<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

	    if(!Schema::connection('dbp')->hasTable('country_geography')) {
		    Schema::connection('dbp')->create('country_geography', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->text('location_description')->nullable();
			    $table->decimal('latitude', 10, 7)->nullable();
			    $table->decimal('longitude', 10, 7)->nullable();
			    $table->string('mapReferences')->nullable();
			    $table->integer('area_sqkm_total')->unsigned()->nullable();
			    $table->integer('area_sqkm_land')->unsigned()->nullable();
			    $table->integer('area_sqkm_water')->unsigned()->nullable();
			    $table->integer('area_km_coastline')->unsigned()->nullable();
			    $table->text('area_note')->nullable();
			    $table->text('climate')->nullable();
			    $table->text('terrain')->nullable();
			    $table->text('hazards')->nullable();
			    $table->text('notes')->nullable();
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('country_maps')) {
		    Schema::connection('dbp')->create('country_maps', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->string('name');
			    $table->string('thumbnail_url');
			    $table->string('map_url');
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('country_people')) {
		    Schema::connection('dbp')->create('country_people', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->text('languages');                                                          // Languages moved to country_language
			    $table->text('religions');                                                          // Religions moved to country_religion
			    $table->integer('population')->unsigned()->nullable();                              // "Population"
			    $table->integer('population_date')->unsigned()->nullable();                         // "Population" - substring
			    $table->string('nationality_noun')->nullable();                                     // "Nationality" | "noun"
			    $table->string('nationality_adjective')->nullable();                                // "Nationality" | "adjective"
			    $table->decimal('age_structure_14', 4, 2)->unsigned()->nullable();                  // "0-14 years"
			    $table->decimal('age_structure_24', 4, 2)->unsigned()->nullable();                  // "15-24 years"
			    $table->decimal('age_structure_54', 4, 2)->unsigned()->nullable();                  // "25-54 years"
			    $table->decimal('age_structure_64', 4, 2)->unsigned()->nullable();                  // "55-64 years"
			    $table->decimal('age_structure_65', 4, 2)->unsigned()->nullable();                  // "65 years and over"
			    $table->decimal('dependency_total', 4, 2)->unsigned()->nullable();                  // "total dependency ratio"
			    $table->decimal('dependency_youth', 4, 2)->unsigned()->nullable();                  // "youth dependency ratio"
			    $table->decimal('dependency_elder', 4, 2)->unsigned()->nullable();                  // "elderly dependency ratio"
			    $table->decimal('dependency_potential', 4, 2)->unsigned()->nullable();              // "potential support ratio"
			    $table->decimal('median_age_total', 3, 2)->unsigned()->nullable();                  // "total"
			    $table->decimal('median_age_male', 3, 2)->unsigned()->nullable();                   // "male"
			    $table->decimal('median_age_female', 3, 2)->unsigned()->nullable();                 // "female"
			    $table->decimal('population_growth_rate_percentage', 3, 2)->nullable();             // "Population growth rate"
			    $table->decimal('birth_rate_per_1k')->unsigned()->nullable();                       // "Birth rate"
			    $table->decimal('death_rate_per_1k')->unsigned()->nullable();                       // "Death rate"
			    $table->decimal('net_migration_per_1k', 6, 2)->nullable();                          // "Net migration rate"
			    $table->text('population_distribution')->nullable();                                // "Population distribution"
			    $table->decimal('urban_population_percentage', 4, 2)->unsigned()->nullable();       // "Urbanization" | "urban population"
			    $table->decimal('urbanization_rate', 4, 2)->unsigned()->nullable();                 // "Urbanization" | "rate of urbanization"
			    $table->string('major_urban_areas_population')->nullable();                         // "Major urban areas - population"
			    $table->decimal('sex_ratio_birth', 3, 1)->unsigned()->nullable();                   // "Sex ratio" | "at birth"
			    $table->decimal('sex_ratio_14', 3, 1)->unsigned()->nullable();                      // "Sex ratio" | "0-14 years"
			    $table->decimal('sex_ratio_24', 3, 1)->unsigned()->nullable();                      // "Sex ratio" | "15-24 years"
			    $table->decimal('sex_ratio_54', 3, 1)->unsigned()->nullable();                      // "Sex ratio" | "25-54 years"
			    $table->decimal('sex_ratio_64', 3, 1)->unsigned()->nullable();                      // "Sex ratio" | "55-64 years"
			    $table->decimal('sex_ratio_65', 3, 1)->unsigned()->nullable();                      // "Sex ratio" | "65 years and over"
			    $table->decimal('sex_ratio_total', 3, 1)->unsigned()->nullable();                   // "Sex ratio" | "total population"
			    $table->tinyInteger('mother_age_first_birth')->unsigned()->nullable();                // "Mother's mean age at first birth"
			    $table->decimal('maternal_mortality_rate', 3, 1)->unsigned()->nullable();             // "Maternal mortality rate"
			    $table->decimal('infant_mortality_per_1k_total', 3, 2)->unsigned()->nullable();     // "Infant mortality rate" | "total"
			    $table->decimal('infant_mortality_per_1k_male', 3, 2)->unsigned()->nullable();      // "Infant mortality rate" | "male"
			    $table->decimal('infant_mortality_per_1k_female', 3, 2)->unsigned()->nullable();    // "Infant mortality rate" | "female"
			    $table->decimal('life_expectancy_at_birth_total', 3, 1)->unsigned()->nullable();    // "Life expectancy at birth" | "total population"
			    $table->decimal('life_expectancy_at_birth_male', 3, 1)->unsigned()->nullable();     // "Life expectancy at birth" | "male"
			    $table->decimal('life_expectancy_at_birth_female', 3, 1)->unsigned()->nullable();   // "Life expectancy at birth" | "female"
			    $table->decimal('total_fertility_rate', 4, 2)->nullable();                          // "Total fertility rate"
			    $table->decimal('contraceptive_prevalence', 4, 2)->nullable();                      // "Contraceptive prevalence rate"
			    $table->decimal('health_expenditures', 4, 2)->nullable();                           // "Health expenditures"
			    $table->decimal('physicians', 4, 2)->nullable();                                    // "Physicians density"
			    $table->decimal('hospital_beds', 4, 2)->nullable();                                 // "Hospital bed density"
			    $table->decimal('drinking_water_source_urban_improved', 5, 2)->nullable();          // "Drinking water source" | "improved" - sub
			    $table->decimal('drinking_water_source_rural_improved', 5, 2)->nullable();          // "Drinking water source" | "improved" - sub
			    $table->decimal('sanitation_facility_access_urban_improved', 5, 2)->nullable();     // "Sanitation facility access" | "improved" - sub
			    $table->decimal('sanitation_facility_access_rural_improved', 5, 2)->nullable();     // "Sanitation facility access" | "improved" - sub
			    $table->decimal('hiv_infection_rate', 4, 2)->nullable();                            // "HIV/AIDS - adult prevalence rate"
			    $table->decimal('hiv_infected', 4, 2)->nullable();                                  // "HIV/AIDS - people living with HIV/AIDS"
			    $table->decimal('hiv_deaths', 4, 2)->nullable();                                    // "HIV/AIDS - deaths"
			    $table->decimal('obesity_rate', 4, 2)->nullable();                                  // "Obesity - adult prevalence rate"
			    $table->decimal('underweight_children', 4, 2)->nullable();                          // "Children under the age of 5 years underweight"
			    $table->string('education_expenditures')->nullable();                               // "Education expenditures"
			    $table->string('literacy_definition')->nullable();                                  // "Literacy" | "definition"
			    $table->decimal('literacy_total', 5, 2)->unsigned()->nullable();                    // "Literacy" | "total population"
			    $table->decimal('literacy_male', 5, 2)->unsigned()->nullable();                     // "Literacy" | "male"
			    $table->decimal('literacy_female', 5, 2)->unsigned()->nullable();                   // "Literacy" | "female"
			    $table->tinyInteger('school_years_total')->unsigned()->nullable();                  // "School life expectancy (primary to tertiary education)" | "total"
			    $table->tinyInteger('school_years_male')->unsigned()->nullable();                   // "School life expectancy (primary to tertiary education)" | "male"
			    $table->tinyInteger('school_years_female')->unsigned()->nullable();                 // "School life expectancy (primary to tertiary education)" | "female"
			    $table->integer('child_labor')->unsigned()->nullable();                             // "Child labor - children ages 5-14" | "total number"
			    $table->decimal('child_labor_percentage', 4, 2)->unsigned()->nullable();            // "Child labor - children ages 5-14" | "percentage"
			    $table->decimal('unemployment_youth_total', 4, 2)->unsigned()->nullable();          // "Unemployment, youth ages 15-24" | "total"
			    $table->decimal('unemployment_youth_male', 4, 2)->unsigned()->nullable();           // "Unemployment, youth ages 15-24" | "male"
			    $table->decimal('unemployment_youth_female', 4, 2)->unsigned()->nullable();         // "Unemployment, youth ages 15-24" | "female"
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('country_people_ethnicities')) {
		    Schema::connection('dbp')->create('country_people_ethnicities', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->string('name');
			    $table->decimal('population_percentage', 5, 2)->unsigned();
			    $table->tinyInteger('date')->unsigned()->nullable();
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('country_religions')) {
		    Schema::connection('dbp')->create('country_religions', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->string('name');
			    $table->decimal('population_percentage', 5, 2)->unsigned()->nullable();
			    $table->tinyInteger('date')->unsigned()->nullable();
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('country_government')) {
		    Schema::connection('dbp')->create('country_government', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->string('name');                                       // "Country name" | "conventional long form"
			    $table->text('name_etymology');                               // "Country name" | "etymology"
			    $table->string('conventional_long_form');                     // "Country name" | "conventional long form"
			    $table->string('conventional_short_form');                    // "Country name" | "conventional short form"
			    $table->text('dependency_status');                            // "Dependency status"
			    $table->text('government_type');                              // "Government type"
			    $table->text('capital');                                      // "Capital" | "name"
			    $table->string('capital_coordinates');                        // "Capital" | "geographic coordinates"
			    $table->string('capital_time_zone');                          // "Capital" | "time difference"
			    $table->text('administrative_divisions');                     // "Administrative divisions"
			    $table->text('administrative_divisions_note');                // "Administrative divisions" | "note"
			    $table->text('independence');                                 // "Independence"
			    $table->text('national_holiday');                             // "National holiday"
			    $table->text('constitution');                                 // "Constitution"
			    $table->text('legal_system');                                 // "Legal system"
			    $table->string('citizenship');                                // "Citizenship"
			    $table->text('suffrage');                                     // "Suffrage"
			    $table->text('executive_chief_of_state');                     // "Executive branch" | "chief of state"
			    $table->text('executive_head_of_government');                 // "Executive branch" | "head of government"
			    $table->text('executive_cabinet');                            // "Executive branch" | "cabinet"
			    $table->text('executive_elections');                          // "Executive branch" | "elections/appointments"
			    $table->text('executive_election_results');                   // "Executive branch" | "election results"
			    $table->text('legislative_description');                      // "Legislative branch" | "description"
			    $table->text('legislative_elections');                        // "Legislative branch" | "elections"
			    $table->text('legislative_election_results');                 // "Legislative branch" | "election results"
			    $table->text('legislative_highest_courts');                   // "Judicial branch" | "highest court(s)"
			    $table->text('legislative_judge_selection');                  // "Judicial branch" | "judge selection and term of office"
			    $table->text('legislative_subordinate_courts');               // "Judicial branch" | "subordinate courts"
			    $table->text('political_parties');                            // "Political parties and leaders"
			    $table->string('political_pressure');                         // "Political parties and leaders" | "other"
			    $table->text('international_organization_participation');     // "International organization participation"
			    $table->text('diplomatic_representation_in_usa');             // "Diplomatic representation in the US"
			    $table->text('diplomatic_representation_from_usa');           // "Diplomatic representation from the US"
			    $table->text('flag_description');                             // "Flag description"
			    $table->text('national_symbols');                             // "National symbol(s)"
			    $table->string('national_anthem');                            // "National anthem"
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('country_economy')) {
		    Schema::connection('dbp')->create('country_economy', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->text('overview');                             // "Economy - overview"
			    $table->string('gdp_power_parity');                   // "GDP (purchasing power parity)"
			    $table->string('gdp_real_growth');                    // "GDP - real growth rate"
			    $table->string('gdp_per_capita');                     // "GDP - per capita (PPP)"
			    $table->string('gdp_household_consumption');          // "GDP - composition, by end use" | "household consumption"
			    $table->string('gdp_consumption');                    // "GDP - composition, by end use" | "government consumption"
			    $table->string('gdp_investment_in_fixed_capital');    // "GDP - composition, by end use" | "investment in fixed capital"
			    $table->string('gdp_investment_in_inventories');      // "GDP - composition, by end use" | "investment in inventories"
			    $table->string('gdp_exports');                        // "GDP - composition, by end use" | "exports of goods and services"
			    $table->string('gdp_imports');                        // "GDP - composition, by end use" | "imports of goods and services"
			    $table->string('gdp_sector_agriculture');             // "GDP - composition, by sector of origin" | "agriculture"
			    $table->string('gdp_sector_industry');                // "GDP - composition, by sector of origin" | "industry"
			    $table->string('gdp_sector_services');                // "GDP - composition, by sector of origin" | "services"
			    $table->text('agriculture_products');                 // "Agriculture - products"
			    $table->text('industries');                           // "Industries"
			    $table->string('industrial_growth_rate');             // "Industrial production growth rate"
			    $table->string('labor_force');                        // "Labor force"
			    $table->string('labor_force_notes');                  // "Labor force" | "note"
			    $table->string('labor_force_services');               // "Labor force - by occupation" | "services"
			    $table->string('labor_force_industry');               // "Labor force - by occupation" | "industry"
			    $table->string('labor_force_agriculture');            // "Labor force - by occupation" | "agriculture"
			    $table->string('labor_force_occupation_notes');       // "Labor force - by occupation" | "note"
			    $table->string('unemployment_rate');                  // "Unemployment rate"
			    $table->string('population_below_poverty');           // "Population below poverty line"
			    $table->string('household_income_lowest_10');         // "Household income or consumption by percentage share" | "lowest 10%"
			    $table->string('household_income_highest_10');        // "Household income or consumption by percentage share" | "highest 10%"
			    $table->string('budget_revenues');                    // "Budget"
			    $table->string('taxes_revenues');                     // "Taxes and other revenues"
			    $table->string('budget_net');                         // "Budget surplus (+) or deficit (-)"
			    $table->string('public_debt');                        // "Public debt"
			    $table->string('external_debt');                      // "Debt - external"
			    $table->string('fiscal_year');                        // "Fiscal year"
			    $table->string('inflation_rate');                     // "Inflation rate (consumer prices)"
			    $table->string('central_bank_discount_rate');         // "Central bank discount rate"
			    $table->string('commercial_bank_prime_lending_rate'); // "Commercial bank prime lending rate"
			    $table->string('stock_money_narrow');                 // "Stock of narrow money"
			    $table->string('stock_money_broad');                  // "Stock of broad money"
			    $table->string('stock_domestic_credit');              // "Stock of domestic credit"
			    $table->string('exports');                            // "Exports"
			    $table->text('exports_commodities');                  // "Exports - commodities"
			    $table->string('exports_partners');                   // "Exports - partners"
			    $table->string('imports');                            // "Imports"
			    $table->text('imports_commodities');                  // "Imports - commodities"
			    $table->string('imports_partners');                   // "Imports - partners"
			    $table->string('exchange_rates');                     // "Exchange rates"
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('country_energy')) {
		    Schema::connection('dbp')->create('country_energy', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->string('electricity_production');           // "Electricity - production"
			    $table->string('electricity_consumption');          // "Electricity - consumption"
			    $table->string('electricity_exports');              // "Electricity - exports"
			    $table->string('electricity_imports');              // "Electricity - imports"
			    $table->string('electricity_generating_capacity');  // "Electricity - installed generating capacity"
			    $table->string('electricity_fossil_fuels');         // "Electricity - from fossil fuels"
			    $table->string('electricity_nuclear');              // "Electricity - from nuclear fuels"
			    $table->string('electricity_hydroelectric');        // "Electricity - from hydroelectric plants"
			    $table->string('electricity_renewable');            // "Electricity - from other renewable sources"
			    $table->string('crude_oil_production');             // "Crude oil - production"
			    $table->string('crude_oil_exports');                // "Crude oil - exports"
			    $table->string('crude_oil_imports');                // "Crude oil - imports"
			    $table->string('crude_oil_reserves');               // "Crude oil - proved reserves"
			    $table->string('petrol_production');                // "Refined petroleum products - production"
			    $table->string('petrol_consumption');               // "Refined petroleum products - consumption"
			    $table->string('petrol_exports');                   // "Refined petroleum products - exports"
			    $table->string('petrol_imports');                   // "Refined petroleum products - imports"
			    $table->string('natural_gas_production');           // "Natural gas - production"
			    $table->string('natural_gas_consumption');          // "Natural gas - consumption"
			    $table->string('natural_gas_exports');              // "Natural gas - exports"
			    $table->string('natural_gas_imports');              // "Natural gas - imports"
			    $table->string('natural_gas_reserves');             // "Natural gas - proved reserves"
			    $table->string('co2_output');                       // "Carbon dioxide emissions from consumption of energy"
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('country_communications')) {
		    Schema::connection('dbp')->create('country_communications', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->string('fixed_phones_total');                           // "Telephones - fixed lines" | "total subscriptions"
			    $table->string('fixed_phones_subs_per_100');                    // "Telephones - fixed lines" | "subscriptions per 100 inhabitants"
			    $table->string('mobile_phones_total');                          // "Telephones - mobile cellular" | "total"
			    $table->string('mobile_phones_subs_per_100');                   // "Telephones - mobile cellular" | "subscriptions per 100 inhabitants"
			    $table->text('phone_system_general_assessment');                // "Telephone system" | "general assessment"
			    $table->text('phone_system_international');                     // "Telephone system" | "international"
			    $table->text('phone_system_domestic');                          // "Telephone system" | "domestic"
			    $table->text('broadcast_media');                                // "Broadcast media"
			    $table->char('internet_country_code', 2);                       // "Internet country code"
			    $table->string('internet_total_users');                         // "Internet users" | "total"
			    $table->decimal('internet_population_percent', 4,
				    1)->unsigned(); // "Internet users" | "percent of population"
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('country_transportation')) {
		    Schema::connection('dbp')->create('country_transportation', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->integer('air_carriers')->unsigned()->nullable();            // "National air transport system" | "number of registered air carriers"
			    $table->integer('aircraft')->unsigned()->nullable();                // "National air transport system" | "inventory of registered aircraft operated by air carriers"
			    $table->integer('aircraft_passengers')->nullable()->unsigned();     // "annual passenger traffic on registered air carriers"
			    $table->integer('aircraft_freight')->nullable()->unsigned();        // "annual freight traffic on registered air carriers"
			    $table->string('aircraft_code_prefix')->nullable();                 // "Civil aircraft registration country code prefix"
			    $table->string('airports')->nullable();                             // "Airports"
			    $table->string('airports_paved')->nullable();                       // "Airports - with paved runways" | "total"
			    $table->tinyInteger('airports_info_date')->nullable()->unsigned();  // "Airports" | sub_field
			    $table->string('major_seaports')->nullable();                       // "Ports and terminals"
			    $table->string('oil_terminals')->nullable();                        // "oil terminal(s)"
			    $table->string('cruise_ports')->nullable();                         // "cruise port(s)"
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    // "Transnational Issues"
	    if(!Schema::connection('dbp')->hasTable('country_issues')) {
		    Schema::connection('dbp')->create('country_issues', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->text('international_disputes');   // "Disputes - international"
			    $table->text('illicit_drugs');            // "Illicit drugs"
			    $table->text('refugees');                 // "Refugees and internally displaced persons" | "refugees (country of origin)"
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('country_joshua_project')) {
		    Schema::connection('dbp')->create('country_joshua_project', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->char('language_official_iso', 3);
			    $table->foreign('language_official_iso')->references('iso')->on('languages')->onUpdate('cascade');
			    $table->string('language_official_name')->nullable();
			    $table->bigInteger('population')->unsigned()->default(0);
			    $table->bigInteger('population_unreached')->unsigned()->default(0);
			    $table->integer('people_groups')->unsigned()->default(0);
			    $table->integer('people_groups_unreached')->unsigned()->default(0);
			    $table->tinyinteger('joshua_project_scale')->unsigned()->default(0);
			    $table->string('primary_religion')->nullable();
			    $table->float('percent_christian')->nullable();
			    $table->boolean('resistant_belt')->default(0);
			    $table->float('percent_literate')->nullable();
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	# Factbook
	    Schema::connection('dbp')->dropIfExists('country_people');
	    Schema::connection('dbp')->dropIfExists('country_energy');
	    Schema::connection('dbp')->dropIfExists('country_issues');
	    Schema::connection('dbp')->dropIfExists('country_economy');
	    Schema::connection('dbp')->dropIfExists('country_religions');
	    Schema::connection('dbp')->dropIfExists('country_government');
	    Schema::connection('dbp')->dropIfExists('country_geography');
	    Schema::connection('dbp')->dropIfExists('country_communications');
	    Schema::connection('dbp')->dropIfExists('country_transportation');
	    Schema::connection('dbp')->dropIfExists('country_people_ethnicities');
	    Schema::connection('dbp')->dropIfExists('country_joshua_project');
    }
}
