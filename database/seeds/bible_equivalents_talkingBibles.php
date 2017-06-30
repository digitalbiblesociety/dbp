<?php

use Illuminate\Database\Seeder;

class bible_equivalents_talkingBibles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->fetch();
    }

    public function fetch()
    {

        $opts = array(
        'http'=>array(
            'method'=>"GET",
            'header'=>"Authorization:Token token=\"5ef65c3e6aa59f9290939a0999e32d07\""
        )
    );
        $context = stream_context_create($opts);
        $file = file_get_contents('https://listen.talkingbibles.org/api/v1/recordings.json?page=1', false, $context);
        dd($file);
        $count = 25;
        while($count == 25) {
            $page = 1;
            $bibles = json_decode(file_get_contents('https://listen.talkingbibles.org/api/v1/recordings.json?page='.$page, false, $context));
            $page++;
            $count = count($bibles);
            $output[] = $bibles;
        }

    }
}
