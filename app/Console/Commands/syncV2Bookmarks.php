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
        $from_date = $this->argument('date') ?? '00-00-00';
        $from_date = Carbon::createFromFormat('Y-m-d', $from_date)->startOfDay();

        $filesets = BibleFileset::with('bible')->get();
        $books = Book::select('id_osis', 'id')->get()->pluck('id', 'id_osis')->toArray();

        DB::connection('dbp_users_v2')->table('bookmark')
            ->where('status', 'current')
            ->where('created', '>', $from_date)
            ->orderBy('id')->chunk(500, function ($bookmarks) use ($filesets, $books) {
                foreach ($bookmarks as $bookmark) {
                    $user_exists = User::where('v2_id', $bookmark->user_id)->first();
                    while (!$user_exists) {
                        $v2_user = DB::connection('dbp_users_v2')->table('user')->where('id', $bookmark->user_id)->first();
                        $user_exists = User::where('email', $v2_user->email)->first();
                        if (isset($user_exists)) {
                            $user_exists->v2_id = $v2_user->id;
                            $user_exists->save();
                            echo "\nUser v2 id updated";
                        } else {
                            sleep(15);
                            echo 'waiting for users seeder';
                            continue;
                        }
                    }

                    $fileset = $filesets->where('id', $bookmark->dam_id)->first();
                    if (!$fileset) {
                        $fileset = $filesets->where('id', substr($bookmark->dam_id, 0, -4))->first();
                    }
                    if (!$fileset) {
                        $fileset = $filesets->where('id', substr($bookmark->dam_id, 0, -2))->first();
                    }

                    if (!$fileset) {
                        echo "\nSkipping $bookmark->dam_id";
                        continue;
                    }

                    if (!$fileset->bible->first()) {
                        echo "\n Skipping" . $bookmark->dam_id;
                        continue;
                    }

                    if (!isset($books[$bookmark->book_id])) {
                        echo "\n Skipping $bookmark->book_id";
                        continue;
                    }

                    $bookmark = Bookmark::firstOrNew([
                        'user_id'     => $user_exists->id,
                        'bible_id'    => $fileset->bible->first()->id,
                        'book_id'     => $books[$bookmark->book_id],
                        'chapter'     => $bookmark->chapter_id,
                        'verse_start' => $bookmark->verse_id,
                        'created_at'  => Carbon::createFromTimeString($bookmark->created)->toDateString(),
                        'updated_at'  => Carbon::createFromTimeString($bookmark->updated)->toDateString()
                    ]);
                    $bookmark->v2_id = $bookmark->id;
                    $bookmark->save();


                    echo "\n" . $bookmark->id;
                }
            });
    }
}
