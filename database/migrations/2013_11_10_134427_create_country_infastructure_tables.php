<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryInfastructureTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp')->hasTable('country_energy')) {
            Schema::connection('dbp')->create('country_energy', function (Blueprint $table) {
                $table->char('country_id', 2);
                $table->foreign('country_id', 'FK_countries_country_energy')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
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
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('country_communications')) {
            Schema::connection('dbp')->create('country_communications', function (Blueprint $table) {
                $table->char('country_id', 2);
                $table->foreign('country_id', 'FK_countries_country_communications')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
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
                $table->decimal('internet_population_percent', 4, 1)->unsigned(); // "Internet users" | "percent of population"
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('country_transportation')) {
            Schema::connection('dbp')->create('country_transportation', function (Blueprint $table) {
                $table->char('country_id', 2);
                $table->foreign('country_id', 'FK_countries_country_transportation')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
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
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('country_economy')) {
            Schema::connection('dbp')->create('country_economy', function (Blueprint $table) {
                $table->char('country_id', 2);
                $table->foreign('country_id', 'FK_countries_country_economy')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
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
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        // "Transnational Issues"
        if (!Schema::connection('dbp')->hasTable('country_issues')) {
            Schema::connection('dbp')->create('country_issues', function (Blueprint $table) {
                $table->char('country_id', 2);
                $table->foreign('country_id', 'FK_countries_country_issues')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
                $table->text('international_disputes');   // "Disputes - international"
                $table->text('illicit_drugs');            // "Illicit drugs"
                $table->text('refugees');                 // "Refugees and internally displaced persons" | "refugees (country of origin)"
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::connection('dbp')->dropIfExists('country_energy');
        Schema::connection('dbp')->dropIfExists('country_issues');
        Schema::connection('dbp')->dropIfExists('country_economy');
        Schema::connection('dbp')->dropIfExists('country_communications');
        Schema::connection('dbp')->dropIfExists('country_transportation');
    }
}
