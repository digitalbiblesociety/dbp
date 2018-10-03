<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Country\CountryLanguage
 *
 * @OA\Schema (
 *     type="object",
 *     description="Country Language",
 *     title="Country Language",
 *     @OA\Xml(name="CountryLanguage")
 * )
 *
 * @property-read \App\Models\Language\Language $language
 * @mixin \Eloquent
 */
class CountryLanguage extends Model
{
	protected $connection = 'dbp';
    protected $table = 'country_language';
	public $timestamps = false;
	public $incrementing = false;

	/**
	 *
	 * @OA\Property(
	 *     title="continent ID",
	 *     description="The continent code for the country",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryLanguage whereCountryId($value)
	 * @property string $country_id
	 */
	protected $country_id;
	/**
	 *
	 * @OA\Property(
	 *     title="language_id",
	 *     description="The language_id for the speakers",
	 *     type="integer"
	 * )
	 *
	 * @method static CountryLanguage whereLanguageId($value)
	 * @property int $language_id
	 */
	protected $language_id;
	/**
	 *
	 * @OA\Property(
	 *     title="population",
	 *     description="The population of the speakers",
	 *     type="integer",
	 *     example=20699
	 * )
	 *
	 * @method static CountryLanguage wherePopulation($value)
	 * @property int $population
	 */
	protected $population;

	public function language()
	{
		return $this->belongsTo(Language::class);
	}

}
