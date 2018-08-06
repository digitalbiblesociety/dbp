<?php

use Illuminate\Database\Seeder;
use App\Models\Country\JoshuaProject;
use App\Models\Language\Language;
class countries_joshuaProject_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\DB::connection('dbp')->table('country_joshua_project')->delete();
    	$seederHelper = new \database\seeds\SeederHelper();
    	$countries = $seederHelper->csv_to_array(storage_path('/data/countries/countries_joshuaProject_export.csv'));

    	foreach($countries as $country) {
    		if($country['ROL3OfficialLanguage'] == '') {
    			$language = Language::where('iso','eng')->first();
		    } else {
			    $language = Language::where('iso',$country['ROL3OfficialLanguage'])->first();
			    if(!$language) {
				    echo "\nMissing Language: ".$country['ROL3OfficialLanguage'];
				    continue;
			    }
		    }


		    $currentCountry = \App\Models\Country\Country::where('fips',$country['ROG3'])->first();
		    if(!$currentCountry) {
			    echo "\nMissing Country: ".$country['ROG3'];
			    continue;
		    }
		    echo "\nConverting: ".$country['PercentChristianity']." to ".round($country['PercentChristianity'],2);

			JoshuaProject::create([
				'country_id'              => $currentCountry->id,
				'language_official_iso'   => $language->iso,
				'language_official_name'  => $country['OfficialLang'],
				'population'              => intval($country['PoplPeoples']),
				'population_unreached'    => intval($country['PoplPeoplesLR']),
				'people_groups'           => intval($country['CntPeoples']),
				'people_groups_unreached' => intval($country['CntPeoplesLR']),
				'joshua_project_scale'    => intval($country['JPScaleCtry']),
				'primary_religion'        => $country['ReligionPrimary'],
				'percent_christian'       => round($country['PercentChristianity'],2),
				'resistant_belt'          => ($country['10_40Window'] == 'Y') ? true : false,
				'percent_literate'        => round($country['LiteracyRate'],2),
			]);
	    }
    }
}
