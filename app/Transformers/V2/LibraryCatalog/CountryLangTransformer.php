<?php

namespace App\Transformers;

use App\Models\Language\Language;
use League\Fractal\TransformerAbstract;

class CountryLangTransformer extends TransformerAbstract
{
	/**
	 * @OAS\Schema (
	 *	type="array",
	 *	schema="v2_country_lang",
	 *	description="The v2_country_lang response",
	 *	title="v2_country_lang",
	 *	@OAS\Xml(name="v2_country_lang"),
	 *	@OAS\Items(
	 *          @OAS\Property(property="id",                    ref="#/components/schemas/Language/properties/id"),
	 *          @OAS\Property(property="lang_code",             ref="#/components/schemas/Language/properties/iso"),
	 *          @OAS\Property(property="region",                ref="#/components/schemas/Language/properties/area"),
	 *          @OAS\Property(property="country_primary",       ref="#/components/schemas/Language/properties/country_id"),
	 *          @OAS\Property(property="lang_id",               ref="#/components/schemas/Language/properties/iso2B"),
	 *          @OAS\Property(property="iso_language_code",     ref="#/components/schemas/Language/properties/iso2T"),
	 *          @OAS\Property(property="regional_lang_name",    ref="#/components/schemas/Language/properties/iso1"),
	 *          @OAS\Property(property="family_id",             ref="#/components/schemas/Language/properties/name"),
	 *          @OAS\Property(property="primary_country_name",  ref="#/components/schemas/Language/properties/iso2T"),
	 *          @OAS\Property(property="country_image",         @OAS\Schema(type="string",example="https://cdn.bible.build/img/flags/full/80X60/in.png")),
	 *          @OAS\Property(property="country_additional",    @OAS\Schema(type="string",example="BM: CH: CN: MM",description="The country names are delimited by both a colon and a space"))
	 *     )
	 *   )
	 * )
	 */
    public function transform(Language $language)
    {
	    return [
		    "id"                   => (string) $language->id,
		    "lang_code"            => (string) $language->iso,
		    "region"               => (string) $language->area,
		    "country_primary"      => (string) strtoupper($language->country_id),
		    "lang_id"              => (string) strtoupper($language->iso),
		    "iso_language_code"    => (string) strtoupper($language->iso),
		    "regional_lang_name"   => (string) $language->autonym ?? $language->name,
		    "family_id"            => (string) strtoupper($language->iso),
		    "primary_country_name" => (string) $language->primaryCountry->name,
		    "country_image"        => (string) url("https://cdn.bible.build/img/flags/full/80X60/".strtolower($language->country_id).'.png'),
		    "country_additional"   => (string) strtoupper($language->countries->pluck('id')->implode(': '))
	    ];
    }
}
