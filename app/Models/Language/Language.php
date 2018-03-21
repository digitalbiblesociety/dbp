<?php

namespace App\Models\Language;

use App\Models\Bible\Bible;
use App\Models\Bible\Video;
use App\Models\Country\CountryRegion;
use Illuminate\Database\Eloquent\Model;

use App\Models\Language\LanguageCode;
use App\Models\Language\LanguageDialect;
use App\Models\Language\LanguageAltName;
use App\Models\Language\LanguageTranslation;
use App\Models\Language\LanguageClassification;

use App\Models\Language\Alphabet;
use App\Models\Language\AlphabetFont;


use App\Models\Country\Country;
use App\Models\Resource\Resource;

/**
 * App\Models\Language\Language
 *
 * @property int $id
 * @property string|null $glotto_id
 * @property string|null $iso
 * @property string|null $iso2B
 * @property string|null $iso2T
 * @property string|null $iso1
 * @property string $name
 * @property string|null $autonym
 * @property string|null $level
 * @property string|null $maps
 * @property string|null $development
 * @property string|null $use
 * @property string|null $location
 * @property string|null $area
 * @property int|null $population
 * @property string|null $population_notes
 * @property string|null $notes
 * @property string|null $typology
 * @property string|null $writing
 * @property string|null $description
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $status
 * @property string|null $country_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\Alphabet[] $alphabets
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\LanguageAltName[] $alternativeNames
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Bible[] $bibleCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Bible[] $bibles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\LanguageClassification[] $classifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\LanguageCode[] $codes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country\Country[] $countries
 * @property-read \App\Models\Language\LanguageTranslation $currentTranslation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\LanguageDialect[] $dialects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Video[] $films
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\AlphabetFont[] $fonts
 * @property-read \App\Models\Language\LanguageCode $iso639_2
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\Language[] $languages
 * @property-read \App\Models\Language\LanguageDialect $parent
 * @property-read \App\Models\Country\Country|null $primaryCountry
 * @property-read \App\Models\Country\CountryRegion $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource\Resource[] $resources
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\LanguageTranslation[] $translations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereAutonym($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereDevelopment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereGlottoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereIso1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereIso2B($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereIso2T($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereMaps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language wherePopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language wherePopulationNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereTypology($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereWriting($value)
 * @mixin \Eloquent
 * @property int|null $bible_status
 * @property int|null $bible_translation_need
 * @property int|null $bible_year
 * @property int|null $bible_year_newTestament
 * @property int|null $bible_year_portions
 * @property string|null $bible_sample_text
 * @property string|null $bible_sample_img
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereBibleSampleImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereBibleSampleText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereBibleStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereBibleTranslationNeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereBibleYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereBibleYearNewTestament($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Language whereBibleYearPortions($value)
 */
class Language extends Model
{

	public $table = "languages";
    public $primaryKey = 'id';

    protected $hidden = ["pivot"];
    protected $fillable = ['glotto_id','iso','name','level','maps','development','use','location','area','population','population_notes','notes','typology','writing','description','family_pk','father_pk','child_dialect_count','child_family_count','child_language_count','latitude','longitude','pk','status','country_id','scope'];

    public function alphabets()
    {
        return $this->BelongsToMany(Alphabet::class,'alphabet_language','script','id')->distinct();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->HasMany(LanguageTranslation::class,'language_source','language_translation');
    }

	public function autonym()
	{
		return $this->HasOne(LanguageTranslation::class,'language_source')->where('vernacular', 1);
	}

    public function currentTranslation($iso = null)
    {
    	if($iso == null) $iso = \i18n::getCurrentLocale();
        $language = Language::where('iso',$iso)->first();
        return $this->HasOne(LanguageTranslation::class,'language_source')->where('language_translation', $language->id);
    }

    public function countries()
    {
        return $this->BelongsToMany(Country::class, 'country_language');
    }

    public function primaryCountry()
    {
        return $this->BelongsTo(Country::class,'country_id','id','countries');
    }

    public function region()
    {
    	return $this->HasOne(CountryRegion::class,'country_id');
    }

    public function fonts()
    {
        return $this->HasMany(AlphabetFont::class, 'iso');
    }

    public function bibles()
    {
        return $this->HasMany(Bible::class,'iso','iso');
    }

    public function bibleCount()
    {
	    return $this->HasMany(Bible::class);
    }

    public function films()
    {
        return $this->HasMany(Video::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    public function codes()
    {
        return $this->HasMany(LanguageCode::class, 'language_id','id');
    }

    public function iso639_2()
    {
        return $this->HasOne(LanguageCode::class);
    }

    public function classifications()
    {
        return $this->HasMany(LanguageClassification::class);
    }

    public function dialects()
    {
        return $this->HasMany(LanguageDialect::class,'language_id','id');
    }

	public function parent()
	{
		return $this->HasOne(LanguageDialect::class,'dialect_id', 'id');
	}

}
