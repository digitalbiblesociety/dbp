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
	    $cl = $seeder_helper->csv_to_array(storage_path('/data/countries/country_language.csv'));
	    foreach ($cl as $item) {
	    	$language = Language::where('iso',$item['language_id'])->first();
			CountryLanguage::create(['language_id' => $language->id, 'country_id'  => $item['country_id']]);
	    }

    }
}
