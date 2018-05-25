<?php

use Illuminate\Database\Seeder;
use App\Models\Country\FactBook\CountryPeople;
use App\Models\Country\FactBook\CountryEnergy;
use App\Models\Country\FactBook\CountryGeography;
use App\Models\Country\FactBook\CountryTransportation;
use App\Models\Country\FactBook\CountryCommunication;
use App\Models\Country\FactBook\CountryIssues;
use App\Models\Country\FactBook\CountryEconomy;
use App\Models\Country\FactBook\CountryGovernment;
use App\Models\Country\Country;

class countries_factbook_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    \DB::table('country_energy')->delete();
	    \DB::table('country_economy')->delete();
	    \DB::table('country_geography')->delete();
	    \DB::table('country_people')->delete();
	    \DB::table('country_communications')->delete();
	    \DB::table('country_transportation')->delete();

        $countries = glob(storage_path('data/countries/factbook/*.json'));
        foreach($countries as $path) {
        	$countryData = json_decode(file_get_contents($path), true);
	        $countryData['code'] = strtoupper(basename($path,'.json'));
        	$country = Country::find($countryData['code']);
        	if($country) {
        		$this->seedEthnicGroups($countryData);
		        $this->seedEnergy($countryData);
		        $this->seedGeography($countryData);
		        $this->seedCommunications($countryData);
		        $this->seedTransportation($countryData);
		        $this->seedIssues($countryData);
		        $this->seedPeople($countryData);
		        $this->seedGovernment($countryData);
		        $this->seedEconomy($countryData);
		        $this->seedIntroduction($countryData);
		        $this->seedReligions($countryData);
	        }
        }
    }

    public function seedIntroduction($country)
    {
    	$introduction = $country['Introduction']['Background']['text'] ?? false;
    	if($introduction) {
		    $country = Country::where('fips',$country['code'])->first();
		    if($country) {
			    $country->introduction = $introduction;
			    $country->save();
		    }
	    }

    }

    public function seedEthnicGroups($country)
    {
    	$ethnicGroups = $country['People and Society']['Ethnic groups'] ?? false;
    	if(isset($ethnicGroups)) {
		    $ethnicGroups = $ethnicGroups['text'];
    	    $ethnicGroups = explode(', ', $ethnicGroups);
    	    foreach ($ethnicGroups as $ethnic_group) {
			    preg_match("/(.*) (\d+.*?)\%/", $ethnic_group, $ethnicity);
			    if(isset($ethnicity[2])) {
				    \App\Models\Country\FactBook\CountryEthnicity::create([
					    'country_id'                    => $country['code'],
					    'name'                          => $ethnicity[1],
					    'population_percentage'         => $ethnicity[2],
				    ]);
			    }
	        }
		}
    }

    public function seedReligions($country) {
    	$religions = $country['People and Society']['Religions'] ?? false;
    	if($religions) {
		    $religions = explode(', ', $religions['text']);
		    foreach ($religions as $religion) {
			    preg_match("/(.*) (\d+.*?)\%/", $religion, $religiousGroup);

				\App\Models\Country\FactBook\CountryReligion::create([
				    'country_id'                    => $country['code'],
				    'name'                          => $religiousGroup[1] ?? $religion,
				    'population_percentage'         => (isset($religiousGroup[2])) ? intval($religiousGroup[2]) : null,
				]);
		    }
	    }
    }


	/**
	 * @param $country
	 */
	public function seedGeography($country)
	{
		$geography = $country['Geography'];
		$latlongs = json_decode(file_get_contents(storage_path('/data/countries/countries_latLong.json')), true);
		$latlongs = $latlongs[$country['code']] ?? [];
		$lng = $latlongs['lng'] ?? NULL;
		$lat = $latlongs['lat'] ?? NULL;

		// Need special accommodation for France
		if($country['code'] == "FR") {
			$geography["Location"] = $geography["Location"]["metropolitan France"];
			$geography["Geographic coordinates"] = $geography["Geographic coordinates"]["metropolitan France"];
			$geography["Map references"] = $geography["Map references"]["metropolitan France"];
			$geography["Climate"] = $geography["Climate"]['metropolitan France'];
		}

		// Error Checking and empty defaults
		if(isset($geography["Location"])) {
			if(!$geography["Location"]["text"]) $geography["Location"]["text"] = "";
		} else {
			$geography["Location"]["text"] = "";
		}
		if(isset($geography["Map references"])) {
			if(!isset($geography["Map references"]["text"])) $geography["Map references"]["text"] = "";
		} else {
			$geography["Map references"] = "";
		}
		if(isset($geography["Area"])) {
			if(!isset($geography["Area"]["land"]["text"])) $geography["Area"]["land"]["text"] = "";
			if(!isset($geography["Area"]["total"]["text"])) $geography["Area"]["total"]["text"] = "";
			if(!isset($geography["Area"]["water"]["text"])) $geography["Area"]["water"]["text"] = "";
		}
		if(isset($geography["Coastline"])) {
			if(!isset($geography["Coastline"]["text"])) $geography["Coastline"]["text"] = "";
		} else {
			$geography["Coastline"]["text"] = "";
		}
		if(isset($geography["Area - comparative"])) {
			if(!isset($geography["Area - comparative"]["text"])) $geography["Area - comparative"]["text"] = "";
		} else {
			$geography["Area - comparative"]["text"] = "";
		}
		if(isset($geography["Climate"])) {
			if(!isset($geography["Climate"]["text"])) $geography["Climate"]["text"] = "";
		} else {
			$geography["Climate"]["text"] = "";
		}
		if(isset($geography["Terrain"])) {
			if(!isset($geography["Terrain"]["text"])) $geography["Terrain"]["text"] = "";
		} else {
			$geography["Terrain"]["text"] = "";
		}
		if(isset($geography["Natural hazards"])) {
			if(!isset($geography["Natural hazards"]["text"])) $geography["Natural hazards"]["text"] = "";
		} else {
			$geography["Natural hazards"]["text"] = "";
		}
		if(isset($geography["Geography - note"])) {
			if(!isset($geography["Geography - note"]["text"])) $geography["Geography - note"]["text"] = "";
		} else {
			$geography["Geography - note"]["text"] = "";
		}

		CountryGeography::create([
			'country_id'           => $country['code'],
			'location_description' => $geography["Location"]["text"],
			'latitude'             => $lat ?? null,
			'longitude'            => $lng ?? null,
			'mapReferences'        => $geography["Map references"]["text"],
			'area_sqkm_total'      => intval($geography["Area"]["total"]["text"]),
			'area_sqkm_land'       => intval($geography["Area"]["land"]["text"]),
			'area_sqkm_water'      => intval($geography["Area"]["water"]["text"]),
			'area_km_coastline'    => intval($geography['Coastline']["text"]),
			'area_note'            => $geography["Area - comparative"]["text"],
			'climate'              => $geography["Climate"]["text"],
			'terrain'              => $geography["Terrain"]["text"],
			'hazards'              => $geography["Natural hazards"]["text"],
			'notes'                => $geography["Geography - note"]["text"]
		]);
	}

	public function seedPeople($country)
	{
		if(!isset($country["People and Society"])) return false;
		$people = $country["People and Society"];

		// Extract Date
		if(isset($people['Population'])) {
			preg_match("/(.*) \(.* (\d+).*?\)/", $people['Population']['text'], $population);
			preg_match("/(.*) \(.* (\d+).*?\)/", $people['Population']['text'], $population);
		}

		if(isset($country['Median age'])) {
			preg_match("/(.*) \(.* (\d+).*?\)/", $people['Median age']['total']['text'], $median_age_total);
			preg_match("/(.*) \(.* (\d+).*?\)/", $people['Median age']['male']['text'], $median_age_male);
			preg_match("/(.*) \(.* (\d+).*?\)/", $people['Median age']['female']['text'], $median_age_female);
		}

		// Extract Percentage
		$extract_percentage = "/(.*)% \(.*?\)/";
		if(isset($people['Age structure'])) {
			preg_match( $extract_percentage, $people['Age structure']['0-14 years']['text'], $age_structure_0 );
			preg_match( $extract_percentage, $people['Age structure']['15-24 years']['text'], $age_structure_15 );
			preg_match( $extract_percentage, $people['Age structure']['25-54 years']['text'], $age_structure_25 );
			preg_match( $extract_percentage, $people['Age structure']['55-64 years']['text'], $age_structure_55 );
			preg_match( $extract_percentage, $people['Age structure']['65 years and over']['text'], $age_structure_65 );
		}

		if(isset($people['Contraceptive prevalence rate']['text'])) {
			preg_match( $extract_percentage, $people['Contraceptive prevalence rate']['text'], $contraceptive_use);
		}
		if(isset($people['Health expenditures']['text'])) {
			preg_match( $extract_percentage, $people['Health expenditures']['text'], $health_expenditures);
		}
		if(isset($people['Dependency ratios'])) {
			preg_match($extract_percentage, $people['Dependency ratios']['total dependency ratio']['text'],   $dependency_total);
			preg_match($extract_percentage, $people['Dependency ratios']['youth dependency ratio']['text'],   $dependency_youth);
			preg_match($extract_percentage, $people['Dependency ratios']['elderly dependency ratio']['text'], $dependency_elderly);
			preg_match($extract_percentage, $people['Dependency ratios']['potential support ratio']['text'],  $dependency_potential);
		}
		if(isset($people['Population growth rate'])) {
			preg_match($extract_percentage, $people['Population growth rate']['text'],  $population_growth_rate);
		}

		if(isset($people['HIV/AIDS - adult prevalence rate']['text'])) {
			preg_match($extract_percentage, $people['HIV/AIDS - adult prevalence rate']['text'], $hiv_prevalence);
		}
		if(isset($people['HIV/AIDS - people living with HIV/AIDS']['text'])) {
			preg_match($extract_percentage, $people['HIV/AIDS - people living with HIV/AIDS']['text'], $hiv_living);
		}
		if(isset($people['HIV/AIDS - deaths']['text'])) {
			preg_match($extract_percentage, $people['HIV/AIDS - deaths']['text'], $hiv_deaths);
		}

		// Extact Birth / death rate
		$extract_population_ratio = "/(.*) .*\/1,000 population/";
		if(isset($people['Birth rate'])) {
			preg_match($extract_population_ratio, $people['Birth rate']['text'], $birth_rate);
			preg_match($extract_population_ratio, $people['Death rate']['text'], $death_rate);
			$birth_rate = (floatval($birth_rate[1]) / 1000);
			$death_rate = (floatval($death_rate[1]) / 1000);
		}
		if(isset($people['Net migration rate'])) {
			preg_match($extract_population_ratio, $people['Net migration rate']['text'], $migration_rate);
			$migration_rate = isset($migration_rate[1]) ? (floatval($migration_rate[1]) / 1000) : 0;
		}

		// Sex Ratio
		$extract_sex_ratio = "/(.*) male/";
		if(isset($people['Sex ratio']['at birth'])) {preg_match($extract_sex_ratio, $people['Sex ratio']['at birth']['text'], $sex_ratio_birth);} else {$sex_ratio_birth[1] = 0;}
		if(isset($people['Sex ratio']['0-14 years'])) {preg_match($extract_sex_ratio, $people['Sex ratio']['0-14 years']['text'], $sex_ratio_0yr);} else {$sex_ratio_0yr[1] = 0;}
		if(isset($people['Sex ratio']['15-24 years'])) {preg_match($extract_sex_ratio, $people['Sex ratio']['15-24 years']['text'], $sex_ratio_15yr);} else {$sex_ratio_15yr[1] = 0;}
		if(isset($people['Sex ratio']['25-54 years'])) {preg_match($extract_sex_ratio, $people['Sex ratio']['25-54 years']['text'], $sex_ratio_25yr);} else {$sex_ratio_25yr[1] = 0;}
		if(isset($people['Sex ratio']['55-64 years'])) {preg_match($extract_sex_ratio, $people['Sex ratio']['55-64 years']['text'], $sex_ratio_55yr);} else {$sex_ratio_55yr[1] = 0;}
		if(isset($people['Sex ratio']['65 years and over'])) {preg_match($extract_sex_ratio, $people['Sex ratio']['65 years and over']['text'], $sex_ratio_65yr);} else {$sex_ratio_65yr[1] = 0;}
		if(isset($people['Sex ratio']['total population'])) {preg_match($extract_sex_ratio, $people['Sex ratio']['total population']['text'], $sex_ratio_total);} else {$sex_ratio_total[1] = 0;}

		//
		$mortality_rate = "/(.*) deaths/";
		if(isset($people['Infant mortality rate'])) {
			preg_match($mortality_rate, $people['Infant mortality rate']['total']['text'],  $infant_mortality_rate_total);
			preg_match($mortality_rate, $people['Infant mortality rate']['male']['text'],   $infant_mortality_rate_male);
			preg_match($mortality_rate, $people['Infant mortality rate']['female']['text'], $infant_mortality_rate_female);
			$infant_mortality_rate_total = isset($infant_mortality_rate_total[1]) ? ($infant_mortality_rate_total[1] / 1000) : 0;
			$infant_mortality_rate_male = isset($infant_mortality_rate_male[1]) ? ($infant_mortality_rate_male[1] / 1000) : 0;
			$infant_mortality_rate_female = isset($infant_mortality_rate_female[1]) ? ($infant_mortality_rate_female[1] / 1000) : 0;
		}

		if(isset($people['Maternal mortality rate'])) {
			preg_match($mortality_rate, $people['Maternal mortality rate']['text'],  $mother_mortality_rate);
			$mother_mortality_rate = (isset($mother_mortality_rate[1]) AND is_int($mother_mortality_rate[1])) ? ($mother_mortality_rate[1] / 1000) : 0;
		}

		$improvements_regex = "/\+\+ urban: (.*?)% of population \+\+ rural: (.*?)% of population/";
		if(isset($people['Drinking water source']['improved'])) {
			preg_match($improvements_regex, $people['Drinking water source']['improved']['text'], $water_improvements);
		}
		if(isset($people['Sanitation facility access']['improved'])) {
			preg_match($improvements_regex, $people['Sanitation facility access']['improved']['text'], $sanitation_improvements);
		}


		CountryPeople::create([
			'country_id'                                    => $country['code'],
			'languages'                                     => $country['Languages']['text'] ?? "",
			'religions'                                     => $country['Religions']['text'] ?? "",
			'population'                                    => isset($population[1]) ? intval($population[1]) : null,
			'population_date'                               => isset($population[2]) ? intval($population[2]) : null,
			'nationality_noun'                              => isset($people['Nationality']['noun']['text']) ? $people['Nationality']['noun']['text'] : null,
			'nationality_adjective'                         => isset($people['Nationality']['adjective']['text']) ? $people['Nationality']['adjective']['text'] : null,
			'age_structure_14'                              => isset($age_structure_0[1]) ?  $age_structure_0[1] : null,
			'age_structure_24'                              => isset($age_structure_15[1]) ? $age_structure_15[1] : null,
			'age_structure_54'                              => isset($age_structure_25[1]) ? $age_structure_25[1] : null,
			'age_structure_64'                              => isset($age_structure_55[1]) ? $age_structure_55[1] : null,
			'age_structure_65'                              => isset($age_structure_65[1]) ? $age_structure_65[1] : null,
			'dependency_total'                              => isset($dependency_total[1]) ? $dependency_total[1] : null,
			'dependency_youth'                              => isset($dependency_youth[1]) ? $dependency_youth[1] : null,
			'dependency_elder'                              => isset($dependency_elderly[1]) ? $dependency_elderly[1] : null,
			'dependency_potential'                          => isset($dependency_potential[1]) ? $dependency_potential[1] : null,
			'median_age_total'                              => isset($median_age_total[1]) ? $median_age_total[1] : null,
			'median_age_male'                               => isset($median_age_male[1]) ? $median_age_male[1] : null,
			'median_age_female'                             => isset($median_age_female[1]) ? $median_age_female[1] : null,
			'population_growth_rate_percentage'             => isset($population_growth_rate[1]) ? $population_growth_rate[1] : null,
			'birth_rate_per_1k'                             => $birth_rate ?? null,
			'death_rate_per_1k'                             => $death_rate ?? null,
			'net_migration_per_1k'                          => $migration_rate ?? null,
			'population_distribution'                       => isset($people['Population distribution']) ? $people['Population distribution']['text'] : "",
//			'urban_population_percentage'                   => isset($people['Urbanization']['urban population']['text']) ? floatval($people['Urbanization']['urban population']['text']) : null,
//			'urbanization_rate'                             => isset($people['Urbanization']['rate of urbanization']['text']) ? floatval($people['Urbanization']['rate of urbanization']['text']) : null,
//			'major_urban_areas_population'                  => isset($people['Major urban areas - population']) ? $people['Major urban areas - population']['text'] : null,
			'sex_ratio_birth'                               => isset($sex_ratio_birth[1]) ? floatval($sex_ratio_birth[1]) : null,
			'sex_ratio_14'                                  => isset($sex_ratio_0yr[1]) ? floatval($sex_ratio_0yr[1]) : null,
			'sex_ratio_24'                                  => isset($sex_ratio_15yr[1]) ? floatval($sex_ratio_15yr[1]) : null,
			'sex_ratio_54'                                  => isset($sex_ratio_25yr[1]) ? floatval($sex_ratio_25yr[1]) : null,
			'sex_ratio_64'                                  => isset($sex_ratio_55yr[1]) ? floatval($sex_ratio_55yr[1]) : null,
			'sex_ratio_65'                                  => isset($sex_ratio_65yr[1]) ? floatval($sex_ratio_65yr[1]) : null,
			'sex_ratio_total'                               => isset($sex_ratio_total[1]) ? floatval($sex_ratio_total[1]) : null,
			'mother_age_first_birth'                        => isset($people['Mother\'s mean age at first birth']) ? substr($people['Mother\'s mean age at first birth']['text'],0,2) : null,
			'maternal_mortality_rate'                       => $mother_mortality_rate[1] ?? null,
			'infant_mortality_per_1k_total'                 => $infant_mortality_rate_total ?? null,
			'infant_mortality_per_1k_male'                  => $infant_mortality_rate_male ?? null,
			'infant_mortality_per_1k_female'                => $infant_mortality_rate_female ?? null,
			'life_expectancy_at_birth_total'                => isset($people['Life expectancy at birth']['total population']['text']) ? floatval($people['Life expectancy at birth']['total population']['text']) : null,
			'life_expectancy_at_birth_male'                 => isset($people['Life expectancy at birth']['male']['text']) ? floatval($people['Life expectancy at birth']['male']['text']) : null,
			'life_expectancy_at_birth_female'               => isset($people['Life expectancy at birth']['female']['text']) ? floatval($people['Life expectancy at birth']['female']['text']) : null,
			'total_fertility_rate'                          => isset($people['Total fertility rate']['text']) ? floatval($people['Total fertility rate']['text']) : null,
			'contraceptive_prevalence'                      => $contraceptive_use[1] ?? 0,
			'health_expenditures'                           => $health_expenditures[1] ?? 0,
			'physicians'                                    => isset($people['Physicians density']) ? floatval($people['Physicians density']['text']) : null,
			'hospital_beds'                                 => isset($people['Hospital bed density']) ? floatval($people['Hospital bed density']['text']) : null,
			'drinking_water_source_urban_improved'          => isset($water_improvements[1]) ? floatval($water_improvements[1]) : null,
			'drinking_water_source_rural_improved'          => isset($water_improvements[2]) ? floatval($water_improvements[2]) : null,
			'sanitation_facility_access_urban_improved'     => isset($sanitation_improvements[1]) ? floatval($sanitation_improvements[1]) : null,
			'sanitation_facility_access_rural_improved'     => isset($sanitation_improvements[2]) ? floatval($sanitation_improvements[2]) : null,
			'hiv_infection_rate'                            => isset($hiv_prevalence[1]) ? $hiv_prevalence[1] : null,
			'hiv_infected'                                  => isset($hiv_living[1]) ? $hiv_living[1] : null,
			'hiv_deaths'                                    => isset($hiv_deaths[1]) ? $hiv_deaths[1] : null,
			'obesity_rate'                                  => isset($people['Obesity - adult prevalence rate']) ? floatval($people['Obesity - adult prevalence rate']['text']) : null,
			'underweight_children'                          => isset($people['Children under the age of 5 years underweight']) ? floatval($people['Children under the age of 5 years underweight']['text']) : null,
			'education_expenditures'                        => isset($people['Education expenditures']) ? floatval($people['Education expenditures']['text']) : null,
			'literacy_definition'                           => isset($people['Literacy']['definition']) ? $people['Literacy']['definition']['text'] : null,
			'literacy_total'                                => isset($people['Literacy']['total population']) ? floatval($people['Literacy']['total population']['text']) : null,
			'literacy_male'                                 => isset($people['Literacy']['male']) ? floatval($people['Literacy']['male']['text']) : null,
			'literacy_female'                               => isset($people['Literacy']['female']) ? floatval($people['Literacy']['female']['text']) : null,
			'school_years_total'                            => isset($people['School life expectancy (primary to tertiary education)']['total']) ? intval($people['School life expectancy (primary to tertiary education)']['total']['text']) : null,
			'school_years_male'                             => isset($people['School life expectancy (primary to tertiary education)']['male']) ? intval($people['School life expectancy (primary to tertiary education)']['male']['text']) : null,
			'school_years_female'                           => isset($people['School life expectancy (primary to tertiary education)']['female']) ? intval($people['School life expectancy (primary to tertiary education)']['female']['text']) : null,
			'child_labor'                                   => isset($people['Child labor - children ages 5-14']['total number']) ? intval($people['Child labor - children ages 5-14']['total number']['text']) : null,
			'child_labor_percentage'                        => isset($people['Child labor - children ages 5-14']['percentage']) ? floatval($people['Child labor - children ages 5-14']['percentage']['text']) : null,
			'unemployment_youth_total'                      => isset($people['Unemployment, youth ages 15-24']['total']) ? floatval($people['Unemployment, youth ages 15-24']['total']['text']) : null,
			'unemployment_youth_male'                       => isset($people['Unemployment, youth ages 15-24']['male']) ? floatval($people['Unemployment, youth ages 15-24']['male']['text']) : null,
			'unemployment_youth_female'                     => isset($people['Unemployment, youth ages 15-24']['female']) ? floatval($people['Unemployment, youth ages 15-24']['female']['text']) : null,
		]);

	}
	public function seedGovernment($country)
	{
		if(!isset($country["Government"])) return false;
		$government = $country["Government"];

		CountryGovernment::create([
			'country_id'                                => $country['code'],
			'name'                                      => isset($government["Country name"]["conventional long form"]['text']) ? $government["Country name"]["conventional long form"]['text'] : "",
			'name_etymology'                            => isset($government["Country name"]["etymology"]['text']) ? $government["Country name"]["etymology"]['text'] : "",
			'conventional_long_form'                    => isset($government["Country name"]["conventional long form"]['text']) ? $government["Country name"]["conventional long form"]['text'] : "",
			'conventional_short_form'                   => isset($government["Country name"]["conventional short form"]['text']) ? $government["Country name"]["conventional short form"]['text'] : "",
			'dependency_status'                         => isset($government["Dependency status"]['text']) ? $government["Dependency status"]['text'] : "",
			'government_type'                           => isset($government["Government type"]['text']) ? $government["Government type"]['text'] : "",
			'capital'                                   => isset($government["Capital"]["name"]['text']) ? $government["Capital"]["name"]['text'] : "",
			'capital_coordinates'                       => isset($government["Capital"]["geographic coordinates"]['text']) ? $government["Capital"]["geographic coordinates"]['text'] : "",
			'capital_time_zone'                         => isset($government["Capital"]["time difference"]['text']) ? $government["Capital"]["time difference"]['text'] : "",
			'administrative_divisions'                  => isset($government["Administrative divisions"]['text']) ? $government["Administrative divisions"]['text'] : "",
			'administrative_divisions_note'             => isset($government["Administrative divisions"]["note"]['text']) ? $government["Administrative divisions"]["note"]['text'] : "",
			'independence'                              => isset($government["Independence"]['text']) ? $government["Independence"]['text'] : "",
			'national_holiday'                          => isset($government["National holiday"]['text']) ? $government["National holiday"]['text'] : "",
			'constitution'                              => isset($government["Constitution"]['text']) ? $government["Constitution"]['text'] : "",
			'legal_system'                              => isset($government["Legal system"]['text']) ? $government["Legal system"]['text'] : "",
			'citizenship'                               => isset($government["Citizenship"]['text']) ? $government["Citizenship"]['text'] : "",
			'suffrage'                                  => isset($government["Suffrage"]['text']) ? $government["Suffrage"]['text'] : "",
			'executive_chief_of_state'                  => isset($government["Executive branch"]["chief of state"]['text']) ? $government["Executive branch"]["chief of state"]['text'] : "",
			'executive_head_of_government'              => isset($government["Executive branch"]["head of government"]['text']) ? $government["Executive branch"]["head of government"]['text'] : "",
			'executive_cabinet'                         => isset($government["Executive branch"]["cabinet"]['text']) ? $government["Executive branch"]["cabinet"]['text'] : "",
			'executive_elections'                       => isset($government["Executive branch"]["elections/appointments"]['text']) ? $government["Executive branch"]["elections/appointments"]['text'] : "",
			'executive_election_results'                => isset($government["Executive branch"]["election results"]['text']) ? $government["Executive branch"]["election results"]['text'] : "",
			'legislative_description'                   => isset($government["Legislative branch"]["description"]['text']) ? $government["Legislative branch"]["description"]['text'] : "",
			'legislative_elections'                     => isset($government["Legislative branch"]["elections"]['text']) ? $government["Legislative branch"]["elections"]['text'] : "",
			'legislative_election_results'              => isset($government["Legislative branch"]["election results"]['text']) ? $government["Legislative branch"]["election results"]['text'] : "",
			'legislative_highest_courts'                => isset($government["Judicial branch"]["highest court(s)"]['text']) ? $government["Judicial branch"]["highest court(s)"]['text'] : "",
			'legislative_judge_selection'               => isset($government["Judicial branch"]["judge selection and term of office"]['text']) ? $government["Judicial branch"]["judge selection and term of office"]['text'] : "",
			'legislative_subordinate_courts'            => isset($government["Judicial branch"]["subordinate courts"]['text']) ? $government["Judicial branch"]["subordinate courts"]['text'] : "",
			'political_parties'                         => isset($government["Political parties and leaders"]['text']) ? $government["Political parties and leaders"]['text'] : "",
			'political_pressure'                        => isset($government["Political parties and leaders"]["other"]['text']) ? $government["Political parties and leaders"]["other"]['text'] : "",
			'international_organization_participation'  => isset($government["International organization participation"]['text']) ? $government["International organization participation"]['text'] : "",
			'diplomatic_representation_in_usa'          => isset($government["Diplomatic representation in the US"]['text']) ? $government["Diplomatic representation in the US"]['text'] : "",
			'diplomatic_representation_from_usa'        => isset($government["Diplomatic representation from the US"]['text']) ? $government["Diplomatic representation from the US"]['text'] : "",
			'flag_description'                          => isset($government["Flag description"]['text']) ? $government["Flag description"]['text'] : "",
			'national_symbols'                          => isset($government["National symbol(s)"]['text']) ? $government["National symbol(s)"]['text'] : "",
			'national_anthem'                           => isset($government["National anthem"]['text']) ? $government["National anthem"]['text'] : "",
		]);
	}

	public function seedEconomy($country)
	{
		if(!isset($country['Economy'])) return false;
		$economic = $country['Economy'];

		CountryEconomy::create([
			'country_id'                          => $country['code'],
			'overview'                            => isset($economic["Economy - overview"]['text']) ? $economic["Economy - overview"]['text'] : "",
			'gdp_power_parity'                    => isset($economic["GDP (purchasing power parity)"]['text']) ? $economic["GDP (purchasing power parity)"]['text'] : "",
			'gdp_real_growth'                     => isset($economic["GDP - real growth rate"]['text']) ? $economic["GDP - real growth rate"]['text'] : "",
			'gdp_per_capita'                      => isset($economic["GDP - per capita (PPP)"]['text']) ? $economic["GDP - per capita (PPP)"]['text'] : "",
			'gdp_household_consumption'           => isset($economic["GDP - composition, by end use"]["household consumption"]['text']) ? $economic["GDP - composition, by end use"]["household consumption"]['text'] : "",
			'gdp_consumption'                     => isset($economic["GDP - composition, by end use"]["government consumption"]['text']) ? $economic["GDP - composition, by end use"]["government consumption"]['text'] : "",
			'gdp_investment_in_fixed_capital'     => isset($economic["GDP - composition, by end use"]["investment in fixed capital"]['text']) ? $economic["GDP - composition, by end use"]["investment in fixed capital"]['text'] : "",
			'gdp_investment_in_inventories'       => isset($economic["GDP - composition, by end use"]["investment in inventories"]['text']) ? $economic["GDP - composition, by end use"]["investment in inventories"]['text'] : "",
			'gdp_exports'                         => isset($economic["GDP - composition, by end use"]["exports of goods and services"]['text']) ? $economic["GDP - composition, by end use"]["exports of goods and services"]['text'] : "",
			'gdp_imports'                         => isset($economic["GDP - composition, by end use"]["imports of goods and services"]['text']) ? $economic["GDP - composition, by end use"]["imports of goods and services"]['text'] : "",
			'gdp_sector_agriculture'              => isset($economic["GDP - composition, by sector of origin"]["agriculture"]['text']) ? $economic["GDP - composition, by sector of origin"]["agriculture"]['text'] : "",
			'gdp_sector_industry'                 => isset($economic["GDP - composition, by sector of origin"]["industry"]['text']) ? $economic["GDP - composition, by sector of origin"]["industry"]['text'] : "",
			'gdp_sector_services'                 => isset($economic["GDP - composition, by sector of origin"]["services"]['text']) ? $economic["GDP - composition, by sector of origin"]["services"]['text'] : "",
			'agriculture_products'                => isset($economic["Agriculture - products"]['text']) ? $economic["Agriculture - products"]['text'] : "",
			'industries'                          => isset($economic["Industries"]['text']) ? $economic["Industries"]['text'] : "",
			'industrial_growth_rate'              => isset($economic["Industrial production growth rate"]['text']) ? $economic["Industrial production growth rate"]['text'] : "",
			'labor_force'                         => isset($economic["Labor force"]['text']) ? $economic["Labor force"]['text'] : "",
			'labor_force_notes'                   => isset($economic["Labor force"]["note"]['text']) ? $economic["Labor force"]["note"]['text'] : "",
			'labor_force_services'                => isset($economic["Labor force - by occupation"]["services"]['text']) ? $economic["Labor force - by occupation"]["services"]['text'] : "",
			'labor_force_industry'                => isset($economic["Labor force - by occupation"]["industry"]['text']) ? $economic["Labor force - by occupation"]["industry"]['text'] : "",
			'labor_force_agriculture'             => isset($economic["Labor force - by occupation"]["agriculture"]['text']) ? $economic["Labor force - by occupation"]["agriculture"]['text'] : "",
			'labor_force_occupation_notes'        => isset($economic["Labor force - by occupation"]["note"]['text']) ? $economic["Labor force - by occupation"]["note"]['text'] : "",
			'unemployment_rate'                   => isset($economic["Unemployment rate"]['text']) ? $economic["Unemployment rate"]['text'] : "",
			'population_below_poverty'            => isset($economic["Population below poverty line"]['text']) ? $economic["Population below poverty line"]['text'] : "",
			'household_income_lowest_10'          => isset($economic["Household income or consumption by percentage share"]["lowest 10%"]['text']) ? $economic["Household income or consumption by percentage share"]["lowest 10%"]['text'] : "",
			'household_income_highest_10'         => isset($economic["Household income or consumption by percentage share"]["highest 10%"]['text']) ? $economic["Household income or consumption by percentage share"]["highest 10%"]['text'] : "",
			'budget_revenues'                     => isset($economic["Budget"]['text']) ? $economic["Budget"]['text'] : "",
			'taxes_revenues'                      => isset($economic["Taxes and other revenues"]['text']) ? $economic["Taxes and other revenues"]['text'] : "",
			'budget_net'                          => isset($economic["Budget surplus (+) or deficit (-)"]['text']) ? $economic["Budget surplus (+) or deficit (-)"]['text'] : "",
			'public_debt'                         => isset($economic["Public debt"]['text']) ? $economic["Public debt"]['text'] : "",
			'external_debt'                       => isset($economic["Debt - external"]['text']) ? $economic["Debt - external"]['text'] : "",
			'fiscal_year'                         => isset($economic["Fiscal year"]['text']) ? $economic["Fiscal year"]['text'] : "",
			'inflation_rate'                      => isset($economic["Inflation rate (consumer prices)"]['text']) ? $economic["Inflation rate (consumer prices)"]['text'] : "",
			'central_bank_discount_rate'          => isset($economic["Central bank discount rate"]['text']) ? $economic["Central bank discount rate"]['text'] : "",
			'commercial_bank_prime_lending_rate'  => isset($economic["Commercial bank prime lending rate"]['text']) ? $economic["Commercial bank prime lending rate"]['text'] : "",
			'stock_money_narrow'                  => isset($economic["Stock of narrow money"]['text']) ? $economic["Stock of narrow money"]['text'] : "",
			'stock_money_broad'                   => isset($economic["Stock of broad money"]['text']) ? $economic["Stock of broad money"]['text'] : "",
			'stock_domestic_credit'               => isset($economic["Stock of domestic credit"]['text']) ? $economic["Stock of domestic credit"]['text'] : "",
			'exports'                             => isset($economic["Exports"]['text']) ? $economic["Exports"]['text'] : "",
			'exports_commodities'                 => isset($economic["Exports - commodities"]['text']) ? $economic["Exports - commodities"]['text'] : "",
			'exports_partners'                    => isset($economic["Exports - partners"]['text']) ? $economic["Exports - partners"]['text'] : "",
			'imports'                             => isset($economic["Imports"]['text']) ? $economic["Imports"]['text'] : "",
			'imports_commodities'                 => isset($economic["Imports - commodities"]['text']) ? $economic["Imports - commodities"]['text'] : "",
			'imports_partners'                    => isset($economic["Imports - partners"]['text']) ? $economic["Imports - partners"]['text'] : "",
			'exchange_rates'                      => isset($economic["Exchange rates"]['text']) ? $economic["Exchange rates"]['text'] : "",
		]);
	}

	public function seedEnergy($country)
	{
		if(!isset($country['Energy'])) return false;
		$energy = $country['Energy'];

		CountryEnergy::create([
			'country_id'                      => $country['code'],
			'electricity_production'          => isset($energy['Electricity - production']) ? $energy['Electricity - production']['text'] : "",
			'electricity_consumption'         => isset($energy['Electricity - consumption']) ? $energy['Electricity - consumption']['text'] : "",
			'electricity_exports'             => isset($energy['Electricity - exports']) ? $energy['Electricity - exports']['text'] : "",
			'electricity_imports'             => isset($energy['Electricity - imports']) ? $energy['Electricity - imports']['text'] : "",
			'electricity_generating_capacity' => isset($energy['Electricity - installed generating capacity']) ? $energy['Electricity - installed generating capacity']['text'] : "",
			'electricity_fossil_fuels'        => isset($energy['Electricity - from fossil fuels']) ? $energy['Electricity - from fossil fuels']['text'] : "",
			'electricity_nuclear'             => isset($energy['Electricity - from nuclear fuels']) ? $energy['Electricity - from nuclear fuels']['text'] : "",
			'electricity_hydroelectric'       => isset($energy['Electricity - from hydroelectric plants']) ? $energy['Electricity - from hydroelectric plants']['text'] : "",
			'electricity_renewable'           => isset($energy['Electricity - from other renewable sources']) ? $energy['Electricity - from other renewable sources']['text'] : "",
			'crude_oil_production'            => isset($energy['Crude oil - production']) ? $energy['Crude oil - production']['text'] : "",
			'crude_oil_exports'               => isset($energy['Crude oil - exports']) ? $energy['Crude oil - exports']['text'] : "",
			'crude_oil_imports'               => isset($energy['Crude oil - imports']) ? $energy['Crude oil - imports']['text'] : "",
			'crude_oil_reserves'              => isset($energy['Crude oil - proved reserves']) ? $energy['Crude oil - proved reserves']['text'] : "",
			'petrol_production'               => isset($energy['Refined petroleum products - production']) ? $energy['Refined petroleum products - production']['text'] : "",
			'petrol_consumption'              => isset($energy['Refined petroleum products - consumption']) ? $energy['Refined petroleum products - consumption']['text'] : "",
			'petrol_exports'                  => isset($energy['Refined petroleum products - exports']) ? $energy['Refined petroleum products - exports']['text'] : "",
			'petrol_imports'                  => isset($energy['Refined petroleum products - imports']) ? $energy['Refined petroleum products - imports']['text'] : "",
			'natural_gas_production'          => isset($energy['Natural gas - production']) ? $energy['Natural gas - production']['text'] : "",
			'natural_gas_consumption'         => isset($energy['Natural gas - consumption']) ? $energy['Natural gas - consumption']['text'] : "",
			'natural_gas_exports'             => isset($energy['Natural gas - exports']) ? $energy['Natural gas - exports']['text'] : "",
			'natural_gas_imports'             => isset($energy['Natural gas - imports']) ? $energy['Natural gas - imports']['text'] : "",
			'natural_gas_reserves'            => isset($energy['Natural gas - proved reserves']) ? $energy['Natural gas - proved reserves']['text'] : "",
			'co2_output'                      => isset($energy['Carbon dioxide emissions from consumption of energy']) ? $energy['Carbon dioxide emissions from consumption of energy']['text'] : "",
		]);
	}
	public function seedCommunications($country)
	{
		if(!isset($country['Communications'])) return false;
		$communications = $country['Communications'];
		$populationPercentage = isset($communications["Internet users"]["percent of population"]) ? str_replace('%','',substr($communications["Internet users"]["percent of population"]["text"],0,3)) : 0.00;

		CountryCommunication::create([
			'country_id'                      => $country['code'],
			'fixed_phones_total'              => isset($communications["Telephones - fixed lines"]["total subscriptions"]) ? $communications["Telephones - fixed lines"]["total subscriptions"]["text"] : "",
			'fixed_phones_subs_per_100'       => isset($communications["Telephones - fixed lines"]["subscriptions per 100 inhabitants"]) ? $communications["Telephones - fixed lines"]["subscriptions per 100 inhabitants"]["text"] : "",
			'mobile_phones_total'             => isset($communications["Telephones - mobile cellular"]["total"]) ? $communications["Telephones - mobile cellular"]["total"]["text"] : "",
			'mobile_phones_subs_per_100'      => isset($communications["Telephones - mobile cellular"]["subscriptions per 100 inhabitants"]) ? $communications["Telephones - mobile cellular"]["subscriptions per 100 inhabitants"]["text"] : "",
			'phone_system_general_assessment' => isset($communications["Telephone system"]["general assessment"]) ? $communications["Telephone system"]["general assessment"]["text"] : "",
			'phone_system_international'      => isset($communications["Telephone system"]["international"]) ? $communications["Telephone system"]["international"]["text"] : "",
			'phone_system_domestic'           => isset($communications["Telephone system"]["domestic"]) ? $communications["Telephone system"]["domestic"]["text"] : "",
			'broadcast_media'                 => isset($communications["Broadcast media"]) ? $communications["Broadcast media"]["text"] : "",
			'internet_country_code'           => isset($communications["Internet country code"]) ? substr(trim($communications["Internet country code"]["text"], "."),0,2) : "",
			'internet_total_users'            => isset($communications["Internet users"]["total"]) ? $communications["Internet users"]["total"]["text"] : "",
			'internet_population_percent'     => $populationPercentage,
		]);
	}
	public function seedTransportation($country)
	{
		if(!isset($country['Transportation'])) return false;
		$transportation = $country["Transportation"];

		CountryTransportation::create([
			'country_id'           => $country['code'],
			'air_carriers'         => isset($transportation["National air transport system"]["number of registered air carriers"]["text"]) ? intval($transportation["National air transport system"]["number of registered air carriers"]["text"]) : null,
			'aircraft'             => isset($transportation["National air transport system"]["inventory of registered aircraft operated by air carriers"]["text"]) ? intval($transportation["National air transport system"]["inventory of registered aircraft operated by air carriers"]["text"]) : null,
			'aircraft_passengers'  => isset($transportation["annual passenger traffic on registered air carriers"]["text"]) ? $transportation["annual passenger traffic on registered air carriers"]["text"] : null,
			'aircraft_freight'     => isset($transportation["annual freight traffic on registered air carriers"]["text"]) ? $transportation["annual freight traffic on registered air carriers"]["text"] : null,
			'aircraft_code_prefix' => isset($transportation["Civil aircraft registration country code prefix"]["text"]) ? $transportation["Civil aircraft registration country code prefix"]["text"] : null,
			'airports'             => isset($transportation["Airports"]["text"]) ? $transportation["Airports"]["text"] : null,
			'airports_paved'       => isset($transportation["Airports - with paved runways"]["total"]["text"]) ? $transportation["Airports - with paved runways"]["total"]["text"] : null,
			'airports_info_date'   => isset($transportation["Airports"]["sub_field"]["text"]) ? $transportation["Airports"]["sub_field"]["text"] : null,
			'major_seaports'       => isset($transportation["Ports and terminals"]["text"]) ? $transportation["Ports and terminals"]["text"] : null,
			'oil_terminals'        => isset($transportation["oil terminal(s)"]["text"]) ? $transportation["oil terminal(s)"]["text"] : null,
			'cruise_ports'         => isset($transportation["cruise port(s)"]["text"]) ? $transportation["cruise port(s)"]["text"] : null,
		]);
	}
	public function seedIssues($country)
	{
		if(!isset($country['Transnational Issues'])) return false;
		$issues = $country["Transnational Issues"];

		CountryIssues::create([
			'country_id'             => $country['code'],
			'international_disputes' => isset($issues["Disputes - international"]["text"]) ? $issues["Disputes - international"]["text"] : "",
			'illicit_drugs'          => isset($issues["Illicit drugs"]["text"]) ? $issues["Illicit drugs"]["text"] : "",
			'refugees'               => isset($issues["Disputes - international"]["text"]) ? $issues["Disputes - international"]["text"] : "",
		]);
	}

}
