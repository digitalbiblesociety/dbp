<?php

namespace App\Transformers;

use App\Models\Language\Language;
use League\Fractal\TransformerAbstract;
class LanguageTransformer extends TransformerAbstract
{

	/**
	 * LanguageTransformer constructor, sets the default version as 4
	 * and the default language as English but let's people customize
	 * their responses via parameters
	 *
	 * @param Request $request
	 */
	public function __construct()
	{
		$this->version = $_GET['v'] ?? 4;
		$this->iso = $_GET['iso'] ?? "eng";
		$this->continent = $_GET['continent'] ?? false;
	}


    public function transform(Language $language)
    {
    	switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($language);
		    case "2": return $this->transformForV2($language);
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
			$language->id,
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
		$route = \Route::currentRouteName();
		switch($route) {
			case "v2_library_volumeLanguage": {
				return [
					"language_name"             => $language->autonym,
                    "english_name"              => $language->name,
                    "language_code"             => $language->iso,
                    "language_iso"              => $language->iso,
                    "language_iso_2B"           => $language->iso2B,
                    "language_iso_2T"           => $language->iso2T,
                    "language_iso_1"            => $language->iso1,
                    "language_iso_name"         => $language->name,
					"language_family_code"      => $language->parent->language->iso,
					"language_family_name"      => $language->parent->language->autonym,
					"language_family_english"   => $language->parent->language->name,
					"language_family_iso"       => $language->parent->language->iso,
					"language_family_iso_2B"    => $language->parent->language->iso2B,
					"language_family_iso_2T"    => $language->parent->language->iso2T,
					"language_family_iso_1"     => $language->parent->languagew->iso1,
                    "media"                     => ["text"],
                    "delivery"                  => ["mobile","web","subsplash"],
                    "resolution"                => []
				];
			}

			case "v2_library_volumeLanguageFamily": {
				return [
					"language_family_code"    => $language->iso ?? null,
					"language_family_name"    => $language->autonym ?? null,
					"language_family_english" => $language->name ?? null,
					"language_family_iso"     => $language->iso ?? null,
					"language_family_iso_2B"  => $language->iso2B,
					"language_family_iso_2T"  => $language->iso2T,
					"language_family_iso_1"   => $language->iso1,
					"language"                => $language->dialects->pluck('childLanguage.iso'),
					"media"                   => ["text"],
					"delivery"                => ["mobile","web","subsplash"],
					"resolution"              => []
				];
			}

			case "v2_country_lang": {
				$img_type = checkParam('img_type');
				$img_size = "_".checkParam('img_size');
				if($img_type == "svg") $img_size = "";
				return [
					"id"                   => $language->id,
                    "lang_code"            => $language->iso,
                    "region"               => $language->primaryCountry->regions->first()->name,
                    "country_primary"      => $language->primaryCountry->id,
                    "lang_id"              => $language->iso,
                    "iso_language_code"    => $language->iso,
                    "regional_lang_name"   => $language->autonym ?? $language->name,
                    "family_id"            => $language->iso,
                    "primary_country_name" => $language->primaryCountry->name,
					"country_image"        => url("/img/flags/".$language->primaryCountry->id.$img_size.'.'.$img_type)
				];
			}

			default: {
				return [
					'language_code'        => $language->iso ?? '',
					'language_name'        => $language->autonym ?? '',
					'english_name'         => $language->name ?? '',
					'language_iso'         => $language->iso ?? '',
					"language_iso_2B"      => $language->iso2B,
					"language_iso_2T"      => $language->iso2T,
					"language_iso_1"       => $language->iso1,
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
		if(isset($_GET['full'])) {
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
