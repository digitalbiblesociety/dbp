<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User\Study\HighlightColor;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;

use App\Models\User\User;
use App\Models\User\Study\Highlight;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class syncV2Highlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncV2:highlights {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the Highlights with the V2 Database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $from_date = $this->argument('date') ?? '00-00-00';
        $from_date = Carbon::createFromFormat('Y-m-d', $from_date)->startOfDay();

        $this->highlightColors = HighlightColor::select('color', 'id')->get()->pluck('id','color')->toArray();
        $filesets = BibleFileset::where('set_type_code','text_plain')->where('asset_id','dbp-prod')->get();
        $books = Book::select(['id_osis','id_usfx','id','protestant_order'])->get();

        \DB::connection('dbp_users_v2')
           ->table('highlight')
           ->where('created', '>', $from_date)
           ->orderBy('created')
           ->chunk(10000, function ($highlights) use($filesets, $books) {
               foreach($highlights as $highlight) {
                   $this->syncHighlight($highlight, $filesets, $books);
               }
           });
    }

    private function syncHighlight($highlight, $filesets, $books)
    {
        $fileset = $filesets->where('id',substr($highlight->dam_id,0,6))->first();
        if(!$fileset) {
            Log::driver('seed_errors')->info('bb_nfd_'.$highlight->dam_id);
            echo "\n Error!! Could not find FILESET_ID: ".substr($highlight->dam_id,0,6);
            return;
        }
        $book = $books->where('id_osis', $highlight->book_id)->first();
        if(!$book) {
            $book = $books->where('protestant_order',$highlight->book_id);
            echo "\n Error!! Could not find BOOK_ID: ".$highlight->book_id;
            return;
        }

        if($book === null) {
            Log::driver('seed_errors')->info('bb_nfb_'.$highlight->book_id);
            echo "\n Error!! Could not find BOOK_ID: ".$highlight->book_id;
            return;
        }

        $user_exists = User::where('v2_id',$highlight->user_id)->first();
        if(!$user_exists) {
            Log::driver('seed_errors')->info('bb_nfu_'.$highlight->user_id);
            echo "\n Error!! Could not find USER_ID: ".$highlight->user_id;
            return;
        }

        $v4Highlight = Highlight::firstOrNew([
            'user_id'           => $user_exists->id,
            'hash_id'          => $fileset->hash_id,
            'book_id'           => $book->id,
            'chapter'           => $highlight->chapter_id,
            'verse_start'       => $highlight->verse_id,
            'highlight_start'   => 1,
            'highlighted_chars' => NULL,
            'highlighted_color' => $this->getRelatedColorIdForHighlightColorString($highlight->color),
            'created_at'        => Carbon::createFromTimeString($highlight->created),
            'updated_at'        => Carbon::createFromTimeString($highlight->updated),
        ]);
        $v4Highlight->v2_id = $highlight->id;
        $v4Highlight->save();
        echo "\n Highlight Processed: ". $highlight->id;
    }
}
