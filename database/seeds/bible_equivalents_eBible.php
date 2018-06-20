<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\BibleEquivalent;
use database\seeds\SeederHelper;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleLink;
class bible_equivalents_eBible extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $seederHelper = new SeederHelper();
	    $bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4&gid=1002399925');
	    $seederHelper->seedBibleEquivalents($bibleEquivalents,'ebible','website','ebible.org');

	    BibleLink::where('provider','eBible.org')->delete();
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

		    BibleLink::create([
		    	'bible_id'          => $bible_equivalent['bible_id'],
			    'url'               => "https://ebible.org/sword/zip/".$bible_equivalent['equivalent_id'].'.zip',
			    'type'              => 'APP',
			    'provider'          => 'ebible.org',
			    'organization_id'   => $organization->id,
			    'title'             => 'Sword Module',
			    'download_size'     => 'unknown' //$seederHelper->remote_filesize("https://ebible.org/sword/zip/".$bible_equivalent['equivalent_id'].'.zip')
		    ]);
	    }

    }
}
