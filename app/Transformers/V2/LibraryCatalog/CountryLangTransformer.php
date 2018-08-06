<?php

namespace App\Transformers;

use App\Models\Language\Language;
use League\Fractal\TransformerAbstract;

class CountryLangTransformer extends TransformerAbstract
{
	/**
	 * @OA\Schema (
	 *	type="array",
	 *	schema="v2_country_lang",
	 *	description="The v2_country_lang response",
	 *	title="v2_country_lang",
	 *	@OA\Xml(name="v2_country_lang"),
	 *	@OA\Items(
	 *          @OA\Property(property="id",                    ref="#/components/schemas/Language/properties/id"),
	 *          @OA\Property(property="lang_code",             ref="#/components/schemas/Language/properties/iso"),
	 *          @OA\Property(property="region",                ref="#/components/schemas/Language/properties/area"),
	 *          @OA\Property(property="country_primary",       ref="#/components/schemas/Language/properties/country_id"),
	 *          @OA\Property(property="lang_id",               ref="#/components/schemas/Language/properties/iso2B"),
	 *          @OA\Property(property="iso_language_code",     ref="#/components/schemas/Language/properties/iso2T"),
	 *          @OA\Property(property="regional_lang_name",    ref="#/components/schemas/Language/properties/iso1"),
	 *          @OA\Property(property="family_id",             ref="#/components/schemas/Language/properties/name"),
	 *          @OA\Property(property="primary_country_name",  ref="#/components/schemas/Language/properties/iso2T"),
	 *          @OA\Property(property="country_image",         @OA\Schema(type="string",example="https://cdn.bible.build/img/flags/full/80X60/in.png")),
	 *          @OA\Property(property="country_additional",    @OA\Schema(type="string",example="BM: CH: CN: MM",description="The country names are delimited by both a colon and a space"))
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
