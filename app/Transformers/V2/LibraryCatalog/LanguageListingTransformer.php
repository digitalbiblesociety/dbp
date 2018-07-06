<?php

namespace App\Transformers\V2\LibraryCatalog;

use App\Models\Language\Language;
use League\Fractal\TransformerAbstract;

class LanguageListingTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
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
