<?php

use Illuminate\Database\Seeder;

class bible_strongs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entries = glob(storage_path().'/data/bibles/lexicons/strongs/entries/*.json');

        foreach($entries as $filepath) {
            $strong_id = str_replace('.json','',basename($filepath));
            $entry = json_decode(file_get_contents($filepath));

            // if(!isset($entry->strongs_def)) echo("Missing Strong Def:".$strong_id."\n");
            // if(!isset($entry->strongs_def)) echo("Missing Strong Def:".$strong_id."\n");
            // if(!isset($entry->strongs_def)) echo("Missing KJV Def:".$strong_id."\n");
            // if(!is_int($entry->frequency)) echo("Missing frequency Def:".$filepath."\n");

            DB::table('strongs')->insert([
                'id'         => $strong_id,
                'derivation' => (isset($entry->derivation)) ? $entry->derivation : '',
                'lemma'      => $entry->lemma,
                'frequency'  => (is_int($entry->frequency)) ? $entry->frequency : 1,
                'definition' => (isset($entry->strongs_def)) ? $entry->strongs_def : '',
                'outline'    => $entry->outline
                ]);

            DB::table('strongs_definitions')->insert([
                'strong_id'  => $strong_id,
                'abbr'       => "ENGKJV",
                'definition' => (isset($entry->kjv_def)) ? $entry->kjv_def : '',
            ]);
        }
/*
        $strongs = json_decode(file_get_contents(storage_path().'/data/bibles/lexicons/strongs.json'));
        foreach($strongs as $strong => $verseArray) {
            foreach($verseArray as $verse) {
                DB::table('bible_strongs')->insert(['verse_id' => $verse, 'strong_id' => $strong]);
            }
        }
*/
    }
}
