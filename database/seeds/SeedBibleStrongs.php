<?php

use Illuminate\Database\Seeder;

class SeedBibleStrongs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $strong_paths = glob(storage_path('data/bibles/lexicons/strongs/entries/*.json'));

        foreach ($strong_paths as $strong_path) {

            $strong_reference = json_decode(file_get_contents($strong_path));

            $strong = [
                'strong_number'     => basename($strong_path, '.json'),
                'root_word'         => $strong_reference->lemma,
                'definition'        => $strong_reference->strongs_def,
                'usage'             => $strong_reference->outline,
            ];

        }

    }
}
