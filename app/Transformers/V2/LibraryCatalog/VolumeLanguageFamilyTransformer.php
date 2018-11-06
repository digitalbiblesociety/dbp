<?php

namespace App\Transformers\V2\LibraryCatalog;

use App\Models\Language\Language;
use App\Transformers\BaseTransformer;

class VolumeLanguageFamilyTransformer extends BaseTransformer
{
	/**
	 * A Fractal transformer.
	 *
	 * @param Language $language
	 * @return array
	 */
    public function transform(Language $language)
    {
	    return [
		    'language_family_code'    => strtoupper($language->iso),
		    'language_family_name'    => (string) $language->name,
		    'language_family_english' => (string) $language->name,
		    'language_family_iso'     => (string) $language->iso,
		    'language_family_iso_2B'  => (string) $language->iso2B,
		    'language_family_iso_2T'  => (string) $language->iso2T,
		    'language_family_iso_1'   => (string) $language->iso1,
		    'language'                => [strtoupper($language->iso)],
		    'media'                   => ['text'],
		    'delivery'                => ['mobile', 'web', 'subsplash'],
		    'resolution'              => ['lo']
	    ];
    }
}
