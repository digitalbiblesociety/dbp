<?php

namespace App\Models\Language;

use App\Models\Bible\Bible;
use App\Models\Bible\Video;
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
 * @property-read LanguageClassification[] $classifications
 * @property-read LanguageCode[] $codes
 * @property-read Country[] $countries
 * @property-read LanguageTranslation $currentTranslation
 * @property-read LanguageDialect[] $dialects
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
 * @OAS\Schema (
 *     type="object",
 *     description="Language",
 *     title="Language",
 *     @OAS\Xml(name="Language")
 * )
 *
 */
class Language extends Model
{

	public $table = "languages";
    public $primaryKey = 'id';

    protected $hidden = ["pivot"];
    protected $fillable = ['glotto_id','iso','name','level','maps','development','use','location','area','population','population_notes','notes','typology','writing','description','family_pk','father_pk','child_dialect_count','child_family_count','child_language_count','latitude','longitude','pk','status','country_id','scope'];

	/**
	 * ID
	 *
	 * @OAS\Property(
	 *     title="id",
	 *     description="The incrementing ID for the language",
	 *     type="integer"
	 * )
	 *
	 * @method static Language whereId($value)
	 * @property int $id
	 */
	protected $id;

	/**
	 * Glotto ID
	 *
	 * @OAS\Property(
	 *     title="glotto_id",
	 *     description="The glottolog ID for the language",
	 *     type="string",
	 *     @OAS\ExternalDocumentation(
	 *         description="For more info please refer to the Glottolog",
	 *         url="http://glottolog.org/"
	 *     ),
	 * )
	 *
	 * @method static Language whereGlottoId($value)
	 * @property string|null $glotto_id
	 *
	 */
	protected $glotto_id;

	/**
	 * Iso
	 *
	 * @OAS\Property(
	 *     title="iso",
	 *     description="The iso 639-3 for the language",
	 *     type="string",
	 *     @OAS\ExternalDocumentation(
	 *         description="For more info",
	 *         url="https://en.wikipedia.org/wiki/ISO_639-3"
	 *     ),
	 * )
	 *
	 * @property string|null $iso
	 * @method static Language whereIso($value)
	 *
	 */
	protected $iso;

	/**
	 * iso2B
	 *
	 * @OAS\Property(
	 *     title="iso 2b",
	 *     description="The iso 639-2, B variant for the language",
	 *     type="integer"
	 * )
	 *
	 * @property string $iso2B
	 * @method static whereIso2b($value)
	 */
	protected $iso2B;

	/**
	 * iso2T
	 *
	 * @OAS\Property(
	 *     title="iso 2t",
	 *     description="The iso 639-2, T variant for the language",
	 *     type="integer"
	 * )
	 *
	 * @property string $iso2T
	 * @method static whereIso2t($value)
	 */
	protected $iso2T;

	/**
	 * @OAS\Property(
	 *     title="iso1",
	 *     description="The iso 639-1 for the language",
	 *     type="integer"
	 * )
	 *
	 * @property string $iso2T
	 * @method static whereIso2t($value)
	 */
	protected $iso1;

	/**
	 * @OAS\Property(
	 *     title="Name",
	 *     description="The name of the language",
	 *     type="string"
	 * )
	 *
	 * @property string $name
	 * @method static whereName($value)
	 */
	protected $name;

	/**
	 * @OAS\Property(
	 *     title="Name",
	 *     description="The name of the language in the vernacular of that language",
	 *     type="string"
	 * )
	 *
	 * @property string $autonym
	 * @method static whereAutonym($value)
	 */
	protected $autonym;

	/**
	 * @OAS\Property(
	 *     title="Maps",
	 *     description="The general area where the language can be found",
	 *     type="string"
	 * )
	 *
	 * @property string $maps
	 * @method static whereMaps($value)
	 */
	protected $maps;

	/**
	 * @OAS\Property(
	 *     title="Development",
	 *     description="The development of the growth of the language",
	 *     type="string"
	 * )
	 *
	 * @property string $development
	 * @method static whereDevelopment($value)
	 */
	protected $development;

	/**
	 * @OAS\Property(
	 *     title="use",
	 *     description="The use of the language",
	 *     type="string"
	 * )
	 *
	 * @property string $use
	 * @method static whereUse($value)
	 */
	protected $use;

	/**
	 * @OAS\Property(
	 *     title="Location",
	 *     description="The location of the language",
	 *     type="string"
	 * )
	 *
	 * @property string $location
	 * @method static whereLocation($value)
	 */
	protected $location;

	/**
	 * @OAS\Property(
	 *     title="Area",
	 *     description="The area of the language",
	 *     type="string"
	 * )
	 *
	 * @property string $area
	 * @method static whereArea($value)
	 */
	protected $area;

	/**
	 * @OAS\Property(
	 *     title="Population",
	 *     description="The estimated number of people that speak the language",
	 *     type="string"
	 * )
	 *
	 * @property string $population
	 * @method static wherePopulation($value)
	 */
	protected $population;

	/**
	 * @OAS\Property(
	 *     title="Population",
	 *     description="Any notes regarding the estimated number of people",
	 *     type="string"
	 * )
	 *
	 * @property string $population
	 * @method static wherePopulation($value)
	 */
	protected $population_notes;

	/**
	 * @OAS\Property(
	 *     title="Notes",
	 *     description="Any notes regarding the language",
	 *     type="string"
	 * )
	 *
	 * @property string $notes
	 * @method static whereNotes($value)
	 */
	protected $notes;

	/**
	 * @OAS\Property(
	 *     title="Typology",
	 *     description="The language's Typology",
	 *     type="string"
	 * )
	 *
	 * @property string $typology
	 * @method static whereTypology($value)
	 */
	protected $typology;

	/**
	 * @OAS\Property(
	 *     title="Typology",
	 *     description="The language's script",
	 *     type="string"
	 * )
	 *
	 * @property string $writing
	 * @method static whereWriting($value)
	 */
	protected $writing;

	/**
	 * @OAS\Property(
	 *     title="Typology",
	 *     description="The description of the language",
	 *     type="string"
	 * )
	 *
	 * @property string $description
	 * @method static whereDescription($value)
	 */
	protected $description;

	/**
	 * @OAS\Property(
	 *     title="Latitude",
	 *     description="A generalized latitude for the location of the language",
	 *     type="string"
	 * )
	 *
	 * @property string $latitude
	 * @method static whereLatitude($value)
	 */
	protected $latitude;

	/**
	 * @OAS\Property(
	 *     title="Longitude",
	 *     description="A generalized longitude for the location of the language",
	 *     type="string"
	 * )
	 *
	 * @property string $longitude
	 * @method static whereLongitude($value)
	 */
	protected $longitude;

	/**
	 * @OAS\Property(
	 *     title="Status",
	 *     description="A status of the language",
	 *     type="string"
	 * )
	 *
	 * @property string $status
	 * @method static whereStatus($value)
	 */
	protected $status;

	/**
	 * @OAS\Property(
	 *     title="country_id",
	 *     description="The primary country where the language is spoken",
	 *     type="string"
	 * )
	 *
	 * @property string $country_id
	 * @method static whereCountryId($value)
	 */
	protected $country_id;

    public function alphabets()
    {
        return $this->BelongsToMany(Alphabet::class,'alphabet_language','script','id')->distinct();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->HasMany(LanguageTranslation::class,'language_source','id')->orderBy('priority', 'desc');
    }

	public function translation()
	{
		return $this->HasOne(LanguageTranslation::class,'language_source','id')->orderBy('priority', 'desc')->select(['language_source','name','priority']);
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

    public function resources()
    {
    	return $this->hasMany(Resource::class,'iso','iso')->has('links');
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
