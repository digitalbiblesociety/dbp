<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryEnergy
 *
 * @property string $country_id
 * @property string $electricity_production
 * @property string $electricity_consumption
 * @property string $electricity_exports
 * @property string $electricity_imports
 * @property string $electricity_generating_capacity
 * @property string $electricity_fossil_fuels
 * @property string $electricity_nuclear
 * @property string $electricity_hydroelectric
 * @property string $electricity_renewable
 * @property string $crude_oil_production
 * @property string $crude_oil_exports
 * @property string $crude_oil_imports
 * @property string $crude_oil_reserves
 * @property string $petrol_production
 * @property string $petrol_consumption
 * @property string $petrol_exports
 * @property string $petrol_imports
 * @property string $natural_gas_production
 * @property string $natural_gas_consumption
 * @property string $natural_gas_exports
 * @property string $natural_gas_imports
 * @property string $natural_gas_reserves
 * @property string $co2_output
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static Builder|CountryEnergy whereCo2Output($value)
 * @method static Builder|CountryEnergy whereCountryId($value)
 * @method static Builder|CountryEnergy whereCreatedAt($value)
 * @method static Builder|CountryEnergy whereCrudeOilExports($value)
 * @method static Builder|CountryEnergy whereCrudeOilImports($value)
 * @method static Builder|CountryEnergy whereCrudeOilProduction($value)
 * @method static Builder|CountryEnergy whereCrudeOilReserves($value)
 * @method static Builder|CountryEnergy whereElectricityConsumption($value)
 * @method static Builder|CountryEnergy whereElectricityExports($value)
 * @method static Builder|CountryEnergy whereElectricityFossilFuels($value)
 * @method static Builder|CountryEnergy whereElectricityGeneratingCapacity($value)
 * @method static Builder|CountryEnergy whereElectricityHydroelectric($value)
 * @method static Builder|CountryEnergy whereElectricityImports($value)
 * @method static Builder|CountryEnergy whereElectricityNuclear($value)
 * @method static Builder|CountryEnergy whereElectricityProduction($value)
 * @method static Builder|CountryEnergy whereElectricityRenewable($value)
 * @method static Builder|CountryEnergy whereNaturalGasConsumption($value)
 * @method static Builder|CountryEnergy whereNaturalGasExports($value)
 * @method static Builder|CountryEnergy whereNaturalGasImports($value)
 * @method static Builder|CountryEnergy whereNaturalGasProduction($value)
 * @method static Builder|CountryEnergy whereNaturalGasReserves($value)
 * @method static Builder|CountryEnergy wherePetrolConsumption($value)
 * @method static Builder|CountryEnergy wherePetrolExports($value)
 * @method static Builder|CountryEnergy wherePetrolImports($value)
 * @method static Builder|CountryEnergy wherePetrolProduction($value)
 * @method static Builder|CountryEnergy whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CountryEnergy extends Model
{
	public $incrementing = false;
	public $table = "country_energy";
}
