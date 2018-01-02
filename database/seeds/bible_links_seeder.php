<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleLink;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationTranslation;

class bible_links_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
    	$mappedLinks = [
		    "http://amzn.to/1CPXlji"=>"third-millennium-bible",
		    "http://amzn.to/1egP1gq"=>"misc",
		    "http://amzn.to/1CQmdYc"=>"misc",
		    "http://amzn.to/1CQn1fu"=>"bible-in-my-language",
		    "http://amzn.to/1CSSei9"=>"american-bible-society",
		    "http://amzn.to/1CTxI0O"=>"hodder-stoughton-religious",
		    "http://amzn.to/1DhmE8u"=>"bible-society-of-germany",
		    "http://amzn.to/1DhrQsW"=>"american-bible-society",
		    "http://amzn.to/1DhrSku"=>"bible-in-my-language",
		    "http://amzn.to/1DhrYIY"=>"biblica",
		    "http://amzn.to/1Dhsdnu"=>"bible-society-of-nigeria",
		    "http://amzn.to/1DsJ6vj"=>"bible-society-of-italy",
		    "http://amzn.to/1TOoEPc"=>"bible-society-of-italy",
		    "http://amzn.to/1g2k7u8"=>"bible-society-of-geneva",
		    "http://amzn.to/1DsL5zL"=>"living-stream-ministry",
		    "http://amzn.to/1g2n9hZ"=>"living-stream-ministry",
		    "http://amzn.to/1ecuyJL"=>"bible-in-my-language",
		    "http://amzn.to/1etBtON"=>"bible-in-my-language",
		    "http://amzn.to/2aR9n4u"=>"bible-in-my-language",
		    "http://amzn.to/1fJNdhM"=>"american-bible-society",
		    "http://amzn.to/1fJNPE5"=>"american-bible-society",
		    "http://amzn.to/1flRfff"=>"thomas-nelson",
		    "http://amzn.to/1fPCyl6"=>"bible-in-my-language",
		    "http://amzn.to/1fQgZRO"=>"misc",
		    "http://amzn.to/1fQTHLM"=>"bible-in-my-language",
		    "http://amzn.to/1FwNsbs"=>"misc",
		    "http://amzn.to/1gCn8RM"=>"bible-in-my-language",
		    "http://amzn.to/1gCnAQ5"=>"bible-society-of-pakistan",
		    "http://amzn.to/1gCnzLV"=>"american-bible-society",
		    "http://amzn.to/1GCVA3K"=>"baker-publishing-group",
		    "http://amzn.to/1LU1hlm"=>"baker-publishing-group",
		    "http://amzn.to/1GD0NIA"=>"bible-domain-publishing",
		    "http://amzn.to/1QNoWDp"=>"alan-martin",
		    "http://amzn.to/1GHC4Dc"=>"alan-martin"
	    ];

    	$mapped = [
    		'Scripture Earth' => 'wycliffe-global-alliance',
		    'Google Play'     => 'wycliffe-global-alliance'
	    ];
	    ini_set( 'memory_limit', '600M' );
	    BibleLink::truncate();

	    $seederHelper = new SeederHelper();
	    $bibleLinks   = $seederHelper->csv_to_array( storage_path() . '/data/bibles/bible_links.csv' );

	    foreach ( $bibleLinks as $item ) {
		    $bible = Bible::find( $item['abbr'] );
		    if (!$bible) {continue;}
			$organizationTranslation = OrganizationTranslation::where('name', $item['provider'])->first();
		    if(!$organizationTranslation) {
		    	$slug = (key_exists($item['provider'],$mapped)) ? $mapped[$item['provider']] : $item['provider'];
		    	$organization = Organization::where('slug',$slug)->first();
		    } else {
		    	$organization = $organizationTranslation->organization;
		    }
			BibleLink::create([
				'bible_id'        => $item['abbr'],
			    'organization_id' => $organization->id ?? null,
			    'title'           => $item['title'],
			    'type'            => $item['type'],
			    'url'             => $item['link'],
			    'provider'        => $item['provider']
			]);
	    }
    }

}
