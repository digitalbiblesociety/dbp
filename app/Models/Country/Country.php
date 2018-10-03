<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

use App\Models\Language\Language;
use App\Models\Country\FactBook\CountryEthnicity;
use App\Models\Country\FactBook\CountryCommunication;
use App\Models\Country\FactBook\CountryEconomy;
use App\Models\Country\FactBook\CountryEnergy;
use App\Models\Country\FactBook\CountryGeography;
use App\Models\Country\FactBook\CountryGovernment;
use App\Models\Country\FactBook\CountryIssues;
use App\Models\Country\FactBook\CountryPeople;
use App\Models\Country\FactBook\CountryReligion;
use App\Models\Country\FactBook\CountryTransportation;

/**
 * App\Models\Country\Country
 *
 * @property-read Language[] $languages
 * @property-read CountryRegion[] $regions
 * @property-read CountryTranslation[] $translations
 * @property-read Language[] $languagesFiltered
 * @property-read CountryTranslation $translation
 * @property-read CountryGeography $geography
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="Country",
 *     title="Country",
 *     @OA\Xml(name="Country")
 * )
 *
 */
class Country extends Model
{
	protected $connection = 'dbp';
    protected $table = 'countries';
    protected $hidden = ['pivot','created_at','updated_at','introduction'];
    public $incrementing = false;
    public $keyType = 'string';

	public function scopeExclude($query,array $value)
	{
		return $query->select( array_diff(['id', 'iso_a3', 'continent', 'name', 'introduction','fips'], $value) );
	}

	/**
	 * @OA\Property(
	 *     title="Country Iso 3166-1",
	 *     description="The Country ID for the country aligning with the ISO 3166-1 standard",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="AD",
	 *     @OA\ExternalDocumentation(
	 *         description="For more info please refer to the Iso Registration Authority",
	 *         url="https://www.iso.org/iso-3166-country-codes.html"
	 *     ),
	 * )
	 *
	 * @property string $id
	 * @method static Country whereId($value)
	 *
	 */
	protected $id;

	/**
	 * @OA\Property(
	 *     title="Country Iso 3166-3",
	 *     description="The Country iso for the country aligning with the ISO 3166-3 standard",
	 *     type="string",
	 *     minLength=3,
	 *     maxLength=3,
	 *     example="AND",
	 *     @OA\ExternalDocumentation(
	 *         description="For more info please refer to the Iso Wiki",
	 *         url="https://en.wikipedia.org/wiki/ISO_3166-3"
	 *     ),
	 * )
	 *
	 * @property string $iso_a3
	 * @method static Country whereIsoA3($value)
	 *
	 */
	protected $iso_a3;

	/**
	 * @OA\Property(
	 *     title="FIPS ID",
	 *     description="The Country id for the country aligning with the FIPS standard of the United Nations",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="AN",
	 *     @OA\ExternalDocumentation(
	 *         description="For more info please refer to the FIPS Wiki",
	 *         url="https://en.wikipedia.org/wiki/List_of_FIPS_country_codes"
	 *     ),
	 * )
	 *
	 * @property string $fips
	 * @method static Country whereFips($value)
	 *
	 */
	protected $fips;

	/**
	 * @OA\Property(
	 *     title="continent ID",
	 *     description="The continent code for the country",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @property string $continent
	 * @method static Country whereContinent($value)
	 *
	 */
	protected $continent;

	/**
	 * @OA\Property(
	 *     title="Country Name",
	 *     description="The name for the country in English",
	 *     type="string",
	 *     maxLength=191,
	 *     example="Andorra"
	 * )
	 *
	 * @property string $name
	 * @method static Country whereName($value)
	 *
	 */
	protected $name;


	/**
	 * @OA\Property(
	 *     title="Country introduction",
	 *     description="A brief description of the country in English",
	 *     type="string",
	 *     example="The landlocked Principality of Andorra is one of the smallest states in Europe, nestled high in the Pyrenees..."
	 * )
	 *
	 * @property string $introduction
	 * @method static Country whereIntroduction($value)
	 *
	 */
	protected $introduction;


	/*
     * @property Carbon $created_at
     * @property Carbon $updated_at
	 * @method static Country whereCreatedAt($value)
	 * @method static Country whereUpdatedAt($value)
	 */
	protected $created_at;
	protected $updated_at;

    public function translations()
    {
    	return $this->hasMany(CountryTranslation::class);
    }

    public function currentTranslation()
    {
	    return $this->hasOne(CountryTranslation::class,'country_id','id')->where('language_id',$GLOBALS['i18n_id']);
    }
/*
 *	public function vernacularTranslation()
 *	{
 *		return $this->HasOne(CountryTranslation::class,'country_id','id')->where('language_id', $this->primary_language_id);
 *	}
 */
    public function languages()
    {
        return $this->belongsToMany(Language::class)->distinct();
    }

	public function languagesFiltered()
	{
		return $this->belongsToMany(Language::class)->distinct()->select(['id','iso','name']);
	}

    public function regions()
    {
    	return $this->hasMany(CountryRegion::class);
    }

    public function maps()
    {
    	return $this->hasMany(CountryMap::class);
    }

    // Joshua Project

	public function joshuaProject()
	{
		return $this->hasOne(JoshuaProject::class);
	}


    // World Factbook

	public function communications()
	{
		return $this->hasOne(CountryCommunication::class);
	}
	public function economy()
	{
		return $this->hasOne(CountryEconomy::class);
	}
	public function energy()
	{
		return $this->hasOne(CountryEnergy::class);
	}
	public function geography()
	{
		return $this->hasOne(CountryGeography::class);
	}
	public function government()
	{
		return $this->hasOne(CountryGovernment::class);
	}
	public function issues()
	{
		return $this->hasOne(CountryIssues::class);
	}
	public function people()
	{
		return $this->hasOne(CountryPeople::class);
	}
	public function ethnicities()
	{
		return $this->hasOne(CountryEthnicity::class);
	}
	public function religions()
	{
		return $this->hasOne(CountryReligion::class);
	}
	public function transportation()
	{
		return $this->hasOne(CountryTransportation::class);
	}


}