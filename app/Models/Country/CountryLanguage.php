<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Country\CountryLanguage
 *
 * @property string $country_id
 * @property int $language_id
 * @property int $population
 * @property-read \App\Models\Language\Language $language
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\CountryLanguage whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\CountryLanguage whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\CountryLanguage wherePopulation($value)
 */
class CountryLanguage extends Model
{
    protected $table = "country_language";
	public $timestamps = false;
	public $incrementing = false;

	public function language()
	{
		return $this->belongsTo(Language::class);
	}

}
