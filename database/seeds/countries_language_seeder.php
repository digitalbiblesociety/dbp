<?php

use Illuminate\Database\Seeder;
use App\Models\Country\CountryLanguage;
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
    	\DB::table('country_language')->delete();

	    $cl = $seeder_helper->csv_to_array(storage_path('/data/countries/country_language.csv'));
	    $saved = array();
	    foreach ($cl as $item) {
	    	$language = Language::where('iso',$item['language_id'])->first();
	    	if(in_array($language->id.$item['country_id'],$saved)) { continue; }
			CountryLanguage::create(['language_id' => $language->id, 'country_id'  => $item['country_id']]);
			$saved[] = $language->id.$item['country_id'];
	    }

	    $languagePopulationByCountry = json_decode(file_get_contents(storage_path('data/countries/languagePopulationByCountry.json')));
	    foreach($languagePopulationByCountry as $country_id => $languages) {
	    	foreach($languages as $iso => $population) {
			    $currentLanguage = Language::where('iso',$iso)->first();
			    if($iso == "xxx") {continue;}
			    if(!$currentLanguage) {echo "\n Language Not Found: ". $iso; continue; }
			    \DB::table('country_language')->where('language_id',$currentLanguage->id)->where('country_id',$country_id)->update(['population' => $population]);
		    }
	    }

    }
}
