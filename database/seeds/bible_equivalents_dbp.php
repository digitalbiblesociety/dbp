<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use Illuminate\Support\Facades\Cache;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleEquivalent;
class bible_equivalents_dbp extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederHelper = new SeederHelper();
        $bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4&gid=1765248815');
        $seederHelper->seedBibleEquivalents($bibleEquivalents,'faith-comes-by-hearing','api','bible.is');
    }
}