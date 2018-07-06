<?php

namespace App\Transformers\V2\LibraryCatalog;

use App\Models\Language\Language;
use League\Fractal\TransformerAbstract;

class LanguageListingTransformer extends TransformerAbstract
{
	/**
	 * @OAS\Schema (
	 *	type="array",
	 *	schema="v2_library_language",
	 *	description="The minimized language return for the all languages v2 route",
	 *	title="v2_library_language",
	 *	@OAS\Xml(name="v2_library_language"),
	 *	@OAS\Items(
	 *          @OAS\Property(property="language_code",         ref="#/components/schemas/Language/properties/iso"),
	 *          @OAS\Property(property="language_name",         ref="#/components/schemas/Language/properties/name"),
	 *          @OAS\Property(property="english_name",          ref="#/components/schemas/Language/properties/name"),
	 *          @OAS\Property(property="language_iso",          ref="#/components/schemas/Language/properties/iso"),
	 *          @OAS\Property(property="language_iso_2B",       ref="#/components/schemas/Language/properties/iso2B"),
	 *          @OAS\Property(property="language_iso_2T",       ref="#/components/schemas/Language/properties/iso2T"),
	 *          @OAS\Property(property="language_iso_1",        ref="#/components/schemas/Language/properties/iso1"),
	 *          @OAS\Property(property="language_iso_name",     ref="#/components/schemas/Language/properties/name"),
	 *          @OAS\Property(property="language_family_code",  ref="#/components/schemas/Language/properties/iso")
	 *     )
	 *   )
	 * )
	 */
    public function transform(Language $language)
    {
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
