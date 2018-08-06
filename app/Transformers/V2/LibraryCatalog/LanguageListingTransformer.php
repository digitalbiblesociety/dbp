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
		    case "v2_library_volumeLanguage": {
			    return [
				    "language_name"             => $language->autonym ?? "",
				    "english_name"              => $language->name ?? "",
				    "language_code"             => strtoupper($language->iso),
				    "language_iso"              => $language->iso,
				    "language_iso_2B"           => $language->iso2B ?? "",
				    "language_iso_2T"           => $language->iso2T ?? "",
				    "language_iso_1"            => $language->iso1 ?? "",
				    "language_iso_name"         => $language->name ?? "",
				    "language_family_code"      => ($language->parent) ? $language->parent->autonym : strtoupper($language->iso),
				    "language_family_name"      => (($language->parent) ? $language->parent->autonym : $language->autonym) ?? "",
				    "language_family_english"   => (($language->parent) ? $language->parent->name : $language->name) ?? "",
				    "language_family_iso"       => $language->iso ?? "",
				    "language_family_iso_2B"    => (($language->parent) ? $language->parent->iso2B : $language->iso2B) ?? "",
				    "language_family_iso_2T"    => (($language->parent) ? $language->parent->iso2T : $language->iso2T) ?? "",
				    "language_family_iso_1"     => (($language->parent) ? $language->parent->iso1 : $language->iso1) ?? "",
				    "media"                     => ["text"],
				    "delivery"                  => ["mobile","web","subsplash"],
				    "resolution"                => []
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
				    'language_code'        => (string) strtoupper($language->iso),
				    'language_name'        => (string) @$language->autonym->name,
				    'english_name'         => (string) $language->name,
				    'language_iso'         => (string) $language->iso,
				    "language_iso_2B"      => (string) $language->iso2B,
				    "language_iso_2T"      => (string) $language->iso2T,
				    "language_iso_1"       => (string) $language->iso1,
				    'language_iso_name'    => (string) $language->name,
				    'language_family_code' => (string) $language->iso
			    ];
		    }
	    }
    }
}
