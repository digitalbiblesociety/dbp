<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use App\Models\Organization\Organization;
class bible_equivalents_bibleGateway extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederHelper = new SeederHelper();
        $bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4');
        $seederHelper->seedBibleEquivalents($bibleEquivalents,'bible-gateway','web-app','biblegateway.com');
    }
}
