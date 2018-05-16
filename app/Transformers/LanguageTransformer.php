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
			'<a href="/languages/'.$language->id.'">'.$language->name.'</a>',
			$language->location,
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

			/**
			 * @OAS\Schema (
			*	type="array",
			*	schema="v2_country_lang",
			*	description="The v2_country_lang response",
			*	title="v2_country_lang",
			*	@OAS\Xml(name="v2_country_lang"),
			*	@OAS\Items(          @OAS\Property(property="id",                    ref="#/components/schemas/Language/properties/id"),
			 *          @OAS\Property(property="lang_code",             ref="#/components/schemas/Language/properties/iso"),
			 *          @OAS\Property(property="region",                ref="#/components/schemas/Language/properties/area"),
			 *          @OAS\Property(property="country_primary",       ref="#/components/schemas/Language/properties/country_id"),
			 *          @OAS\Property(property="lang_id",               ref="#/components/schemas/Language/properties/iso2B"),
			 *          @OAS\Property(property="iso_language_code",     ref="#/components/schemas/Language/properties/iso2T"),
			 *          @OAS\Property(property="regional_lang_name",    ref="#/components/schemas/Language/properties/iso1"),
			 *          @OAS\Property(property="family_id",             ref="#/components/schemas/Language/properties/name"),
			 *          @OAS\Property(property="primary_country_name",  ref="#/components/schemas/Language/properties/iso2T"),
			 *          @OAS\Property(property="country_image",         @OAS\Schema(type="string",example="https://cdn.bible.build/img/flags/full/80X60/in.png")),
			 *          @OAS\Property(property="country_additional",    @OAS\Schema(type="string",example="BM: CH: CN: MM",description="The country names are delimited by both a colon and a space"))
			 *     )
			 *   )
			 * )
			 */
			case "v2_country_lang": {
				return [
					"id"                   => (string) $language->id,
                    "lang_code"            => $language->iso,
                    "region"               => $language->area,
                    "country_primary"      => strtoupper($language->country_id),
                    "lang_id"              => strtoupper($language->iso),
                    "iso_language_code"    => strtoupper($language->iso),
                    "regional_lang_name"   => $language->autonym ?? $language->name,
                    "family_id"            => strtoupper($language->iso),
                    "primary_country_name" => $language->primaryCountry->name,
					"country_image"        => url("https://cdn.bible.build/img/flags/full/80X60/".strtolower($language->country_id).'.png'),
					"country_additional"   => strtoupper($language->countries->pluck('id')->implode(': '))
				];
			}

			/**
			 * @OAS\Schema (
			*	type="array",
			*	schema="v2_library_language",
			*	description="The minimized language return for the all languages v2 route",
			*	title="v2_library_language",
			*	@OAS\Xml(name="v2_library_language"),
			*	@OAS\Items(          @OAS\Property(property="language_code",         ref="#/components/schemas/Language/properties/iso"),
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
		/**
		 * @OAS\Response(
		 *   response="v4_languages.one",
		 *   description="The Full alphabet return for the single alphabet route",
		 *   @OAS\MediaType(
		 *     mediaType="application/json",
		 *     @OAS\Schema(ref="#/components/schemas/Language")
		 *   )
		 * )
		 */
		$full = checkParam('full', null, 'optional');
		if($full) {
			return [
				"id"                   => $language->id,
				"name"                 => $language->name,
				'autonym'              => ($language->autonym) ? $language->autonym->name : '',
                "glotto_id"            => $language->glotto_id,
				"bibles"               => $language->bibles,
                "iso"                  => $language->iso,
                "maps"                 => $language->maps,
                "area"                 => $language->area,
                "population"           => $language->population,
				"country_id"           => $language->country_id,
				'codes'                => $language->codes->pluck('code','source') ?? '',
				'alternativeNames'     => array_flatten($language->translations->pluck('name')->ToArray()) ?? '',
				'dialects'             => $language->dialects->pluck('name') ?? '',
				'classifications'      => $language->classifications->pluck('name','classification_id') ?? '',
				'resources'            => $language->resources
			];
		}

		/**
		 * @OAS\Response(
		 *   response="v4_languages.all",
		 *   description="The minimized language return for the single language route",
		 *   @OAS\MediaType(
		 *     mediaType="application/json",
		 *     @OAS\Schema(ref="#/components/schemas/Language")
		 *   )
		 * )
		 */
		$return = [
			'iso_2b'          => $language->iso2B,
			'iso'             => $language->iso,
			'name'            => $language->name,
			'bibles'          => $language->bibles_count ?? $language->bibles->count()
		];
		if($language->relationLoaded('translations')) $return['alt_names'] = array_flatten($language->translations->pluck('name')->ToArray());
		if($language->bibles) $return['filesets'] = $language->bibles->pluck('filesets')->flatten()->count();
		return $return;
	}

}
