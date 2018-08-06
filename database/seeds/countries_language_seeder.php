<?php

use Illuminate\Database\Seeder;
use App\Models\Country\CountryLanguage;
use App\Models\Country\Country;
use database\seeds\SeederHelper;
use App\Models\Language\Language;

class countries_language_seeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @param SeederHelper $seeder_helper
	 *
	 * @return void
	 */
    public function run(SeederHelper $seeder_helper)
    {
    	\DB::connection('dbp')->table('country_language')->delete();
	    $languagePopulationByCountry = json_decode(file_get_contents(storage_path('data/countries/languagePopulationByCountry.json')));
	    foreach($languagePopulationByCountry as $country_id => $languages) {
	    	foreach($languages as $iso => $population) {
			    $currentLanguage = Language::where('iso',$iso)->first();
			    $currentCountry = Country::find($country_id);
			    //if(!$currentCountry) $currentCountry = Country::where('iso_a3',$country_id)->orWhere('fips',$country_id)->first();
			    if($iso == "xxx") {continue;}
			    if(!$currentLanguage) {echo "\n Language Not Found: ". $iso; continue;}
			    if(!$currentCountry) {continue;}
			    $alreadyExists = CountryLanguage::where('language_id',$currentLanguage->id)->where('country_id',$currentCountry)->first();
			    if($alreadyExists) { continue; }
			    CountryLanguage::create(['language_id' => $currentLanguage->id,'country_id' => $currentCountry->id,'population' => $population]);
		    }
	    }

    }

}
