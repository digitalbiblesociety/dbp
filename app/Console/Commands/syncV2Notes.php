<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;

use App\Models\User\Study\Note;
use Carbon\Carbon;

class syncV2Notes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncV2:notes {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the Notes with the V2 Database';

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

        \DB::connection('dbp_users_v2')->table('note')->where('created', '>', $from_date)
           ->orderBy('id')->chunk(5000, function ($notes) use ($filesets, $books) {
               foreach ($notes as $note) {
                   $fileset = $filesets->where('id', $note->dam_id)->first();
                   if (!$fileset) {
                       $fileset = $filesets->where('id', substr($note->dam_id, 0, -4))->first();
                   }
                   if (!$fileset) {
                       $fileset = $filesets->where('id', substr($note->dam_id, 0, -2))->first();
                   }
                   if (!$fileset) {
                       continue;
                   }
                   if ($fileset->bible->first()) {
                       if (!isset($fileset->bible->first()->id)) {
                           continue;
                       }
                   } else {
                       continue;
                   }
                   if (!isset($books[$note->book_id])) {
                       continue;
                   }

                   $note = Note::create([
                        'v2_id'       => $note->id,
                        'user_id'     => $note->user_id,
                        'bible_id'    => $fileset->bible->first()->id,    //  => "ENGESVO2ET"
                        'book_id'     => $books[$note->book_id],          //  => "Ezra"
                        'chapter'     => $note->chapter_id,               //  => "1"
                        'verse_start' => $note->verse_id,                 //  => "1"
                        'notes'       => encrypt($note->note),
                        'created_at'  => Carbon::createFromTimeString($note->created)->toDateString(),
                        'updated_at'  => Carbon::createFromTimeString($note->updated)->toDateString(),
                    ]);
                   echo "\n Note Processed: ". $note->id;
               }
           });
    }
}
