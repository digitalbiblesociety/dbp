<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CountryEconomy
 * @package App\Models\Country\FactBook
 *
 * @OAS\Schema (
 *     type="object",
 *     description="CountryEconomy",
 *     title="CountryEconomy",
 *     @OAS\Xml(name="CountryEconomy")
 * )
 *
 */
class CountryEconomy extends Model
{
	public $incrementing = false;
	public $table = "country_economy";


	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Country/properties/id")
	 * @method static CountryEconomy whereCountryId($value)
	 * @property string $country_id
	 */
	protected $country_id;
	/**
	 *
	 * @OAS\Property(
     *     title="overview",
     *     description="Economy - overview",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereOverview($value)
	 * @property string $overview
	 */
	protected $overview;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_power_parity",
     *     description="GDP (purchasing power parity)",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpPowerParity($value)
	 * @property string $gdp_power_parity
	 */
	protected $gdp_power_parity;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_real_growth",
     *     description="GDP - real growth rate",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpRealGrowth($value)
	 * @property string $gdp_real_growth
	 */
	protected $gdp_real_growth;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_per_capita",
     *     description="GDP - per capita (PPP)",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpPerCapita($value)
	 * @property string $gdp_per_capita
	 */
	protected $gdp_per_capita;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_household_consumption",
     *     description="GDP - composition, by end use; household consumption",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpHouseholdConsumption($value)
	 * @property string $gdp_household_consumption
	 */
	protected $gdp_household_consumption;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_consumption",
     *     description="GDP - composition, by end use; government consumption",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpConsumption($value)
	 * @property string $gdp_consumption
	 */
	protected $gdp_consumption;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_investment_in_fixed_capital",
     *     description="GDP - composition, by end use; investment in fixed capital",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpInvestmentInFixedCapital($value)
	 * @property string $gdp_investment_in_fixed_capital
	 */
	protected $gdp_investment_in_fixed_capital;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_investment_in_inventories",
     *     description="GDP - composition, by end use; investment in inventories",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpInvestmentInInventories($value)
	 * @property string $gdp_investment_in_inventories
	 */
	protected $gdp_investment_in_inventories;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_exports",
     *     description="GDP - composition, by end use; exports of goods and services",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpExports($value)
	 * @property string $gdp_exports
	 */
	protected $gdp_exports;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_imports",
     *     description="GDP - composition, by end use; imports of goods and services",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpImports($value)
	 * @property string $gdp_imports
	 */
	protected $gdp_imports;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_sector_agriculture",
     *     description="GDP - composition, by sector of origin; agriculture",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpSectorAgriculture($value)
	 * @property string $gdp_sector_agriculture
	 */
	protected $gdp_sector_agriculture;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_sector_industry",
     *     description="GDP - composition, by sector of origin; industry",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpSectorIndustry($value)
	 * @property string $gdp_sector_industry
	 */
	protected $gdp_sector_industry;
	/**
	 *
	 * @OAS\Property(
     *     title="gdp_sector_services",
     *     description="GDP - composition, by sector of origin; services",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereGdpSectorServices($value)
	 * @property string $gdp_sector_services
	 */
	protected $gdp_sector_services;
	/**
	 *
	 * @OAS\Property(
     *     title="agriculture_products",
     *     description="Agriculture - products",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereAgricultureProducts($value)
	 * @property string $agriculture_products
	 */
	protected $agriculture_products;
	/**
	 *
	 * @OAS\Property(
     *     title="industries",
     *     description="Industries",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereIndustries($value)
	 * @property string $industries
	 */
	protected $industries;
	/**
	 *
	 * @OAS\Property(
     *     title="industrial_growth_rate",
     *     description="Industrial production growth rate",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereIndustrialGrowthRate($value)
	 * @property string $industrial_growth_rate
	 */
	protected $industrial_growth_rate;
	/**
	 *
	 * @OAS\Property(
     *     title="labor_force",
     *     description="Labor force",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereLaborForce($value)
	 * @property string $labor_force
	 */
	protected $labor_force;
	/**
	 *
	 * @OAS\Property(
     *     title="labor_force_notes",
     *     description="Labor force; note",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereLaborForceNotes($value)
	 * @property string $labor_force_notes
	 */
	protected $labor_force_notes;
	/**
	 *
	 * @OAS\Property(
     *     title="labor_force_services",
     *     description="Labor force - by occupation; services",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereLaborForceServices($value)
	 * @property string $labor_force_services
	 */
	protected $labor_force_services;
	/**
	 *
	 * @OAS\Property(
     *     title="labor_force_industry",
     *     description="Labor force - by occupation; industry",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereLaborForceIndustry($value)
	 * @property string $labor_force_industry
	 */
	protected $labor_force_industry;
	/**
	 *
	 * @OAS\Property(
     *     title="labor_force_agriculture",
     *     description="Labor force - by occupation; agriculture",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereLaborForceAgriculture($value)
	 * @property string $labor_force_agriculture
	 */
	protected $labor_force_agriculture;
	/**
	 *
	 * @OAS\Property(
     *     title="labor_force_occupation_notes",
     *     description="Labor force - by occupation; note",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereLaborForceOccupationNotes($value)
	 * @property string $labor_force_occupation_notes
	 */
	protected $labor_force_occupation_notes;
	/**
	 *
	 * @OAS\Property(
     *     title="unemployment_rate",
     *     description="Unemployment rate",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereUnemploymentRate($value)
	 * @property string $unemployment_rate
	 */
	protected $unemployment_rate;
	/**
	 *
	 * @OAS\Property(
     *     title="population_below_poverty",
     *     description="Population below poverty line",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy wherePopulationBelowPoverty($value)
	 * @property string $population_below_poverty
	 */
	protected $population_below_poverty;
	/**
	 *
	 * @OAS\Property(
     *     title="household_income_lowest_10",
     *     description="Household income or consumption by percentage share; lowest 10%",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereHouseholdIncomeLowest10($value)
	 * @property string $household_income_lowest_10
	 */
	protected $household_income_lowest_10;
	/**
	 *
	 * @OAS\Property(
     *     title="household_income_highest_10",
     *     description="Household income or consumption by percentage share; highest 10%",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereHouseholdIncomeHighest10($value)
	 * @property string $household_income_highest_10
	 */
	protected $household_income_highest_10;
	/**
	 *
	 * @OAS\Property(
     *     title="budget_revenues",
     *     description="Budget",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereBudgetRevenues($value)
	 * @property string $budget_revenues
	 */
	protected $budget_revenues;
	/**
	 *
	 * @OAS\Property(
     *     title="taxes_revenues",
     *     description="Taxes and other revenues",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereTaxesRevenues($value)
	 * @property string $taxes_revenues
	 */
	protected $taxes_revenues;
	/**
	 *
	 * @OAS\Property(
     *     title="budget_net",
     *     description="Budget surplus (+) or deficit (-)",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereBudgetNet($value)
	 * @property string $budget_net
	 */
	protected $budget_net;
	/**
	 *
	 * @OAS\Property(
     *     title="public_debt",
     *     description="Public debt",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy wherePublicDebt($value)
	 * @property string $public_debt
	 */
	protected $public_debt;
	/**
	 *
	 * @OAS\Property(
     *     title="external_debt",
     *     description="Debt - external",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereExternalDebt($value)
	 * @property string $external_debt
	 */
	protected $external_debt;
	/**
	 *
	 * @OAS\Property(
     *     title="fiscal_year",
     *     description="Fiscal year",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereFiscalYear($value)
	 * @property string $fiscal_year
	 */
	protected $fiscal_year;
	/**
	 *
	 * @OAS\Property(
     *     title="inflation_rate",
     *     description="Inflation rate (consumer prices)",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereInflationRate($value)
	 * @property string $inflation_rate
	 */
	protected $inflation_rate;
	/**
	 *
	 * @OAS\Property(
     *     title="central_bank_discount_rate",
     *     description="Central bank discount rate",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereCentralBankDiscountRate($value)
	 * @property string $central_bank_discount_rate
	 */
	protected $central_bank_discount_rate;
	/**
	 *
	 * @OAS\Property(
     *     title="commercial_bank_prime_lending_rate",
     *     description="Commercial bank prime lending rate",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereCommercialBankPrimeLendingRate($value)
	 * @property string $commercial_bank_prime_lending_rate
	 */
	protected $commercial_bank_prime_lending_rate;
	/**
	 *
	 * @OAS\Property(
     *     title="stock_money_narrow",
     *     description="Stock of narrow money",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereStockMoneyNarrow($value)
	 * @property string $stock_money_narrow
	 */
	protected $stock_money_narrow;
	/**
	 *
	 * @OAS\Property(
     *     title="stock_money_broad",
     *     description="Stock of broad money",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereStockMoneyBroad($value)
	 * @property string $stock_money_broad
	 */
	protected $stock_money_broad;
	/**
	 *
	 * @OAS\Property(
     *     title="stock_domestic_credit",
     *     description="Stock of domestic credit",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereStockDomesticCredit($value)
	 * @property string $stock_domestic_credit
	 */
	protected $stock_domestic_credit;
	/**
	 *
	 * @OAS\Property(
     *     title="exports",
     *     description="Exports",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereExports($value)
	 * @property string $exports
	 */
	protected $exports;
	/**
	 *
	 * @OAS\Property(
     *     title="exports_commodities",
     *     description="Exports - commodities",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereExportsCommodities($value)
	 * @property string $exports_commodities
	 */
	protected $exports_commodities;
	/**
	 *
	 * @OAS\Property(
     *     title="exports_partners",
     *     description="Exports - partners",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereExportsPartners($value)
	 * @property string $exports_partners
	 */
	protected $exports_partners;
	/**
	 *
	 * @OAS\Property(
     *     title="imports",
     *     description="Imports",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereImports($value)
	 * @property string $imports
	 */
	protected $imports;
	/**
	 *
	 * @OAS\Property(
     *     title="imports_commodities",
     *     description="Imports - commodities",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereImportsCommodities($value)
	 * @property string $imports_commodities
	 */
	protected $imports_commodities;
	/**
	 *
	 * @OAS\Property(
     *     title="imports_partners",
     *     description="Imports - partners",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereImportsPartners($value)
	 * @property string $imports_partners
	 */
	protected $imports_partners;
	/**
	 *
	 * @OAS\Property(
     *     title="exchange_rates",
     *     description="Exchange rates",
     *     type="string"
     * )
	 *
	 * @method static CountryEconomy whereExchangeRates($value)
	 * @property string $exchange_rates
	 */
	protected $exchange_rates;

}
