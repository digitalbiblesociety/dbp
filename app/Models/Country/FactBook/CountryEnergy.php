<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryEnergy
 *
 * @OAS\Schema (
 *     type="object",
 *     description="CountryEnergy",
 *     title="CountryEnergy",
 *     @OAS\Xml(name="CountryEnergy")
 * )
 *
 * @mixin \Eloquent
 */
class CountryEnergy extends Model
{
	public $incrementing = false;
	public $table = "country_energy";


/**
 *
 * @OAS\Property(ref="#/components/schemas/Country/properties/id")
 * @method static CountryEnergy whereCountryId($value)
 * @property string $country_id
 */
protected $country_id;
/**
 *
 * @OAS\Property(
 *     title="electricity_production",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereElectricityProduction($value)
 * @property string $electricity_production
 */
protected $electricity_production;
/**
 *
 * @OAS\Property(
 *     title="electricity_consumption",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereElectricityConsumption($value)
 * @property string $electricity_consumption
 */
protected $electricity_consumption;
/**
 *
 * @OAS\Property(
 *     title="electricity_exports",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereElectricityExports($value)
 * @property string $electricity_exports
 */
protected $electricity_exports;
/**
 *
 * @OAS\Property(
 *     title="electricity_imports",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereElectricityImports($value)
 * @property string $electricity_imports
 */
protected $electricity_imports;
/**
 *
 * @OAS\Property(
 *     title="electricity_generating_capacity",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereElectricityGeneratingCapacity($value)
 * @property string $electricity_generating_capacity
 */
protected $electricity_generating_capacity;
/**
 *
 * @OAS\Property(
 *     title="electricity_fossil_fuels",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereElectricityFossilFuels($value)
 * @property string $electricity_fossil_fuels
 */
protected $electricity_fossil_fuels;
/**
 *
 * @OAS\Property(
 *     title="electricity_nuclear",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereElectricityNuclear($value)
 * @property string $electricity_nuclear
 */
protected $electricity_nuclear;
/**
 *
 * @OAS\Property(
 *     title="electricity_hydroelectric",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereElectricityHydroelectric($value)
 * @property string $electricity_hydroelectric
 */
protected $electricity_hydroelectric;
/**
 *
 * @OAS\Property(
 *     title="electricity_renewable",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereElectricityRenewable($value)
 * @property string $electricity_renewable
 */
protected $electricity_renewable;
/**
 *
 * @OAS\Property(
 *     title="crude_oil_production",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereCrudeOilProduction($value)
 * @property string $crude_oil_production
 */
protected $crude_oil_production;
/**
 *
 * @OAS\Property(
 *     title="crude_oil_exports",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereCrudeOilExports($value)
 * @property string $crude_oil_exports
 */
protected $crude_oil_exports;
/**
 *
 * @OAS\Property(
 *     title="crude_oil_imports",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereCrudeOilImports($value)
 * @property string $crude_oil_imports
 */
protected $crude_oil_imports;
/**
 *
 * @OAS\Property(
 *     title="crude_oil_reserves",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereCrudeOilReserves($value)
 * @property string $crude_oil_reserves
 */
protected $crude_oil_reserves;
/**
 *
 * @OAS\Property(
 *     title="petrol_production",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy wherePetrolProduction($value)
 * @property string $petrol_production
 */
protected $petrol_production;
/**
 *
 * @OAS\Property(
 *     title="petrol_consumption",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy wherePetrolConsumption($value)
 * @property string $petrol_consumption
 */
protected $petrol_consumption;
/**
 *
 * @OAS\Property(
 *     title="petrol_exports",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy wherePetrolExports($value)
 * @property string $petrol_exports
 */
protected $petrol_exports;
/**
 *
 * @OAS\Property(
 *     title="petrol_imports",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy wherePetrolImports($value)
 * @property string $petrol_imports
 */
protected $petrol_imports;
/**
 *
 * @OAS\Property(
 *     title="natural_gas_production",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereNaturalGasProduction($value)
 * @property string $natural_gas_production
 */
protected $natural_gas_production;
/**
 *
 * @OAS\Property(
 *     title="natural_gas_consumption",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereNaturalGasConsumption($value)
 * @property string $natural_gas_consumption
 */
protected $natural_gas_consumption;
/**
 *
 * @OAS\Property(
 *     title="natural_gas_exports",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereNaturalGasExports($value)
 * @property string $natural_gas_exports
 */
protected $natural_gas_exports;
/**
 *
 * @OAS\Property(
 *     title="natural_gas_imports",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereNaturalGasImports($value)
 * @property string $natural_gas_imports
 */
protected $natural_gas_imports;
/**
 *
 * @OAS\Property(
 *     title="natural_gas_reserves",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereNaturalGasReserves($value)
 * @property string $natural_gas_reserves
 */
protected $natural_gas_reserves;
/**
 *
 * @OAS\Property(
 *     title="co2_output",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereCo2Output($value)
 * @property string $co2_output
 */
protected $co2_output;
/**
 *
 * @OAS\Property(
 *     title="created_at",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereCreatedAt($value)
 * @property \Carbon\Carbon|null $created_at
 */
protected $created_at;
/**
 *
 * @OAS\Property(
 *     title="updated_at",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryEnergy whereUpdatedAt($value)
 * @property \Carbon\Carbon|null $updated_at
 */
protected $updated_at;

}
