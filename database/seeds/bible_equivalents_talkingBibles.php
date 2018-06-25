<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
class bible_equivalents_talkingBibles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $seederHelper = new SeederHelper();
	    $bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4&gid=760436889');
	    $seederHelper->seedBibleEquivalents($bibleEquivalents,'talking-bibles-international','web-app','talkingBibles');

    }

    public function fetch()
    {

        $opts = [
			'http' => [
				'method' => 'GET',
				'header' => 'Authorization:Token token=' . env('TALKING_BIBLES_API')
			]
        ];
        $context = stream_context_create($opts);
        $file = file_get_contents('https://listen.talkingbibles.org/api/v1/recordings.json?page=1', false, $context);
        $count = 25;
        while($count == 25) {
            $page = 1;
            $bibles = json_decode(file_get_contents('https://listen.talkingbibles.org/api/v1/recordings.json?page='.$page, false, $context));
            $page++;
            $count = count($bibles);
            $output[] = $bibles;
        }
        file_put_contents(storage_path('/data/bibles/equivalents/TalkingBibles.json'));

    }
}
