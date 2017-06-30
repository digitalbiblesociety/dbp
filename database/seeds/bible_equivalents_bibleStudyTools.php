<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
class bible_equivalents_bibleStudyTools extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederHelper = new SeederHelper();
        $bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4&gid=358371748');
        $seederHelper->seedBibleEquivalents($bibleEquivalents,'bible-study-tools','web-app','biblestudytools.com');
    }
}
