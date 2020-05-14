<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User\Study\Note;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class reSyncV2Notes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reSyncV2:notes {note_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re Sync the Notes that are not edited with the V2 Database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $note_id = $this->argument('note_id');
        $this->alert(Carbon::now() . ': v2 to v4 notes re sync started.');
        $db_v2_connection = DB::connection('dbp_users_v2');
        $db_v2_utf8_connection = DB::connection('dbp_users_v2_utf8');

        $db_users_connection = DB::connection('dbp_users');

        $chunk_size = $note_id ? 1 : 1000;
        do {
            $query = $db_users_connection
                ->table('user_notes')
                ->where('v2_id', '!=', 0)
                ->when($note_id, function ($query, $note_id) {
                    return $query->whereId($note_id);
                })
                ->unless($note_id, function ($query) {
                    return $query->where('bookmark', '=', 0);
                })
                ->orderBy('v2_id')->limit($chunk_size);

            $v4_notes = $query->get();
            $remaining = $query->count();
            $this->info(Carbon::now() . ': ' . $remaining . ' remaining to process.');

            $v2_ids = $v4_notes->pluck('v2_id');
            $v4_updated_dates = $v4_notes->pluck('updated_at', 'v2_id');
            $v4_created_dates = $v4_notes->pluck('created_at', 'v2_id');
            $v4_ids = $v4_notes->pluck('id', 'v2_id');

            $v2_fields = ['id', 'created', 'updated', 'note'];
            $v2_notes = $db_v2_connection->table('note')->select($v2_fields)->whereIn('id', $v2_ids)->get();
            $v2_utf8_notes = $db_v2_utf8_connection->table('note')->select($v2_fields)->whereIn('id', $v2_ids)->get()->pluck('note', 'id');

            $v2_notes = $v2_notes->map(function ($note) use ($v2_utf8_notes, $v4_created_dates, $v4_updated_dates, $note_id) {
                // Force a resync when note_id is provided
                if ($note_id) {
                    $note->resync = true;
                    return $note;
                }
                // If v2_note is different than v2_utf8_note
                $note->resync = $v2_utf8_notes[$note->id] !== $note->note;

                if ($note->resync) {
                    $note->resync = false;
                    // v2 note is newer or equal than v4
                    $v2_note_is_gte = Carbon::createFromTimeString($note->updated)->gte(Carbon::createFromTimeString($v4_updated_dates[$note->id]));

                    // Different creation dates on v4 -> v2
                    if ($v4_created_dates[$note->id] !== $note->created) {
                        // Same v4 updated_at and v4 created_at values means no change
                        if ($v4_created_dates[$note->id] === $v4_updated_dates[$note->id] || $v2_note_is_gte) {
                            $note->resync = true;
                        }
                        // If the v2 note updated date is the same or greater than v4 update date resync
                    } elseif ($v2_note_is_gte) {
                        $note->resync = true;
                    }
                }
                return $note;
            })->filter(function ($note) {
                return $note->resync;
            });

            // Encrypt notes using background process
            $processes = [];
            foreach ($v2_notes as $v2_note) {
                $base64_note = base64_encode($v2_note->note);
                $process = new Process('php ' . base_path('artisan') . " encrypt {$base64_note} {$v2_note->id}");
                $process->setTimeout(0);
                $process->start();
                $processes[] = $process;
            }
            $process_results = [];
            while (count($processes)) {
                foreach ($processes as $i => $runningProcess) {
                    // specific process is finished, so we remove it
                    if (!$runningProcess->isRunning()) {
                        $output = json_decode($runningProcess->getOutput());
                        $process_results[$output->id] = $output->value;
                        unset($processes[$i]);
                    }
                }
            }
            $query = '';
            foreach ($v2_notes as $v2_note) {
                $query .= 'UPDATE user_notes set notes = "'
                    . $process_results[$v2_note->id] . '", updated_at = "'
                    . Carbon::createFromTimeString($v2_note->updated) . '", bookmark = 1 where id = '
                    . $v4_ids[$v2_note->id] . '; ';
            }
            if ($query) {
                $db_users_connection->unprepared($query);
            }
            $this->line(Carbon::now() . ': Re synced ' . $v2_notes->count() . ' of ' . $chunk_size . ' v2 notes.');
            if (!$v4_notes->isEmpty()) {
                $v4_ids = $v4_notes->pluck('id');
                $db_users_connection->unprepared('UPDATE user_notes set updated_at = updated_at, bookmark = 1 where id IN (' . implode(',', $v4_ids->toArray()) . ');');
            }
        } while (!$v4_notes->isEmpty() && !$note_id);

        $this->alert(Carbon::now() . ": v2 to v4 notes re sync finalized.\n");
    }
}
