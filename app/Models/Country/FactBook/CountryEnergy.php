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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereCo2Output($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereCrudeOilExports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereCrudeOilImports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereCrudeOilProduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereCrudeOilReserves($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereElectricityConsumption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereElectricityExports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereElectricityFossilFuels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereElectricityGeneratingCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereElectricityHydroelectric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereElectricityImports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereElectricityNuclear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereElectricityProduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereElectricityRenewable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereNaturalGasConsumption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereNaturalGasExports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereNaturalGasImports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereNaturalGasProduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereNaturalGasReserves($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy wherePetrolConsumption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy wherePetrolExports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy wherePetrolImports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy wherePetrolProduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEnergy whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CountryEnergy extends Model
{
	public $incrementing = false;
	public $table = "country_energy";
}
