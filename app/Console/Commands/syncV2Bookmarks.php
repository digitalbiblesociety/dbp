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
            $last_bookmark_synced = Bookmark::whereNotNull('v2_id')->where('v2_id', '!=', 0)->orderBy('id', 'desc')->first();
            $from_date = $last_bookmark_synced->created_at ?? Carbon::now()->startOfDay();
        }

        $filesets = BibleFileset::with('bible')->get();
        $this->dam_ids = [];
        $books = Book::select('id_osis', 'id')->get()->pluck('id', 'id_osis')->toArray();

        echo "\n" . Carbon::now() . ': v2 to v4 bookmarks sync started.';
        $chunk_size = config('settings.v2V4SyncChunkSize');
        DB::connection('dbp_users_v2')->table('bookmark')
            ->where('status', 'current')
            ->where('created', '>', $from_date)
            ->orderBy('id')->chunk($chunk_size, function ($bookmarks) use ($filesets, $books) {
                $user_v2_ids = $bookmarks->pluck('user_id')->toArray();
                $bookmark_v2_ids = $bookmarks->pluck('id')->toArray();

                $v4_users = User::whereIn('v2_id', $user_v2_ids)->pluck('id', 'v2_id');
                $v4_bookmarks = Bookmark::whereIn('v2_id', $bookmark_v2_ids)->pluck('v2_id', 'v2_id');

                $dam_ids = $bookmarks->pluck('dam_id')->reduce(function ($carry, $item) use ($filesets) {
                    if (!isset($carry[$item])) {
                        if (isset($this->dam_ids[$item])) {
                            $carry[$item] = $this->dam_ids[$item];
                            return $carry;
                        }
                        $fileset = getFilesetFromDamId($item, $filesets);
                        if ($fileset) {
                            $carry[$item] = $fileset;
                            $this->dam_ids[$item] = $fileset;
                        }
                    }
                    return $carry;
                }, []);

                $bookmarks = $bookmarks->filter(function ($bookmark) use ($dam_ids, $books, $v4_users, $v4_bookmarks) {
                    return validateV2Annotation($bookmark, $dam_ids, $books, $v4_users, $v4_bookmarks);
                });

                $bookmarks = $bookmarks->map(function ($bookmark) use ($v4_users, $books, $dam_ids) {
                    return [
                        'v2_id'       => $bookmark->id,
                        'user_id'     => $v4_users[$bookmark->user_id],
                        'bible_id'    => $dam_ids[$bookmark->dam_id]->bible->first()->id,
                        'book_id'     => $books[$bookmark->book_id],
                        'chapter'     => $bookmark->chapter_id,
                        'verse_start' => $bookmark->verse_id,
                        'created_at' => Carbon::createFromTimeString($bookmark->created),
                        'updated_at' => Carbon::createFromTimeString($bookmark->updated),
                    ];
                });

                $chunks = $bookmarks->chunk(5000);

                foreach ($chunks as $chunk) {
                    Bookmark::insert($chunk->toArray());
                }

                echo "\n" . Carbon::now() . ': Inserted ' . sizeof($bookmarks) . ' new v2 bookmarks.';
            });
        echo "\n" . Carbon::now() . ": v2 to v4 bookmarks sync finalized.\n";
    }
}
