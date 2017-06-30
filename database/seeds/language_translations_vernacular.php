<?php

use Illuminate\Database\Seeder;

class language_translations_vernacular extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $langauge = json_decode(file_get_contents(storage_path().'/temp-lif.json'));
        dd($langauge);
    }
}
