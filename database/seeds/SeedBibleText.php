<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\Book;
use App\Models\Bible\BibleVerse;
use App\Models\Bible\BibleFileset;

class SeedBibleText extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '4000M');

        $filesets = BibleFileset::where('set_type_code', 'text_plain')->get();
        $books  = Book::select('id', 'id_usfx')->get()->pluck('id', 'id_usfx');
        unset($books['']);

        $tables = collect(DB::connection('sophia')->select('SHOW TABLES'))->pluck('Tables_in_sophia');
        foreach ($tables as $table) {
            $finished_tables_path = storage_path('finished_sophia_tables.json');
            $finished_tables = json_decode(file_get_contents($finished_tables_path));

            //$filesets
            if (substr($table, -3) === 'vpl') {
                $fileset = $filesets->where('id', substr($table, 0, -4))->first();

                if (in_array($table, $finished_tables)) {
                    continue;
                }

                if (!$fileset) {
                    echo "\n Skipping $table";
                    continue;
                }

                \DB::connection('sophia')->table($table)->orderBy('canon_order')->chunk(5000, function ($verses) use ($fileset,$books,$table) {
                    $verse_text_combined = '';
                    $verse_number_combined = 0;

                    foreach ($verses as $key => $verse) {
                        if (!isset($books[$verse->book])) {
                            echo "\n Skipping Book". $verse->book;
                            continue;
                        }

                        $verseIsSplit = str_contains($verse->canon_order, ['a','b','c','d','e']);

                        if (!$verseIsSplit) {
                            $verse_text_combined = '';
                        }

                        if (str_contains($verse->canon_order, ['a'])) {
                            $verse_text_combined = $verse->verse_text;
                            $verse_number_combined = $verse->verse_start;
                            continue;
                        } elseif ($verseIsSplit) {
                            $verse_text_combined .= $verse->verse_text;

                            if ($verses[$key + 1]->verse_start === $verse_number_combined) {
                                continue;
                            }
                        }

                        if (\DB::connection('dbp')->table('bible_verses')->where([
                            'hash_id'     => $fileset->hash_id,
                            'book_id'     => $books[$verse->book],
                            'chapter'     => $verse->chapter,
                            'verse_start' => $verse->verse_start,
                        ])->exists()) {
                            $skipped_tables[] = $table;
                            file_put_contents(storage_path('skipped_sophia_tables.json'), json_encode($skipped_tables));
                            break;
                        }

                        \DB::connection('dbp')->table('bible_verses')->insert([
                            'hash_id'     => $fileset->hash_id,
                            'book_id'     => $books[$verse->book],
                            'chapter'     => $verse->chapter,
                            'verse_start' => $verse->verse_start,
                            'verse_end'   => $verse->verse_end,
                            'verse_text'  => ($verse_text_combined !== '') ? $verse_text_combined : $verse->verse_text
                        ]);
                    }
                });

                $finished_tables[] = $table;
                file_put_contents($finished_tables_path, json_encode($finished_tables));
            }
        }
    }
}
