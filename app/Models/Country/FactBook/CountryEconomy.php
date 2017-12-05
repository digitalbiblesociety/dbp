<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryEconomy
 *
 * @property string $country_id
 * @property string $overview
 * @property string $gdp_power_parity
 * @property string $gdp_real_growth
 * @property string $gdp_per_capita
 * @property string $gdp_household_consumption
 * @property string $gdp_consumption
 * @property string $gdp_investment_in_fixed_capital
 * @property string $gdp_investment_in_inventories
 * @property string $gdp_exports
 * @property string $gdp_imports
 * @property string $gdp_sector_agriculture
 * @property string $gdp_sector_industry
 * @property string $gdp_sector_services
 * @property string $agriculture_products
 * @property string $industries
 * @property string $industrial_growth_rate
 * @property string $labor_force
 * @property string $labor_force_notes
 * @property string $labor_force_services
 * @property string $labor_force_industry
 * @property string $labor_force_agriculture
 * @property string $labor_force_occupation_notes
 * @property string $unemployment_rate
 * @property string $population_below_poverty
 * @property string $household_income_lowest_10
 * @property string $household_income_highest_10
 * @property string $budget_revenues
 * @property string $taxes_revenues
 * @property string $budget_net
 * @property string $public_debt
 * @property string $external_debt
 * @property string $fiscal_year
 * @property string $inflation_rate
 * @property string $central_bank_discount_rate
 * @property string $commercial_bank_prime_lending_rate
 * @property string $stock_money_narrow
 * @property string $stock_money_broad
 * @property string $stock_domestic_credit
 * @property string $exports
 * @property string $exports_commodities
 * @property string $exports_partners
 * @property string $imports
 * @property string $imports_commodities
 * @property string $imports_partners
 * @property string $exchange_rates
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereAgricultureProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereBudgetNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereBudgetRevenues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereCentralBankDiscountRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereCommercialBankPrimeLendingRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereExchangeRates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereExports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereExportsCommodities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereExportsPartners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereExternalDebt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereFiscalYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpConsumption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpExports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpHouseholdConsumption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpImports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpInvestmentInFixedCapital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpInvestmentInInventories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpPerCapita($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpPowerParity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpRealGrowth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpSectorAgriculture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpSectorIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereGdpSectorServices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereHouseholdIncomeHighest10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereHouseholdIncomeLowest10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereImports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereImportsCommodities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereImportsPartners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereIndustrialGrowthRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereIndustries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereInflationRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereLaborForce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereLaborForceAgriculture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereLaborForceIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereLaborForceNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereLaborForceOccupationNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereLaborForceServices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereOverview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy wherePopulationBelowPoverty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy wherePublicDebt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereStockDomesticCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereStockMoneyBroad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereStockMoneyNarrow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereTaxesRevenues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereUnemploymentRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEconomy whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CountryEconomy extends Model
{
	public $incrementing = false;
	public $table = "country_economy";
}
