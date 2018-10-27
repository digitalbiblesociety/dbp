<?php

namespace App\Models\Language;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFilesetConnection;
use App\Models\Bible\Video;
use App\Models\Country\CountryLanguage;
use App\Models\Country\CountryRegion;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country\Country;
use App\Models\Resource\Resource;

/**
 * App\Models\Language\Language
 *
 * @mixin \Eloquent
 * @property-read Alphabet[] $alphabets
 * @property-read Bible[] $bibleCount
 * @property-read Bible[] $bibles
 * @property-read Country[] $countries
 * @property-read LanguageCode[] $codes
 * @property-read LanguageDialect[] $dialects
 * @property-read LanguageTranslation $currentTranslation
 * @property-read LanguageClassification[] $classifications
 * @property-read Video[] $films
 * @property-read AlphabetFont[] $fonts
 * @property-read LanguageCode $iso639_2
 * @property-read Language[] $languages
 * @property-read LanguageDialect $parent
 * @property-read Country|null $primaryCountry
 * @property-read CountryRegion $region
 * @property-read \App\Models\Resource\Resource[] $resources
 * @property-read LanguageTranslation[] $translations
 *
 * @property int $id
 * @property string|null $glotto_id
 * @property string|null $iso
 * @property string $iso2B
 * @property string $iso2T
 * @property string $name
 * @property string $maps
 * @property string $development
 * @property string $use
 * @property string $location
 * @property string $area
 * @property string $population
 * @property string $notes
 * @property string $typology
 * @property string $description
 * @property string $latitude
 * @property string $longitude
 * @property string $status
 * @property string $country_id
 *
 * @method static Language whereId($value)
 * @method static Language whereGlottoId($value)
 * @method static Language whereIso($value)
 * @method static whereIso2b($value)
 * @method static whereIso2t($value)
 * @method static whereName($value)
 * @method static whereMaps($value)
 * @method static whereDevelopment($value)
 * @method static whereUse($value)
 * @method static whereLocation($value)
 * @method static whereArea($value)
 * @method static wherePopulation($value)
 * @method static whereNotes($value)
 * @method static whereTypology($value)
 * @method static whereDescription($value)
 * @method static whereLatitude($value)
 * @method static whereLongitude($value)
 * @method static whereStatus($value)
 * @method static whereCountryId($value)
 *
 * @OA\Schema (
 *     type="object",
 *     description="Language",
 *     title="Language",
 *     @OA\Xml(name="Language")
 * )
 *
 */
class Language extends Model
{
	protected $connection = 'dbp';
	public $table = 'languages';
    protected $hidden = ['pivot'];
    protected $fillable = ['glotto_id','iso','name','maps','development','use','location','area','population','population_notes','notes','typology','writing','description','family_pk','father_pk','child_dialect_count','child_family_count','child_language_count','latitude','longitude','pk','status_id','status_notes','country_id','scope'];

	/**
	 * ID
	 *
	 * @OA\Property(
	 *     title="id",
	 *     description="The incrementing ID for the language",
	 *     type="integer"
	 * )
	 *
	 */
	protected $id;

	/**
	 * Glotto ID
	 *
	 * @OA\Property(
	 *     title="glotto_id",
	 *     description="The glottolog ID for the language",
	 *     type="string",
	 *     @OA\ExternalDocumentation(
	 *         description="For more info please refer to the Glottolog",
	 *         url="http://glottolog.org/"
	 *     ),
	 * )
	 *
	 *
	 */
	protected $glotto_id;

	/**
	 * Iso
	 *
	 * @OA\Property(
	 *     title="iso",
	 *     description="The iso 639-3 for the language",
	 *     type="string",
	 *     @OA\ExternalDocumentation(
	 *         description="For more info",
	 *         url="https://en.wikipedia.org/wiki/ISO_639-3"
	 *     ),
	 * )
	 *
	 *
	 */
	protected $iso;

	/**
	 * iso2B
	 *
	 * @OA\Property(
	 *     title="iso 2b",
	 *     description="The iso 639-2, B variant for the language",
	 *     type="integer"
	 * )
	 *
	 */
	protected $iso2B;

	/**
	 * iso2T
	 *
	 * @OA\Property(
	 *     title="iso 2t",
	 *     description="The iso 639-2, T variant for the language",
	 *     type="integer"
	 * )
	 *
	 */
	protected $iso2T;

	/**
	 * @OA\Property(
	 *     title="iso1",
	 *     description="The iso 639-1 for the language",
	 *     type="integer"
	 * )
	 *
	 */
	protected $iso1;

	/**
	 * @OA\Property(
	 *     title="Name",
	 *     description="The name of the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $name;

	/**
	 * @OA\Property(
	 *     title="Maps",
	 *     description="The general area where the language can be found",
	 *     type="string"
	 * )
	 *
	 */
	protected $maps;

	/**
	 * @OA\Property(
	 *     title="Development",
	 *     description="The development of the growth of the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $development;

	/**
	 * @OA\Property(
	 *     title="use",
	 *     description="The use of the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $use;

	/**
	 * @OA\Property(
	 *     title="Location",
	 *     description="The location of the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $location;

	/**
	 * @OA\Property(
	 *     title="Area",
	 *     description="The area of the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $area;

	/**
	 * @OA\Property(
	 *     title="Population",
	 *     description="The estimated number of people that speak the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $population;

	/**
	 * @OA\Property(
	 *     title="Population",
	 *     description="Any notes regarding the estimated number of people",
	 *     type="string"
	 * )
	 *
	 */
	protected $population_notes;

	/**
	 * @OA\Property(
	 *     title="Notes",
	 *     description="Any notes regarding the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $notes;

	/**
	 * @OA\Property(
	 *     title="Typology",
	 *     description="The language's Typology",
	 *     type="string"
	 * )
	 *
	 */
	protected $typology;

	/**
	 * @OA\Property(
	 *     title="Typology",
	 *     description="The description of the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $description;

	/**
	 * @OA\Property(
	 *     title="Latitude",
	 *     description="A generalized latitude for the location of the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $latitude;

	/**
	 * @OA\Property(
	 *     title="Longitude",
	 *     description="A generalized longitude for the location of the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $longitude;

	/**
	 * @OA\Property(
	 *     title="Status",
	 *     description="A status of the language",
	 *     type="string"
	 * )
	 *
	 */
	protected $status;

	/**
	 * @OA\Property(
	 *     title="country_id",
	 *     description="The primary country where the language is spoken",
	 *     type="string"
	 * )
	 *
	 */
	protected $country_id;

	public function population()
	{
		return CountryLanguage::where('language_id',$this->id)->select('language_id','population')->count();
	}

    public function alphabets()
    {
        return $this->belongsToMany(Alphabet::class,'alphabet_language','script','id')->distinct();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->hasMany(LanguageTranslation::class,'language_source_id','id')->orderBy('priority', 'desc');
    }

	public function translation()
	{
		return $this->hasOne(LanguageTranslation::class,'language_source_id','id')->orderBy('priority', 'desc')->select(['language_source_id','name','priority']);
	}

	public function autonym()
	{
		return $this->hasOne(LanguageTranslation::class,'language_source_id')->where('vernacular', 1);
	}

    public function currentTranslation()
    {
        return $this->hasOne(LanguageTranslation::class,'language_source_id')->where('language_translation_id', $GLOBALS['i18n_id']);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_language');
    }

    public function primaryCountry()
    {
        return $this->belongsTo(Country::class,'country_id','id','countries');
    }

    public function region()
    {
    	return $this->hasOne(CountryRegion::class,'country_id');
    }

    public function fonts()
    {
        return $this->hasMany(AlphabetFont::class);
    }

    public function bibles()
    {
        return $this->hasMany(Bible::class);
    }

	public function filesets()
	{
		return $this->hasManyThrough(BibleFilesetConnection::class,Bible::class,'language_id','bible_id','id','id');
	}

    public function bibleCount()
    {
	    return $this->hasMany(Bible::class);
    }

    public function resources()
    {
    	return $this->hasMany(Resource::class)->has('links');
    }

    public function films()
    {
        return $this->hasMany(Video::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    public function codes()
    {
        return $this->hasMany(LanguageCode::class, 'language_id','id');
    }

    public function iso639_2()
    {
        return $this->hasOne(LanguageCode::class);
    }

    public function classifications()
    {
        return $this->hasMany(LanguageClassification::class);
    }

    public function dialects()
    {
        return $this->hasMany(LanguageDialect::class,'language_id','id');
    }

	public function parent()
	{
		return $this->hasOne(LanguageDialect::class,'dialect_id', 'id');
	}

}
