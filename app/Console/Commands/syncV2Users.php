<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class syncV2Users extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncV2:users {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the Users with the V2 Database';

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
            $last_user_synced = User::whereNotNull('v2_id')->where('v2_id', '!=', 0)->orderBy('id', 'desc')->first();
            $from_date = $last_user_synced->created_at ?? Carbon::now()->startOfDay();
        }
        DB::connection('dbp_users_v2')->table('user')->where('created', '>', $from_date)->orderBy('id')
            ->chunk(50000, function ($users) {
                foreach ($users as $user) {
                    $user_exists = User::where('v2_id', $user->id)->orWhere('email', $user->email)->first();
                    if (!$user_exists) {
                        echo "\nCreating user v2 id: " . $user->id;
                        User::create([
                            'v2_id'            => $user->id,
                            'name'             => $user->username ?? $user->email,
                            'password'         => bcrypt($user->password),
                            'first_name'       => $user->first_name,
                            'last_name'        => $user->last_name,
                            'token'            => Str::random(24),
                            'email'            => $user->email,
                            'activated'        => (int) $user->confirmed,
                            'created_at'       => Carbon::createFromTimeString($user->created),
                            'updated_at'       => Carbon::createFromTimeString($user->updated),
                        ]);
                    } else {
                        echo "\nUser already created v2 id: " . $user->id;
                    }
                }
            });
    }
}
