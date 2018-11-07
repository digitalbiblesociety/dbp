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

        /*
	    BibleLink::where('provider','bible-gateway')->delete();
	    $organization = \App\Models\Organization\Organization::where('slug','ebible')->first();
	    foreach($bibleEquivalents as $bible_equivalent) {
		    if(!isset($bible_equivalent['bible_id']) OR !isset($bible_equivalent['equivalent_id'])) {continue;}
		    $bible = Bible::find($bible_equivalent['bible_id']);
		    if(!$bible) {continue;}

		    BibleLink::create([
			    'bible_id'          => $bible_equivalent['bible_id'],
			    'url'               => "https://ebible.org/".$bible_equivalent['equivalent_id'],
			    'type'              => 'WEB',
			    'provider'          => 'ebible.org',
			    'organization_id'   => $organization->id,
			    'title'             => 'Simple Web Reader'
		    ]);
	    }
        */

    }
}
