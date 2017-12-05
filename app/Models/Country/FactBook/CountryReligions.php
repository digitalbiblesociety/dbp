<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryReligions
 *
 * @property string $country_id
 * @property string $name
 * @property float $population_percentage
 * @property int $date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryReligions whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryReligions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryReligions whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryReligions whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryReligions wherePopulationPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryReligions whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CountryReligions extends Model
{
	public $incrementing = false;

}
