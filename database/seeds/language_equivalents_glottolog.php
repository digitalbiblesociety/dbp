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
        $languages = json_decode(file_get_contents("http://glottolog.org/resourcemap.json?rsc=language"));
        foreach($languages->resources as $resource) {
            if(!$resource->identifiers) continue;
            foreach($resource->identifiers as $code) {
                if ($code->type == "iso639-3") {
                    $iso = $code->identifier;
                    $isoExists = Language::where('iso',$iso)->first();
                    if($isoExists) continue;
                    $language = Language::find($resource->id);
                    if(!$language) continue;
                    $language->iso = $iso;
                    $language->save();
                }
            }
        }

        // Run through Dialects
        $seederHelper = new SeederHelper();
        $languages = $seederHelper->csv_to_array(storage_path().'/data/languages/languoid.csv');
        foreach($languages as $language) {
            if($language['level'] == "dialect") {
                $dialect = LanguageDialect::where('name',$language['name'])->first();
                if($dialect) {
                    if($dialect->dialect_id == NULL) {
                        $dialect->dialect_id = $language['id'];
                        $dialect->save();
                    }
                }
            }
        }


    }
}
