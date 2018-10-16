<?php

namespace App\Transformers\V2\LibraryCatalog;

use App\Models\Language\Language;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class LanguageListingTransformer extends BaseTransformer
{
	/**
	 * @OA\Schema (
	 *	type="array",
	 *	schema="v2_library_language",
	 *	description="The minimized language return for the all languages v2 route",
	 *	title="v2_library_language",
	 *	@OA\Xml(name="v2_library_language"),
	 *	@OA\Items(
	 *          @OA\Property(property="language_code",         ref="#/components/schemas/Language/properties/iso"),
	 *          @OA\Property(property="language_name",         ref="#/components/schemas/Language/properties/name"),
	 *          @OA\Property(property="english_name",          ref="#/components/schemas/Language/properties/name"),
	 *          @OA\Property(property="language_iso",          ref="#/components/schemas/Language/properties/iso"),
	 *          @OA\Property(property="language_iso_2B",       ref="#/components/schemas/Language/properties/iso2B"),
	 *          @OA\Property(property="language_iso_2T",       ref="#/components/schemas/Language/properties/iso2T"),
	 *          @OA\Property(property="language_iso_1",        ref="#/components/schemas/Language/properties/iso1"),
	 *          @OA\Property(property="language_iso_name",     ref="#/components/schemas/Language/properties/name"),
	 *          @OA\Property(property="language_family_code",  ref="#/components/schemas/Language/properties/iso")
	 *     )
	 *   )
	 * )
	 */
    public function transform(Language $language)
    {
	    switch($this->route) {
		    case 'v2_library_volumeLanguage': {
			    return [
				    'language_name'             => (string) $language->autonym,
				    'english_name'              => (string) $language->name,
				    'language_code'             => strtoupper($language->iso),
				    'language_iso'              => (string) $language->iso,
				    'language_iso_2B'           => (string) $language->iso2B,
				    'language_iso_2T'           => (string) $language->iso2T,
				    'language_iso_1'            => (string) $language->iso1,
				    'language_iso_name'         => (string) $language->name,
				    'language_family_code'      => ($language->parent) ? $language->parent->autonym : strtoupper($language->iso),
				    'language_family_name'      => (($language->parent) ? $language->parent->autonym : $language->autonym) ?? "",
				    'language_family_english'   => (($language->parent) ? $language->parent->name : $language->name) ?? "",
				    'language_family_iso'       => $language->iso ?? "",
				    'language_family_iso_2B'    => (($language->parent) ? $language->parent->iso2B : $language->iso2B) ?? "",
				    'language_family_iso_2T'    => (($language->parent) ? $language->parent->iso2T : $language->iso2T) ?? "",
				    'language_family_iso_1'     => (($language->parent) ? $language->parent->iso1 : $language->iso1) ?? "",
				    'media'                     => ["text"],
				    'delivery'                  => ["mobile","web","subsplash"],
				    'resolution'                => []
			    ];
		    }

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
		    case 'v2_country_lang': {
			    return [
				    'id'                   => (string) $language->id,
				    'lang_code'            => (string) $language->iso,
				    'region'               => (string) $language->area,
				    'country_primary'      => strtoupper($language->country_id),
				    'lang_id'              => strtoupper($language->iso),
				    'iso_language_code'    => strtoupper($language->iso),
				    'regional_lang_name'   => $language->autonym ?? $language->name,
				    'family_id'            => strtoupper($language->iso),
				    'primary_country_name' => (string) $language->primaryCountry->name,
				    'country_image'        => 'https://dbp-mcdn.s3.us-west-2.amazonaws.com/flags/full/80x60/'.strtolower($language->country_id).'.png',
				    'country_additional'   => strtoupper($language->countries->pluck('id')->implode(': '))
			    ];
		    }

		    /**
		     * @OA\Schema (
		     *	type="array",
		     *	schema="v2_library_volumeLanguageFamily",
		     *	description="",
		     *	title="v2_library_volumeLanguageFamily",
		     *	@OA\Xml(name="v2_library_volumeLanguageFamily"),
		     *	@OA\Items(
		     *          @OA\Property(property="language_family_code",      ref="#/components/schemas/Language/properties/iso"),
		     *          @OA\Property(property="language_family_name",      ref="#/components/schemas/Language/properties/name"),
		     *          @OA\Property(property="language_family_english",   ref="#/components/schemas/Language/properties/name"),
		     *          @OA\Property(property="language_family_iso",       ref="#/components/schemas/Language/properties/iso"),
		     *          @OA\Property(property="language_family_iso_2B",    ref="#/components/schemas/Language/properties/iso2B"),
		     *          @OA\Property(property="language_family_iso_2T",    ref="#/components/schemas/Language/properties/iso2T"),
		     *          @OA\Property(property="language_family_iso_1",     ref="#/components/schemas/Language/properties/iso1"),
		     *          @OA\Property(property="language",                  @OA\Schema(type="array")),
		     *          @OA\Property(property="media",                     @OA\Schema(type="array")),
		     *          @OA\Property(property="delivery",                  @OA\Schema(type="array")),
		     *          @OA\Property(property="resolution",                @OA\Schema(type="array")),
		     *     )
		     *   )
		     * )
		     */
		    case "v2_library_volumeLanguageFamily": {
		    	return [
			        "language_family_code"    => (string) strtoupper($language->iso),
                    "language_family_name"    => (string) $language->name,
                    "language_family_english" => (string) $language->name,
                    "language_family_iso"     => (string) $language->iso,
                    "language_family_iso_2B"  => (string) $language->iso2B,
                    "language_family_iso_2T"  => (string) $language->iso2T,
                    "language_family_iso_1"   => (string) $language->iso1,
                    "language"                => [
	                    (string) strtoupper($language->iso)
                    ],
                    "media"                   => ["video","text"],
                    "delivery"                => ["mobile","web","subsplash"],
                    "resolution"              => ["lo"]
				];
		    }

		    default: {
			    return [
				    'language_code'        => (string) @strtoupper($language->iso),
				    'language_name'        => (string) @$language->name,
				    'english_name'         => (string) @$language->name,
				    'language_iso'         => (string) @$language->iso,
				    "language_iso_2B"      => (string) @$language->iso2B,
				    "language_iso_2T"      => (string) @$language->iso2B,
				    "language_iso_1"       => (string) @$language->iso2B,
				    'language_iso_name'    => (string) @$language->name,
				    'language_family_code' => (string) @$language->iso
			    ];
		    }
	    }
    }
}
