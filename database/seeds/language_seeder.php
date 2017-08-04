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
	    \DB::connection('geo_data')->table('languages_translations')->delete();
	    \DB::connection('geo_data')->table('languages_dialects')->delete();
	    \DB::connection('geo_data')->table('languages_codes')->delete();
	    \DB::connection('geo_data')->table('languages_classifications')->delete();
	    \DB::connection('geo_data')->table('languages_altNames')->delete();
    	\DB::connection('geo_data')->table('languages')->delete();

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

	        if(isset($language['dialects'])) {
	        	foreach($language['dialects'] as $dialect) {
			        preg_match_all("/\[([^\]]*)\]/", $dialect, $dialectCodesArray);
			        $dialect = ['language_id' => $langoid->id,'name' => $dialect];

			        if(count($dialectCodesArray[1]) > 0) {
				        $dialectLanguage = Language::where('iso',$dialectCodesArray[1][0])->first();
				        if($dialectLanguage) {
					        $dialect['dialect_id'] = $dialectLanguage->id;
				        }
			        }
			        $langoid->dialects()->create($dialect);
		        }
	        }

	        //

        }

    }

}