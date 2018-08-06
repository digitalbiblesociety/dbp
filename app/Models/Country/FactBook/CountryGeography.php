<?php

namespace App\Models\Country\FactBook;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\Country
 *
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="CountryGeography",
 *     title="CountryGeography",
 *     @OA\Xml(name="CountryGeography")
 * )
 *
 */
class CountryGeography extends Model
{
	protected $connection = 'dbp';
	public $table = "country_geography";
	public $incrementing = false;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Country/properties/id")
	 * @method static CountryGeography whereCountryId($value)
	 * @property string $country_id
	 */
	protected $country_id;
	/**
	 *
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
