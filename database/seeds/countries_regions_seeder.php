<?php

use Illuminate\Database\Seeder;
use App\Models\Country\CountryRegion;
use database\seeds\SeederHelper;
class countries_regions_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederHelper = new SeederHelper();
        $countries = $seederHelper->csv_to_array(storage_path().'/data/countries/country_regions.csv');
        foreach($countries as $country) {
        	$language = \App\Models\Language\Language::where('iso','eng')->first();
        	$countryRegion = new CountryRegion();
        	$countryRegion->country_id = $country['id'];
	        $countryRegion->language_id = $language->id;
	        $countryRegion->name = $country['region'];
	        $countryRegion->save();
        }
    }
}
