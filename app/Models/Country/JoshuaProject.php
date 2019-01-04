<?php

namespace App\Models\Country;

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
 * @method static JoshuaProject whereCountryId($value)
 * @method static JoshuaProject whereCreatedAt($value)
 * @method static JoshuaProject whereJoshuaProjectScale($value)
 * @method static JoshuaProject whereLanguageOfficialIso($value)
 * @method static JoshuaProject whereLanguageOfficialName($value)
 * @method static JoshuaProject wherePeopleGroups($value)
 * @method static JoshuaProject wherePeopleGroupsUnreached($value)
 * @method static JoshuaProject wherePercentChristian($value)
 * @method static JoshuaProject wherePercentLiterate($value)
 * @method static JoshuaProject wherePopulation($value)
 * @method static JoshuaProject wherePopulationUnreached($value)
 * @method static JoshuaProject wherePrimaryReligion($value)
 * @method static JoshuaProject whereResistantBelt($value)
 * @method static JoshuaProject whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Country\Country $Country
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\LanguageTranslation[] $languageTranslations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country\CountryTranslation[] $translations
 */
class JoshuaProject extends Model
{
    protected $connection = 'dbp';
    public $table = 'country_joshua_project';

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function translations()
    {
        return $this->hasMany(CountryTranslation::class, 'country_id', 'country_id');
    }
}
