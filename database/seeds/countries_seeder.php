<?php

use Illuminate\Database\Seeder;
use \database\seeds\SeederHelper;
use App\Models\Country\Country;
use App\Models\Country\CountryLanguage;

class countries_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederHelper = new SeederHelper();
        $countries = $seederHelper->csv_to_array(storage_path('/data/countries/countries.csv'));
        foreach($countries as $country) {
            $country['name'] = $country['en_name'];
            unset($country['en_name']);
            Country::insert($country);
        }
    }

}
