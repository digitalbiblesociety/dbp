<?php

use Illuminate\Database\Seeder;
use App\Models\Language\Language;
use App\Models\Language\LanguageCode;
use App\Models\Language\LanguageDialect;
use database\seeds\SeederHelper;

class language_equivalents_glottolog extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $seederHelper = new SeederHelper();
        $languages = json_decode(file_get_contents("http://glottolog.org/resourcemap.json?rsc=language"));
	    $languoids = $seederHelper->csv_to_array(storage_path().'/data/languages/languoid.csv');
        foreach($languages->resources as $resource) {
            if(!$resource->identifiers) continue;
            foreach($resource->identifiers as $code) {
                if ($code->type == "iso639-3") {
                    $iso = $code->identifier;
                    $isoExists = Language::where('iso',$iso)->first();
                    if($isoExists) continue;
                    $language = Language::where('glotto_id',$resource->id)->first();
                    if(!$language) continue;
                    $language->iso = $iso;
                    $language->save();
                }
            }
        }

	    // Run through Dialects
	    foreach($languoids as $languoid) {
		    if($languoid['level'] == "dialect") {
			    $dialect = LanguageDialect::where('name',$languoid['name'])->first();
			    if($dialect) {
				    if($dialect->dialect_id == NULL) {
				    	$language = Language::where('glotto_id', $languoid['id'])->first();
				    	if(!$language) { continue; }
					    $dialect->id = $language->id;
					    $dialect->save();
				    }
			    }
		    }
	    }

    }
}
