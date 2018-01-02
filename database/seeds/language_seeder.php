<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
use Symfony\Component\Yaml\Yaml;
use App\Models\Language\LanguageDialect;
use App\Models\Language\LanguageAltName;
use App\Models\Language\LanguageClassification;

use App\Models\Country\Country;
use App\Models\Country\CountryLanguage;
class language_seeder extends Seeder
{

    public function run()
    {
	    \DB::table('languages_translations')->delete();
	    \DB::table('languages_dialects')->delete();
	    \DB::table('languages_codes')->delete();
	    \DB::table('languages_classifications')->delete();
	    \DB::table('languages_altNames')->delete();
    	\DB::table('languages')->delete();

	    $seederHelper = new \database\seeds\SeederHelper();
        $languages = Yaml::parse(file_get_contents(storage_path().'/data/languages/languages.yaml'));
        foreach($languages as $id => $language) {

            // Skip Entries Not officially in the Glottolog
            if (strpos($language['code+name'], 'NOCODE_') !== false) {continue;}

            $langoid = new Language();
            $langoid->glotto_id = $id;
            $langoid->name = $language['name'] ?? NULL;
            $langoid->iso = $language['iso_639-3'] ?? NULL;
            $langoid->status = $language['language_status'] ?? NULL;
            $langoid->maps = $language['language_maps'] ?? NULL;
            $langoid->development = $language['language_development'] ?? NULL;
            $langoid->use = $language['language_use'] ?? NULL;
            $langoid->location = $language['location'] ?? NULL;
            $langoid->area = $language['macroarea-gl'] ?? NULL;
            $langoid->population = $language['population_numeric'] ?? NULL;
            $langoid->population_notes = $language['population'] ?? NULL;
            $langoid->notes = $language['other_comments'] ?? NULL;
            $langoid->latitude = $language['coordinates']['latitude'] ?? NULL;
            $langoid->longitude = $language['coordinates']['longitude'] ?? NULL;
            $langoid->country_id = $country ?? NULL;
            if(isset($language['typology'])) $langoid->typology = implode(',', $language['typology']);
            if(isset($language['writing'])) $langoid->writing = implode(',', $language['writing']);
            $langoid->save();


	        if(isset($language['alternate_names'])) {
	        	foreach($language['alternate_names'] as $altName) {
	        		$langoid->alternativeNames()->create(['language_id' => $langoid->id,'name' => $altName]);
		        }
	        }

	        if(isset($language['classification-gl'])) {
		        foreach ($language['classification-gl'] as $order => $classification) {
			        preg_match_all("/\[([^\]]*)\]/", $classification, $classCodesArray);
			        $langoid->classifications()->create([
			            'order'             => $order,
			            'language_id'       => $langoid->id,
			            'classification_id' => $classCodesArray[1][0],
			            'name'              => $language['classification'][$order] ?? $classification
			        ]);
		        }
	        }

	        /*
	        if(isset($language['dialects'])) {
	        	foreach($language['dialects'] as $dialect) {
			        preg_match_all("/\[([^\]]*)\]/", $dialect, $dialectCodesArray);
			        $dialect = ['language_id' => $langoid->id,'name' => $dialect];

			        if(count($dialectCodesArray[1]) > 0) {
				        $dialectLanguage = Language::where('iso',$dialectCodesArray[1][0])->first();
				        if($dialectLanguage) {
					        $dialect['dialect_id'] = $dialectLanguage->id;
					        $langoid->dialects()->create($dialect);
				        }

			        }

		        }
	        }*/

        }

	    // Double Check with Iso Data
	    $languages = $seederHelper->tsv_to_collection(storage_path('data/languages/ethnologue/iso-639-3_20170202.tab'));
	    foreach ($languages as $language) {
		    $current_language = Language::where('iso',$language['Id'])->first();
		    if(!$current_language) $current_language = new Language();

		    $current_language->iso = $language['Id'];
		    $current_language->name = $language['Ref_Name'];
		    $current_language->iso2B = ($language['Part2B'] != '') ? $language['Part2B'] : null;
		    $current_language->iso2T = ($language['Part2T'] != '') ? $language['Part2T'] : null;
		    $current_language->iso1 = ($language['Part1'] != '') ? $language['Part1'] : null;
		    $current_language->save();
	    }

	    $language_dialects = $seederHelper->tsv_to_collection(storage_path('data/languages/ethnologue/iso-639-3-macrolanguages_20170131.tab'));
	    foreach($language_dialects as $dialect) {
	    	if(!isset($dialect["M_Id"]) OR !isset($dialect["I_Id"])) { continue; }
		    $iso_codes_to_search_by[] = $dialect["M_Id"];
		    $iso_codes_to_search_by[] = $dialect["I_Id"];
	    }
	    $iso_codes_to_search_by = array_unique($iso_codes_to_search_by);
	    $current_languages = Language::whereIn('iso',$iso_codes_to_search_by)->get();
	    foreach ($language_dialects as $language_dialect) {
	    	$language_id = $current_languages->where('iso',$language_dialect["M_Id"])->first();
	    	if(!$language_id) { continue; }
		    $language_id = $language_id->id;
	    	$dialect_id = $current_languages->where('iso',$language_dialect["I_Id"])->first();
		    if(!$dialect_id) { continue; }
		    $dialect_id = $dialect_id->id;
	    	$dialect = LanguageDialect::where('language_id',$language_id)->where('dialect_id',$dialect_id)->first();
	    	if($dialect) {continue;}
			$current_dialect = new LanguageDialect();
			$current_dialect->language_id = $language_id;
		    $current_dialect->dialect_id = $dialect_id;
		    $current_dialect->name = $current_languages->where('iso',$language_dialect["I_Id"])->first()->name;
		    $current_dialect->save();
	    }

    }

}