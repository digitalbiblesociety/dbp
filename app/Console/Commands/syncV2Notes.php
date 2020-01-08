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
            $last_note_synced = Note::whereNotNull('v2_id')->where('v2_id', '!=', 0)->orderBy('id', 'desc')->first();
            $from_date = $last_note_synced->created_at ?? Carbon::now()->startOfDay();
        }

        $filesets = BibleFileset::with('bible')->get();
        $this->dam_ids = [];
        $books = Book::select('id_osis', 'id')->get()->pluck('id', 'id_osis')->toArray();

        echo "\n" . Carbon::now() . ': v2 to v4 notes sync started.';
        $chunk_size = config('settings.v2V4SyncChunkSize');

        DB::connection('dbp_users_v2')->table('note')
            ->where('status', 'current')
            ->where('created', '>', $from_date)
            ->orderBy('id')->chunk($chunk_size, function ($notes) use ($filesets, $books) {
                $user_v2_ids = $notes->pluck('user_id')->toArray();
                $note_v2_ids = $notes->pluck('id')->toArray();

                $v4_users = User::whereIn('v2_id', $user_v2_ids)->pluck('id', 'v2_id');
                $v4_notes = Note::whereIn('v2_id', $note_v2_ids)->pluck('v2_id', 'v2_id');

                $dam_ids = $notes->pluck('dam_id')->reduce(function ($carry, $item) use ($filesets) {
                    if (!isset($carry[$item])) {
                        $fileset = $this->getFilesetFromDamId($item, $filesets);
                        if ($fileset) {
                            $carry[$item] = $fileset;
                        }
                    }
                    return $carry;
                }, []);

                $notes = $notes->filter(function ($note) use ($dam_ids, $books, $v4_users, $v4_notes) {
                    return $this->validateNote($note, $dam_ids, $books, $v4_users, $v4_notes);
                });

                $notes = $notes->map(function ($note) use ($v4_users, $books, $dam_ids) {
                    return [
                        'v2_id'       => $note->id,
                        'user_id'     => $v4_users[$note->user_id],
                        'bible_id'    => $dam_ids[$note->dam_id]->bible->first()->id,
                        'book_id'     => $books[$note->book_id],
                        'notes'       => encrypt($note->note),
                        'chapter'     => $note->chapter_id,
                        'verse_start' => $note->verse_id,
                        'verse_end'   => $note->verse_id,
                        'created_at'  => Carbon::createFromTimeString($note->created),
                        'updated_at'  => Carbon::createFromTimeString($note->updated),
                    ];
                });

                $chunks = $notes->chunk(5000);

                foreach ($chunks as $chunk) {
                    Note::insert($chunk->toArray());
                }

                echo "\n" . Carbon::now() . ': Inserted ' . sizeof($notes) . ' new v2 notes.';
            });
        echo "\n" . Carbon::now() . ": v2 to v4 notes sync finalized.\n";
    }

    private function getFilesetFromDamId($dam_id, $filesets)
    {
        if (isset($this->dam_ids[$dam_id])) {
            return $this->dam_ids[$dam_id];
        }

        $fileset = $filesets->where('id', $dam_id)->first();

        if (!$fileset) {
            $fileset = $filesets->where('id', substr($dam_id, 0, -4))->first();
        }
        if (!$fileset) {
            $fileset = $filesets->where('id', substr($dam_id, 0, -2))->first();
        }
        if (!$fileset) {
            // echo "\n Error!! Could not find FILESET_ID: " . substr($dam_id, 0, 6);
            return false;
        }

        $this->dam_ids[$dam_id] = $fileset;

        return $fileset;
    }

    private function validateNote($note, $filesets, $books, $v4_users, $v4_notes)
    {
        if (isset($v4_notes[$note->id])) {
            // echo "\n Error!! Note already inserted: " . $note->id;
            return false;
        }

        if (!isset($v4_users[$note->user_id])) {
            // echo "\n Error!! Could not find USER_ID: " . $note->user_id;
            return false;
        }

        if (!isset($books[$note->book_id])) {
            // echo "\n Error!! Could not find BOOK_ID: " . $note->book_id;
            return false;
        }

        if (!isset($filesets[$note->dam_id])) {
            // echo "\n Error!! Could not find FILESET_ID: " . substr($note->dam_id, 0, 6);
            return false;
        }

        $fileset = $filesets[$note->dam_id];

        if ($fileset->bible->first()) {
            if (!isset($fileset->bible->first()->id)) {
                // echo "\n Error!! Could not find BIBLE_ID";
                return false;
            }
        } else {
            // echo "\n Error!! Could not find BIBLE_ID";
            return false;
        }

        return true;
    }
}
