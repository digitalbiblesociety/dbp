<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User\User;
use App\Models\Bible\Book;
use App\Models\Bible\BibleFileset;
use App\Models\User\Study\Bookmark;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class syncV2Bookmarks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncV2:bookmarks {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the Bookmarks with the V2 Database';

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
            $from_date = Carbon::now()->startOfDay();
        }

        $filesets = BibleFileset::with('bible')->get();
        $books = Book::select('id_osis', 'id')->get()->pluck('id', 'id_osis')->toArray();

        DB::connection('dbp_users_v2')->table('bookmark')
            ->where('status', 'current')
            ->where('created', '>', $from_date)
            ->orderBy('id')->chunk(500, function ($bookmarks) use ($filesets, $books) {
                foreach ($bookmarks as $bookmark) {
                    $this->syncBookmark($bookmark, $filesets, $books);
                }
            });
    }

    private function syncBookmark($bookmark, $filesets, $books)
    {
        $fileset = $filesets->where('id', $bookmark->dam_id)->first();
        if (!$fileset) {
            $fileset = $filesets->where('id', substr($bookmark->dam_id, 0, -4))->first();
        }
        if (!$fileset) {
            $fileset = $filesets->where('id', substr($bookmark->dam_id, 0, -2))->first();
        }

        if (!$fileset) {
            echo "\n Error!! Could not find FILESET_ID: " . substr($bookmark->dam_id, 0, 6);
            return;
        }

        if ($fileset->bible->first()) {
            if (!isset($fileset->bible->first()->id)) {
                echo "\n Error!! Could not find BIBLE_ID";
                return;
            }
        } else {
            echo "\n Error!! Could not find BIBLE_ID";
            return;
        }
        if (!isset($books[$bookmark->book_id])) {
            echo "\n Error!! Could not find BOOK_ID: " . $bookmark->book_id;
            return;
        }

        $user_exists = User::where('v2_id', $bookmark->user_id)->first();
        if (!$user_exists) {
            echo "\n Error!! Could not find USER_ID: " . $bookmark->user_id;
            return;
        }

        $v4Bookmark = Bookmark::firstOrNew([
            'v2_id'       => $bookmark->id,
            'user_id'     => $user_exists->id,
            'bible_id'    => $fileset->bible->first()->id,
            'book_id'     => $books[$bookmark->book_id],
            'chapter'     => $bookmark->chapter_id,
            'verse_start' => $bookmark->verse_id,
        ]);

        if (!$v4Bookmark->id) {
            $v4Bookmark->created_at = Carbon::createFromTimeString($bookmark->created);
            $v4Bookmark->updated_at = Carbon::createFromTimeString($bookmark->updated);
            $v4Bookmark->save();
        }

        echo "\n Bookmark Processed: " . $bookmark->id;
    }
}
