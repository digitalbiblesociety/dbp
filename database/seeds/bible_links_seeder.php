<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleLink;
use App\Models\Organization\OrganizationTranslation;

class bible_links_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
	    ini_set( 'memory_limit', '600M' );
	    BibleLink::truncate();

	    $seederHelper = new SeederHelper();
	    $bibleLinks   = $seederHelper->csv_to_array( storage_path() . '/data/bibles/bible_links.csv' );

	    foreach ( $bibleLinks as $item ) {
		    $bible = Bible::find( $item['abbr'] );
		    if (!$bible) {continue;}
			$organizationTranslation = OrganizationTranslation::where('name', $item['provider'])->first();
			BibleLink::create([
			    'organization_id' => $organizationTranslation->id ?? null,
			    'title'           => $item['title'],
			    'type'            => $item['type'],
			    'url'             => $item['link'],
			    'provider'        => $item['provider']
			]);
	    }
    }

}
