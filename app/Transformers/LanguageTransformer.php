<?php

namespace App\Transformers;

use App\Models\Language\Language;

class LanguageTransformer extends BaseTransformer
{
    public function transform(Language $language)
    {
    	switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($language);
		    case "2":
		    case "3": return $this->transformForV2($language);
		    case "4":
		    default: return $this->transformForV4($language);
	    }
    }

	/**
	 * @param Language $language
	 *
	 * @return array
	 */
	public function transformForDataTables(Language $language)
	{
		return [
			$language->alternativeNames->implode('name', ' '),
			'<a href="/languages/'.$language->id.'">'.$language->name.'</a>',
			$language->iso,
			$language->bibles_count
		];
	}

	/**
	 * @param Language $language
	 *
	 * @return array
	 */
	public function transformForV2(Language $language) {
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
					"language_family_english"   => (($language->parent) ? $language->parent->
						name : $language->name) ?? "",
					"language_family_iso"       => $language->iso ?? "",
					"language_family_iso_2B"    => (($language->parent) ? $language->parent->iso2B : $language->iso2B) ?? "",
					"language_family_iso_2T"    => (($language->parent) ? $language->parent->iso2T : $language->iso2T) ?? "",
					"language_family_iso_1"     => (($language->parent) ? $language->parent->iso1 : $language->iso1) ?? "",
                    "media"                     => ["text"],
                    "delivery"                  => ["mobile","web","subsplash"],
                    "resolution"                => []
				];
			}

			case "v2_library_volumeLanguageFamily": {
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

			case "v2_country_lang": {
				$img_type = checkParam('img_type', null, 'optional') ?? "svg";
				$img_size = "_".checkParam('img_size', null, 'optional') ?? "";
				if($img_type == "svg") $img_size = "";
				return [
					"id"                   => (string) $language->id,
                    "lang_code"            => $language->iso,
                    "region"               => $language->primaryCountry->regions->first()->name,
                    "country_primary"      => $language->primaryCountry->id,
                    "lang_id"              => $language->iso,
                    "iso_language_code"    => $language->iso,
                    "regional_lang_name"   => $language->autonym ?? $language->name,
                    "family_id"            => $language->iso,
                    "primary_country_name" => $language->primaryCountry->name,
					"country_image"        => url("/img/flags/".$language->primaryCountry->id.$img_size.'.'.$img_type),
					"country_additional"   => $language->countries->pluck('id')
				];
			}

			default: {
				return [
					'language_code'        => strtoupper($language->iso) ?? '',
					'language_name'        => $language->autonym ?? '',
					'english_name'         => $language->name ?? '',
					'language_iso'         => $language->iso ?? '',
					"language_iso_2B"      => $language->iso2B ?? '',
					"language_iso_2T"      => $language->iso2T ?? '',
					"language_iso_1"       => $language->iso1 ?? '',
					'language_iso_name'    => $language->name ?? '',
					'language_family_code' => $language->iso ?? ''
				];
			}

		}

	}

	/**
	 * @param Language $language
	 *
	 * @return array
	 */
	public function transformForV4(Language $language) {
		$full = checkParam('full', null, 'optional');
		if($full) {
			return [
				"id"                   => $language->id,
				"name"                 => $language->name,
				'autonym'              => ($language->autonym) ? $language->autonym->name : '',
                "glotto_id"            => $language->glotto_id,
                "iso"                  => $language->iso,
                "maps"                 => $language->maps,
                "area"                 => $language->area,
                "population"           => $language->population,
				"country_id"           => $language->country_id,
				'codes'                => $language->codes->pluck('code','source') ?? '',
				'alternativeNames'     => array_flatten($language->alternativeNames->ToArray()) ?? '',
				'dialects'             => $language->dialects->pluck('name') ?? '',
				'classifications'      => $language->classifications->pluck('name','classification_id') ?? '',
			];
		}
			return [
				'glotto_code'     => $language->id,
				'iso_code'        => $language->iso,
				'name'            => $language->name,
				'count_bible'     => $language->bibles_count
			];
	}

}
