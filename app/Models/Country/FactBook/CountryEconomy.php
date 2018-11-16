<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CountryEconomy
 * @package App\Models\Country\FactBook
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Country Economy Model stores information about the economic status of a country as reported by the CIA's World Factbook",
 *     title="Country Economy",
 *     @OA\Xml(name="CountryEconomy")
 * )
 *
 */
class CountryEconomy extends Model
{
    protected $connection = 'dbp';
    public $incrementing = false;
    public $table = 'country_economy';


    /**
     *
     * @OA\Property(ref="#/components/schemas/Country/properties/id")
     * @method static CountryEconomy whereCountryId($value)
     * @property string $country_id
     */
    protected $country_id;
    /**
     *
     * @OA\Property(
     *     title="Overview",
     *     type="string",
     *     description="A general overview of the countries economic status.",
     *     example="Afghanistan's economy is recovering from decades of conflict. The economy has improved significantly since the fall of the Taliban regime in 2001 largely because of the infusion of international assistance, the recovery of the agricultural sector, and service sector growth..."
     * )
     *
     * @method static CountryEconomy whereOverview($value)
     * @property string $overview
     */
    protected $overview;
    /**
     *
     * @OA\Property(
     *     title="GDP (purchasing power parity)",
     *     description="The GDP (PPP) of the country",
     *     type="string",
     *     example="$64.08 billion (2016 est.) ++ $62.82 billion (2015 est.) ++ $62.35 billion (2014 est.)",
     *     @OA\ExternalDocumentation(description="For a detailed list of countries and their PPP GDP see:",url="https://en.wikipedia.org/wiki/List_of_countries_by_GDP_(PPP)_per_capita")
     * )
     *
     * @method static CountryEconomy whereGdpPowerParity($value)
     * @property string $gdp_power_parity
     */
    protected $gdp_power_parity;
    /**
     *
     * @OA\Property(
     *     title="GDP real growth rate",
     *     description="The GDP (RGR) of the country",
     *     type="string",
     *     @OA\ExternalDocumentation(description="For a detailed list of countries and their RGR GDP see:",url="https://en.wikipedia.org/wiki/List_of_countries_by_real_GDP_growth_rate")
     * )
     *
     * @method static CountryEconomy whereGdpRealGrowth($value)
     * @property string $gdp_real_growth
     */
    protected $gdp_real_growth;
    /**
     *
     * @OA\Property(
     *     title="GDP - per capita (PPP)",
     *     description="The GDP (PPP) of the country",
     *     type="string"
     * )
     *
     * @method static CountryEconomy whereGdpPerCapita($value)
     * @property string $gdp_per_capita
     */
    protected $gdp_per_capita;
    /**
     *
     * @OA\Property(
     *     title="GDP - composition, by end use; household consumption",
     *     description="",
     *     type="string"
     * )
     *
     * @method static CountryEconomy whereGdpHouseholdConsumption($value)
     * @property string $gdp_household_consumption
     */
    protected $gdp_household_consumption;
    /**
     *
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
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
