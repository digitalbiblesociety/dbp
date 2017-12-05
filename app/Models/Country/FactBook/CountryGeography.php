<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryGeography
 *
 * @property string $country_id
 * @property string|null $location_description
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $mapReferences
 * @property int|null $area_sqkm_total
 * @property int|null $area_sqkm_land
 * @property int|null $area_sqkm_water
 * @property int|null $area_km_coastline
 * @property string|null $area_note
 * @property string|null $climate
 * @property string|null $terrain
 * @property string|null $hazards
 * @property string|null $notes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereAreaKmCoastline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereAreaNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereAreaSqkmLand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereAreaSqkmTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereAreaSqkmWater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereClimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereHazards($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereLocationDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereMapReferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereTerrain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGeography whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CountryGeography extends Model
{
	public $table = "country_geography";
	public $incrementing = false;

}
