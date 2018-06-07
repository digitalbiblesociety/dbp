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
	 *     title="Location Description",
	 *     description="A description of where the country is located on a geo-political map of the world.",
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
	 *     description="A point of latitude that falls within the borders of the country being described",
	 *     type="number",
	 *     example="17.0608160"
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
	 *     description="A point of longitude that falls within the borders of the country being described",
	 *     type="number",
	 *     example="-61.7964280"
	 * )
	 *
	 * @method static CountryGeography whereLongitude($value)
	 * @property string $longitude
	 */
	protected $longitude;
	/**
	 *
	 * @OAS\Property(
	 *     title="Map References",
	 *     description="The name of the general continent or region where the country is located.",
	 *     type="string"
	 * )
	 *
	 * @method static CountryGeography whereMapReferences($value)
	 * @property string $mapReferences
	 */
	protected $mapReferences;
	/**
	 *
	 * @OAS\Property(
	 *     title="Total Area in square kilometers",
	 *     description="area_sqkm_total",
	 *     type="integer"
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
	 *     type="integer"
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
	 *     type="integer"
	 * )
	 *
	 * @method static CountryGeography whereAreaSqkmWater($value)
	 * @property string $area_sqkm_water
	 */
	protected $area_sqkm_water;
	/**
	 *
	 * @OAS\Property(
	 *     title="The country's coastline length",
	 *     description="The length of the country's coastline in Kilometers",
	 *     type="integer"
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
