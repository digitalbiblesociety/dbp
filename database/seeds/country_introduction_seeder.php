<?php

use Illuminate\Database\Seeder;

class country_introduction_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederHelper = new \database\seeds\SeederHelper();
        $countries = $seederHelper->csv_to_array(storage_path('data/countries/country_introductions.csv'));

        foreach ($countries as $country) {
        	$currentCountry = \App\Models\Country\Country::where('id',$country['country_id'])->first();
        	if(!$currentCountry) { continue; }
        	if($country['introduction']) {
		        $currentCountry->introduction = $country['introduction'] ?? '';
		        $currentCountry->save();
	        }

        }

    }
}
