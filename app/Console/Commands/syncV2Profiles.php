<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User\User;
use App\Models\Language\Language;
use App\Models\Country\Country;
use App\Models\User\Profile;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

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
                ->select(['users.created_at'])
                ->orderBy('users.id', 'desc')->first();
            $from_date = $last_profile_synced->created_at ?? Carbon::now()->startOfDay();
        }

        $countries = Country::all();
        DB::connection('dbp_users_v2')->table('user_profile')->where('field_value', '!=', '')
            ->where('created', '>', $from_date)
            ->orderBy('id')
            ->chunk(5000, function ($profiles) use ($countries) {
                foreach ($profiles as $profile) {
                    $user_exists = User::where('v2_id', $profile->user_id)->first();
                    if (!$user_exists) {
                        echo "\n Error!! Could not find USER_ID: " . $profile->user_id;
                        continue;
                    }

                    echo "\nProcessing profile: " . $profile->id;
                    $current_profile = Profile::where('user_id', $user_exists->id)->first();
                    $is_new = false;
                    if (!$current_profile) {
                        $current_profile = Profile::create([
                            'user_id' => $user_exists->id,
                            'created_at' => Carbon::createFromTimeString($profile->created),
                            'updated_at' => Carbon::createFromTimeString($profile->updated),
                        ]);
                        $is_new = true;
                    }

                    if (!$is_new && Carbon::parse($current_profile->updated_at) > Carbon::parse($profile->updated)) {
                        echo "\nProfile already updated";
                        continue;
                    }

                    if (\strlen($profile->field_value) > 191) {
                        echo "\nToo long for " . $profile->field_name . ': ' . $profile->field_value;
                        continue;
                    }
                    switch ($profile->field_name) {
                        case 'country': {
                                if ($profile->field_value == 'USA' || $profile->field_value == 'United States of America' || $profile->field_value == 'US') {
                                    $current_profile->country_id = 'US';
                                } else {
                                    $current_country = $countries->where('name', $profile->field_value)->first();
                                    if (!$current_country) {
                                        echo "\nCountry not found";
                                        continue;
                                    }
                                    $current_profile->country_id = $current_country->id;
                                }
                                break;
                            }
                        case 'birthday':
                        case 'birthdate': {
                                break;
                                $current_date = str_replace('_', '/', $profile->field_value);
                                $current_date = str_replace('-', '/', $current_date);
                                try {
                                    $current_date = \Carbon\Carbon::parse($current_date)->toDateTimeString();
                                    $current_profile->birthday = $current_date;
                                    if (!$current_date) {
                                        break;
                                    }
                                } catch (Exception $e) {
                                    break;
                                }
                                break;
                            }
                        case 'gender': {
                                $current_profile->sex = ($profile->field_value == 'female') ? 2 : 1;
                                break;
                            }
                        case 'download_status':
                        case 'testitem':
                        case 'testitem2':
                        case 'age_range': {
                                break;
                            }
                        case 'phone_number':
                        case 'phone': {
                                if (\strlen($profile->field_value) > 22) {
                                    echo "\nToo long for phone:".$profile->field_value;
                                    break;
                                }
                                $current_profile->phone = $profile->field_value;
                                break;
                            }
                        case 'locale': {
                                $lang = Language::where('iso2B', substr($profile->field_value, 0, 2))->select('id')->first();
                                if ($lang) {
                                    $current_profile->language_id = $lang->id;
                                }
                                break;
                            }
                        default: {
                                $current_profile->{$profile->field_name} = $profile->field_value;
                            }
                    }
                    $current_profile->save();
                    echo "\nProfile updated";
                }
            });
    }
}
