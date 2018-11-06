<?php

namespace App\Transformers;

use App\Models\Language\Language;
use App\Transformers\BaseTransformer;

class VolumeLanguageListTransformer extends BaseTransformer
{

    public function transform(Language $language)
    {
        return [
	        'language_name'        => (string) $language->autonym,
	        'english_name'         => (string) $language->name,
	        'language_code'        => strtoupper($language->iso),
	        'language_iso'         => (string) $language->iso,
	        'language_iso_2B'      => (string) $language->iso2B,
	        'language_iso_2T'      => (string) $language->iso2T,
	        'language_iso_1'       => (string) $language->iso1,
	        'language_iso_name'    => (string) $language->name,
	        'language_family_code' => (string) $language->parent ? $language->parent->autonym : strtoupper($language->iso),
	        'language_family_name' => (string) $language->parent ? $language->parent->autonym : $language->autonym,
	        'language_family_english' => (string) $language->parent ? $language->parent->name : $language->name,
	        'language_family_iso'     => (string) $language->iso,
	        'language_family_iso_2B'  => (string) $language->parent ? $language->parent->iso2B : $language->iso2B,
	        'language_family_iso_2T'  => (string) $language->parent ? $language->parent->iso2T : $language->iso2T,
	        'language_family_iso_1'   => (string) $language->parent ? $language->parent->iso1 : $language->iso1,
	        'media'                   => ['text'],
	        'delivery'                => ['mobile', 'web', 'subsplash'],
	        'resolution'              => []
		];
    }
}
