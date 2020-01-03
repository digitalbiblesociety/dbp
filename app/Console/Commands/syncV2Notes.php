<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;
use App\Models\User\Study\Note;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $from_date = $this->argument('date');
        if ($from_date) {
            $from_date = Carbon::createFromFormat('Y-m-d', $from_date)->startOfDay();
        } else {
            $from_date = Carbon::now()->startOfDay();
        }

        $filesets = BibleFileset::with('bible')->get();
        $books = Book::select('id_osis', 'id')->get()->pluck('id', 'id_osis')->toArray();

        DB::connection('dbp_users_v2')->table('note')->where('created', '>', $from_date)
            ->orderBy('id')->chunk(5000, function ($notes) use ($filesets, $books) {
                foreach ($notes as $note) {
                    $this->syncNote($note, $filesets, $books);
                }
            });
    }

    private function syncNote($note, $filesets, $books)
    {
        $fileset = $filesets->where('id', $note->dam_id)->first();
        if (!$fileset) {
            $fileset = $filesets->where('id', substr($note->dam_id, 0, -4))->first();
        }
        if (!$fileset) {
            $fileset = $filesets->where('id', substr($note->dam_id, 0, -2))->first();
        }
        if (!$fileset) {
            echo "\n Error!! Could not find FILESET_ID: " . substr($note->dam_id, 0, 6);
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
        if (!isset($books[$note->book_id])) {
            echo "\n Error!! Could not find BOOK_ID: " . $note->book_id;
            return;
        }

        $user_exists = User::where('v2_id', $note->user_id)->first();
        if (!$user_exists) {
            echo "\n Error!! Could not find USER_ID: " . $note->user_id;
            return;
        }

        $v4Note = Note::firstOrNew([
            'v2_id'       => $note->id,
            'user_id'     => $user_exists->id,
            'bible_id'    => $fileset->bible->first()->id,
            'book_id'     => $books[$note->book_id],
            'chapter'     => $note->chapter_id,
            'verse_start' => $note->verse_id,
        ]);

        if (!$v4Note->id) {
            $v4Note->notes = encrypt($note->note);
            $v4Note->verse_end = $note->verse_id;
            $v4Note->created_at = Carbon::createFromTimeString($note->created);
            $v4Note->updated_at = Carbon::createFromTimeString($note->updated);
            $v4Note->save();
        }
        echo "\n Note Processed: " . $note->id;
        die();
    }
}
