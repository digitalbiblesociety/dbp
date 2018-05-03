<?php

namespace App\Models\Country;

use App\Models\Bible\Bible;
use App\Models\Country\FactBook\CountryGeography;
use App\Models\Language\LanguageTranslation;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

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
 * @OAS\Schema (
 *     type="object",
 *     description="Country",
 *     title="Country",
 *     @OAS\Xml(name="Country")
 * )
 *
 */
class Country extends Model
{
    protected $table = 'countries';
    protected $hidden = ["pivot","created_at","updated_at"];
    public $incrementing = false;
    public $keyType = 'string';

	/**
	 * @OAS\Property(
	 *     title="Country Iso 3166-1",
	 *     description="The Country ID for the country aligning with the ISO 3166-1 standard",
	 *     format="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="AD",
	 *     @OAS\ExternalDocumentation(
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
	 * @OAS\Property(
	 *     title="Country Iso 3166-3",
	 *     description="The Country iso for the country aligning with the ISO 3166-3 standard",
	 *     format="string",
	 *     minLength=3,
	 *     maxLength=3,
	 *     example="AND",
	 *     @OAS\ExternalDocumentation(
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
	 * @OAS\Property(
	 *     title="FIPS ID",
	 *     description="The Country id for the country aligning with the FIPS standard of the United Nations",
	 *     format="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="AN",
	 *     @OAS\ExternalDocumentation(
	 *         description="For more info please refer to the FIPS Wiki",
	 *         url="https://en.wikipedia.org/wiki/List_of_FIPS_country_codes"
	 *     ),
	 * )
	 *
	 * @property string $continent
	 * @method static Country whereFips($value)
	 *
	 */
	protected $fips;

	/**
	 * @OAS\Property(
	 *     title="continent ID",
	 *     description="The continent code for the country",
	 *     format="string",
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
	 * @OAS\Property(
	 *     title="Country Name",
	 *     description="The name for the country in English",
	 *     format="string",
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
	 * @OAS\Property(
	 *     title="Country introduction",
	 *     description="A brief description of the country in English",
	 *     format="string",
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

    public function translations($iso = null)
    {
    	if(!isset($iso)) return $this->HasMany(CountryTranslation::class);
    	$language = Language::where('iso',$iso)->first();
    	return $this->HasMany(CountryTranslation::class)->where('language_id',$language->id);
    }

    public function translation()
    {
	    $language = Language::where('iso',\i18n::getCurrentLocale())->first();
	    return $this->HasOne(CountryTranslation::class)->where('language_id',$language->id);
    }

    public function languages()
    {
        return $this->BelongsToMany(Language::class)->distinct();
    }

	public function languagesFiltered()
	{
		return $this->BelongsToMany(Language::class)->distinct()->select(['iso','name']);
	}

    public function regions()
    {
    	return $this->HasMany(CountryRegion::class);
    }

    // World Factbook

    public function geography()
    {
    	return $this->hasOne(CountryGeography::class);
    }


}