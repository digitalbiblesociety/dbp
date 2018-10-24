<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\Bible;
use App\Models\Bible\BibleEquivalent;
use database\seeds\SeederHelper;
class bible_equivalents_dbl extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	    \DB::transaction(function () {
		    $seederHelper = new SeederHelper();
		    $sheet_id = '1SMEGaeN_jn4rN_mQrc1jQyVqIHHaTMhmpruJpmXUKYs';
		    $gid = '149747612';
		    $bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/'.$sheet_id.'/export?format=csv&id='.$sheet_id.'&gid='.$gid);
		    foreach ($bibleEquivalents as $bible_equivalent) {
			    if ($bible_equivalent['needs_bible'] === '1') {
				    $dbl_bible = json_decode(file_get_contents('https://thedigitalbiblelibrary.org/api/entries/' . $bible_equivalent['dbl_id']));
				    $revision  = end($dbl_bible->revisions);

				    $language = \App\Models\Language\Language::where('iso', $revision->languageCode)->first();

				    switch ($revision->languageNumerals) {
					    case 'Arabic': {
						    $numeral_system = 'western-arabic';
						    break;
					    }
					    case 'Devanagari': {
						    $numeral_system = 'devanagari';
						    break;
					    }
					    default: {
						    dd($revision->languageNumerals);
					    }
				    }

				    switch($revision->scope) {
					    case 'Bible': {
					    	$scope = 'FB';break;
					    }
					    case 'New Testament+': {
						    $scope = 'NTP';break;
					    }
					    case 'New Testament': {
						    $scope = 'NT';break;
					    }
					    case 'Portions': {
						    $scope = 'P';break;
					    }
					    default: {
					    	dd($revision->scope);
					    	break;
					    }
				    }

				    $bible_id = strtoupper($revision->nameAbbreviation);
				    $bible_id = str_replace('-','',$bible_id);
				    if(strlen($bible_id) < 6) $bible_id = strtoupper($revision->languageCode).$bible_id;
				    $bible_id = substr($bible_id,0,12);
				    if(Bible::where('id',$bible_id)->exists()) {
				    	echo "\nPlease review $bible_id as a possible connection for ".$bible_equivalent['dbl_id'];
				    	continue;
				    }

				    preg_match('/\d\d\d\d/',$revision->dateCompleted,$matches);
				    $date = $matches[0] ?? '';

				    $current_bible = Bible::create([
					    'id'                => $bible_id,
					    'language_id'       => $language->id,
					    'date'              => $date,
					    'script'            => $revision->languageScriptCode,
					    'scope'             => $scope,
					    'copyright'         => ltrim(strip_tags($revision->copyrightStatement)),
					    'in_progress'       => false,
					    'versification'     => 'protestant',
					    'numeral_system_id' => $numeral_system
				    ]);
				    $this->createBibleEquivalents($revision, $current_bible);
				    $this->createBibleTranslations($revision, $current_bible);

				    /**
				     * "idParatextName": "PNB1"
				     * "idBiblica": ""
				     * "comments": "BSI"
				     * "id": "36a0f78a6b3da8ba"
				     * "nameAbbreviation": "POCHURY-BSI"
				     * "relationships": []
				     * "pubPromoVersionInfo": ""
				     * "nameAbbreviationLocal": ""
				     * "idSIL": ""
				     * "idParatextFullName": "Pochury Naga Bible1"
				     */

			    }
		    }
	    });

    }

    private function createBibleTranslations($revision,$current_bible)
    {
	    if($revision->nameCommonLocal) {
		    $current_bible->translations()->create([
			    'language_id' => $current_bible->language_id,
			    'name'        => $revision->nameCommonLocal,
			    'vernacular'  => 1,
			    'description' => $revision->description
		    ]);
	    }

	    if($revision->nameCommon) {
		    $english_language = \App\Models\Language\Language::where('iso','eng')->first();
		    $current_bible->translations()->create([
			    'language_id' => $english_language->id,
			    'name'        => $revision->nameCommon,
			    'vernacular'  => 0,
			    'description' => $revision->description
		    ]);
	    }
    }


    private function createBibleEquivalents($revision,$current_bible)
    {
	    if($revision->idParatext) {
		    $sil = \App\Models\Organization\Organization::where('slug','sil-international')->first();
		    $current_bible->equivalents()->create([
			    'type'            => 'archival',
			    'site'            => 'https://paratext.org/',
			    'organization_id' => $sil->id,
			    'equivalent_id'   => $revision->idParatext,
			    'suffix'          => 'Paratext',
		    ]);

		    if($revision->idParatextCset) {
			    $current_bible->equivalents()->create([
				    'type'            => 'archival',
				    'site'            => 'https://paratext.org/',
				    'organization_id' => $sil->id,
				    'equivalent_id'   => $revision->idParatextCset,
				    'suffix'          => 'Paratext Cset',
			    ]);
		    }
	    }
	    if($revision->idGBC) {
		    $ubs = \App\Models\Organization\Organization::where('slug','united-bible-societies')->first();
		    $current_bible->equivalents()->create([
			    'type'            => 'archival',
			    'site'            => 'https://globalbiblecatalogue.org/',
			    'organization_id' => $ubs->id,
			    'equivalent_id'   => $revision->idGBC,
			    'suffix'          => 'Global Bible Catalogue',
		    ]);
	    }

	    if($revision->idBiblica) {
		    $biblica = \App\Models\Organization\Organization::where('slug','biblica')->first();
		    $current_bible->equivalents()->create([
			    'type'            => 'archival',
			    'site'            => 'https://www.biblica.com/',
			    'organization_id' => $biblica->id,
			    'equivalent_id'   => $revision->idBiblica,
			    'suffix'          => 'Biblica',
		    ]);
	    }
    }

}
