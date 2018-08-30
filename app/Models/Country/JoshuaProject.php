<?php

namespace App\Models\Country;

use App\Models\Language\LanguageTranslation;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\JoshuaProject
 *
 * @property string $country_id
 * @property string $language_official_iso
 * @property string|null $language_official_name
 * @property int $population
 * @property int $population_unreached
 * @property int $people_groups
 * @property int $people_groups_unreached
 * @property int $joshua_project_scale
 * @property string|null $primary_religion
 * @property float|null $percent_christian
 * @property int $resistant_belt
 * @property float|null $percent_literate
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject whereJoshuaProjectScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject whereLanguageOfficialIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject whereLanguageOfficialName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject wherePeopleGroups($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject wherePeopleGroupsUnreached($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject wherePercentChristian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject wherePercentLiterate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject wherePopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject wherePopulationUnreached($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject wherePrimaryReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject whereResistantBelt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\JoshuaProject whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Country\Country $Country
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\LanguageTranslation[] $languageTranslations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country\CountryTranslation[] $translations
 */
class JoshuaProject extends Model
{
	protected $connection = 'dbp';
    public $table = "country_joshua_project";

	public function country()
	{
		return $this->belongsTo(Country::class,'country_id','id');
	}

	public function translations()
	{
		return $this->HasMany(CountryTranslation::class,'country_id','country_id');
	}

	public function languageTranslations()
	{
		return $this->HasMany(LanguageTranslation::class,'iso','iso');
	}

}
