<?php

namespace App\Console\Commands;

use App\Models\Notifications\NotifyToSlack;
use App\Notifications\SyncNotification;
use Illuminate\Console\Command;
use Exception;

class syncV2Database extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncV2:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the v4 Database with the V2 Database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->call('syncV2:users');
            $this->call('syncV2:profiles');
            $this->call('syncV2:highlights');
            $this->call('syncV2:bookmarks');
            $this->call('syncV2:notes');
        } catch (Exception $e) {
            $notification = new SyncNotification();
            $notification->title = 'v2 - v4 sync failed';
            $notification->message = $e->getMessage();
            (new NotifyToSlack())->notify($notification);
        }
    }
}
