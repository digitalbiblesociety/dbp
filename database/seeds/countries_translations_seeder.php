<?php

use Illuminate\Database\Seeder;
use \App\Models\Language\Language;
use \App\Models\Language\LanguageCode;
use \App\Models\Country\Country;
use App\Models\Country\CountryTranslation;
use \database\seeds\SeederHelper;

class countries_translations_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $countries = glob(storage_path().'/data/countries/translations/*.json');
        foreach($countries as $country) {
            $current_country = json_decode(file_get_contents($country), true, 512, JSON_UNESCAPED_UNICODE);
            $iso = basename($country, '.json');
            $current_language = Language::where('iso','=', $iso)->first();
            if(!$current_language) {
                continue;
            } else {
                $current_language = $current_language;
            }

                foreach($current_country as $translation) {

                    if(!Country::where('id','=', $translation['id'])->first()) continue;
                    CountryTranslation::insert([
                        'country_id' => $translation['id'],
                        'language_id' => $current_language->id,
                        'name' => $translation['name2']
                    ]);
                }

        }
    */
        // Wikipedia Translations
	    \DB::table('country_translations')->delete();
        $seederhelper = new SeederHelper();
        $wikipedia = $seederhelper->csv_to_array(storage_path()."/data/countries/country_translations_wiki.csv");
        foreach($wikipedia as $entry) {
            if(strlen($entry['language_id']) == 2) {
                $current_language = LanguageCode::where('source','Iso 639-2')->where('code','=', $entry['language_id'])->first();
                if(!$current_language) { continue; }
                $current_language = $current_language->language->iso;
            } elseif(strlen($entry['language_id']) == 3) {
                $current_language = Language::where('iso',$entry['language_id'])->first();
                if(!$current_language) { continue; }
                $current_language = $current_language->iso;
            } else {
                dd($entry['language_id']);
            }

            if(CountryTranslation::where(['country_id' => $entry['country_id'], 'language_id' => $current_language, 'name' => $entry['name']])->count() == 0) {
                CountryTranslation::insert(['country_id' => $entry['country_id'], 'language_id' => $current_language, 'name' => $entry['name']]);
            }
        }

    }
}
