<?php

namespace App\Console\Commands;

use App\Models\Playlist\PlaylistItems;
use Illuminate\Console\Command;
use Carbon\Carbon;

class syncPlaylistDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:playlistDuration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the playlist items duration with the database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "\n" . Carbon::now() . ': playlist items sync started.';

        $playlist_items = PlaylistItems::all();

        foreach ($playlist_items as $playlist_item) {
            $playlist_item->calculateDuration()->save();
        }

        echo "\n" . Carbon::now() . ": playlist items sync finalized.\n";
    }
}
