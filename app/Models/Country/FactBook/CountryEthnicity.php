<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryEthnicity
 *
 * @mixin \Eloquent
 * @property string $country_id
 * @property string $name
 * @property float $population_percentage
 * @property int|null $date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEthnicity whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEthnicity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEthnicity whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEthnicity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEthnicity wherePopulationPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryEthnicity whereUpdatedAt($value)
 */
class CountryEthnicity extends Model
{
	public $table = "country_people_ethnicities";
	public $incrementing = false;

}
