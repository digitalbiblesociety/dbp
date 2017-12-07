<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\CountryRegion
 *
 * @property string $country_id
 * @property int $language_id
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\CountryRegion whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\CountryRegion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\CountryRegion whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\CountryRegion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\CountryRegion whereUpdatedAt($value)
 */
class CountryRegion extends Model
{
	protected $table = 'country_regions';
	public $timestamps = false;
}
