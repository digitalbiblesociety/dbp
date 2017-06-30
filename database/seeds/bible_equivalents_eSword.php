<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\BibleEquivalent;
use database\seeds\SeederHelper;
class bible_equivalents_eSword extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederHelper = new SeederHelper();
        $bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4&gid=679014081');
        $seederHelper->seedBibleEquivalents($bibleEquivalents,'crosswire','desktop-app','eSword');
    }
}
