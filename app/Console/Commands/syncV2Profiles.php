<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User\User;
use App\Models\Country\Country;
use App\Models\User\Profile;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class syncV2Profiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncV2:profiles {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the Profiles with the V2 Database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $from_date = $this->argument('date');
        if ($from_date) {
            $from_date = Carbon::createFromFormat('Y-m-d', $from_date)->startOfDay();
        } else {
            $last_profile_synced = User::whereNotNull('v2_id')->where('v2_id', '!=', 0)
                ->join(config('database.connections.dbp_users.database') . '.profiles', 'users.id', '=', 'profiles.user_id')
                ->select(['profiles.created_at'])
                ->orderBy('profiles.created_at', 'desc')->first();
            $from_date = $last_profile_synced->created_at ?? Carbon::now()->startOfDay();
        }

        echo "\n" . Carbon::now() . ': v2 to v4 profiles sync started.';
        $chunk_size = config('settings.v2V4SyncChunkSize');
        $countries = Country::all()->pluck('id', 'name');

        $profile_columns = Schema::connection('dbp_users')->getColumnListing('profiles');
        DB::connection('dbp_users_v2')->table('user_profile')
            ->where('field_value', '!=', '')
            ->where('created', '>', $from_date)
            ->orderBy('id')
            ->chunk($chunk_size, function ($profiles) use ($countries, $profile_columns) {
                $user_v2_ids = $profiles->pluck('user_id')->toArray();
                $v4_users = User::whereIn('v2_id', $user_v2_ids)->pluck('id', 'v2_id');

                $profiles = $profiles->filter(function ($profile) use ($v4_users) {
                    if (\strlen($profile->field_value) > 191) {
                        // echo "\nToo long for " . $profile->field_name . ': ' . $profile->field_value;
                        return false;
                    }

                    if (!isset($v4_users[$profile->user_id])) {
                        // echo "\n Error!! Could not find USER_ID: " . $profile->user_id;
                        return false;
                    }

                    return true;
                });

                $v4_users_ids = $profiles->map(function ($profile) use ($v4_users) {
                    return $v4_users[$profile->user_id];
                })->unique()->flatten();

                $v4_profiles = Profile::whereIn('user_id', $v4_users_ids)->pluck('user_id', 'user_id');

                $profiles = $profiles->reduce(function ($carry, $profile) use ($v4_users, $profile_columns, $countries) {
                    $v4_user_id = $v4_users[$profile->user_id];
                    if (!isset($carry[$v4_user_id])) {
                        $carry[$v4_user_id]['created_at'] = [];
                        $carry[$v4_user_id]['updated_at'] = [];
                        $carry[$v4_user_id]['user_id'] = $v4_user_id;
                    }

                    $carry[$v4_user_id]['created_at'][] = Carbon::createFromTimeString($profile->created);
                    $carry[$v4_user_id]['updated_at'][] = Carbon::createFromTimeString($profile->updated);
                    switch ($profile->field_name) {
                        case 'birthday':
                        case 'birthdate': {
                                try {
                                    $current_date = Carbon::parse($profile->field_value)->toDateTimeString();
                                    $carry[$v4_user_id]['birthday'] = $current_date;
                                } catch (Exception $e) {
                                    break;
                                }
                                break;
                            }
                        case 'country': {
                                if ($profile->field_value == 'USA' || $profile->field_value == 'United States of America' || $profile->field_value == 'US') {
                                    $carry[$v4_user_id]['country_id'] = 'US';
                                    break;
                                }
                                if (isset($countries[$profile->field_value])) {
                                    $carry[$v4_user_id]['country_id'] = $countries[$profile->field_value];
                                }
                                break;
                            }
                        case 'gender': {
                                $carry[$v4_user_id]['sex'] = ($profile->field_value == 'female') ? 2 : 1;
                                break;
                            }
                        case 'phone_number':
                        case 'phone': {
                                if (\strlen($profile->field_value) > 22) {
                                    // echo "\nToo long for phone:" . $profile->field_value;
                                    break;
                                }
                                $carry[$v4_user_id]['phone'] = $profile->field_value;
                                break;
                            }

                        default: {
                                if (in_array($profile->field_name, $profile_columns)) {
                                    $carry[$v4_user_id][$profile->field_name] = $profile->field_value;
                                }
                            }
                    }

                    return $carry;
                }, []);

                $created = 0;
                $updated = 0;

                foreach ($profiles as $v4_user_id => $profile) {
                    $profile['created_at'] = collect($profile['created_at'])->max();
                    $profile['updated_at'] = collect($profile['updated_at'])->max();

                    if (!isset($v4_profiles[$v4_user_id])) {
                        $current_profile = Profile::create($profile);
                        $created++;
                        continue;
                    }

                    $current_profile = Profile::where('user_id', $v4_user_id)->first();
                    if (Carbon::parse($current_profile->updated_at) > Carbon::parse($profile['updated_at'])) {
                        // echo "\nProfile already updated";
                        continue;
                    }

                    $current_profile->update($profile);
                    $updated++;
                }
                echo "\n" . Carbon::now() . ': Inserted ' . $created . ' new v2 profiles.';
                echo "\n" . Carbon::now() . ': Updated ' . $updated . ' v2 profiles.';
            });

        echo "\n" . Carbon::now() . ": v2 to v4 profiles sync finalized.\n";
    }
}
