<?php

namespace App\Transformers\V2\LibraryCatalog;

use App\Models\Language\Language;
use App\Transformers\BaseTransformer;

class VolumeLanguageFamilyTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Language $language)
    {
	    return [
		    "language_family_code"    => strtoupper($language->iso) ?? "",
		    "language_family_name"    => $language->name ?? "",
		    "language_family_english" => $language->name ?? "",
		    "language_family_iso"     => $language->iso ?? "",
		    "language_family_iso_2B"  => $language->iso2B ?? "",
		    "language_family_iso_2T"  => $language->iso2T ?? "",
		    "language_family_iso_1"   => $language->iso1 ?? "",
		    "language"                => [strtoupper($language->iso)],
		    "media"                   => ["text"],
		    "delivery"                => ["mobile","web","subsplash"],
		    "resolution"              => ["lo"]
	    ];
    }
}
