<?php

namespace App\Models\Country\FactBook;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\Country
 *
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="CountryGeography",
 *     title="CountryGeography",
 *     @OAS\Xml(name="CountryGeography")
 * )
 *
 */
class CountryGeography extends Model
{
	public $table = "country_geography";
	public $incrementing = false;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Country/properties/id")
	 * @method static CountryGeography whereCountryId($value)
	 * @property string $country_id
	 */
	protected $country_id;
	/**
	 *
	 * @OAS\Property(
	 *     title="location_description",
	 *     description="location_description",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereLocationDescription($value)
	 * @property string $location_description
	 */
	protected $location_description;
	/**
	 *
	 * @OAS\Property(
	 *     title="latitude",
	 *     description="latitude",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereLatitude($value)
	 * @property string $latitude
	 */
	protected $latitude;
	/**
	 *
	 * @OAS\Property(
	 *     title="longitude",
	 *     description="longitude",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereLongitude($value)
	 * @property string $longitude
	 */
	protected $longitude;
	/**
	 *
	 * @OAS\Property(
	 *     title="mapReferences",
	 *     description="mapReferences",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereMapreferences($value)
	 * @property string $mapReferences
	 */
	protected $mapReferences;
	/**
	 *
	 * @OAS\Property(
	 *     title="area_sqkm_total",
	 *     description="area_sqkm_total",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereAreaSqkmTotal($value)
	 * @property string $area_sqkm_total
	 */
	protected $area_sqkm_total;
	/**
	 *
	 * @OAS\Property(
	 *     title="area_sqkm_land",
	 *     description="area_sqkm_land",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereAreaSqkmLand($value)
	 * @property string $area_sqkm_land
	 */
	protected $area_sqkm_land;
	/**
	 *
	 * @OAS\Property(
	 *     title="area_sqkm_water",
	 *     description="area_sqkm_water",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereAreaSqkmWater($value)
	 * @property string $area_sqkm_water
	 */
	protected $area_sqkm_water;
	/**
	 *
	 * @OAS\Property(
	 *     title="area_km_coastline",
	 *     description="area_km_coastline",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereAreaKmCoastline($value)
	 * @property string $area_km_coastline
	 */
	protected $area_km_coastline;
	/**
	 *
	 * @OAS\Property(
	 *     title="area_note",
	 *     description="area_note",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereAreaNote($value)
	 * @property string $area_note
	 */
	protected $area_note;
	/**
	 *
	 * @OAS\Property(
	 *     title="climate",
	 *     description="climate",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereClimate($value)
	 * @property string $climate
	 */
	protected $climate;
	/**
	 *
	 * @OAS\Property(
	 *     title="terrain",
	 *     description="terrain",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereTerrain($value)
	 * @property string $terrain
	 */
	protected $terrain;
	/**
	 *
	 * @OAS\Property(
	 *     title="hazards",
	 *     description="hazards",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereHazards($value)
	 * @property string $hazards
	 */
	protected $hazards;
	/**
	 *
	 * @OAS\Property(
	 *     title="notes",
	 *     description="notes",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereNotes($value)
	 * @property string $notes
	 */
	protected $notes;

}
