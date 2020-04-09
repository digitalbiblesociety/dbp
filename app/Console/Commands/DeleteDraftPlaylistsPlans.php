<?php

namespace App\Console\Commands;

use App\Models\Plan\Plan;
use App\Models\Playlist\Playlist;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteDraftPlaylistsPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:draftPlaylistPlans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete 24 hours old draft playlists and plans';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "\n" . Carbon::now() . ': draft playlist and plans deletion started.';

        Playlist::where('draft', 1)->where('created_at', '<', Carbon::now()->subDays(1)->toDateTimeString())->delete();
        Plan::where('draft', 1)->where('created_at', '<', Carbon::now()->subDays(1)->toDateTimeString())->delete();

        echo "\n" . Carbon::now() . ": draft playlist and plans deletion finalized.\n";
    }
}
