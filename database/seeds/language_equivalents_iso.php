<?php

use Illuminate\Database\Seeder;
use App\Models\Language\LanguageCode;
class language_equivalents_iso extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = json_decode(file_get_contents(storage_path().'/data/languages/language_equivalents_iso2.json'), true);
        foreach($languages as $language) {
            $currentLanguage = \App\Models\Language\Language::where('iso',$language['iso6393'])->first();
            if(!$currentLanguage) {
                continue;
            }
            if(!isset($language['iso6393'])) {
                dd($language);
            }
	        LanguageCode::insert(['language_id' => $currentLanguage->id, 'code' => substr($language['iso6391'],0,3), 'source' => 'Iso 639-2']);
        }

    }
}
