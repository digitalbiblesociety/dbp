<?php

namespace App\Transformers;

use App\Models\Language\Language;

class LanguageTransformer extends BaseTransformer
{
    public function transform(Language $language)
    {
    	switch ($this->version) {
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
	public function transformForV2(Language $language) {
		switch($this->route) {
			case "v2_library_volumeLanguage": {
				return [
					'language_name'             => $language->autonym ?? "",
                    'english_name'              => $language->name ?? "",
                    'language_code'             => strtoupper($language->iso),
                    'language_iso'              => $language->iso,
                    'language_iso_2B'           => $language->iso2B ?? "",
                    'language_iso_2T'           => $language->iso2T ?? "",
                    'language_iso_1'            => $language->iso1 ?? "",
                    'language_iso_name'         => $language->name ?? "",
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
		 * @OA\Response(
		 *   response="v4_languages.one",
		 *   description="The Full alphabet return for the single alphabet route",
		 *   @OA\MediaType(
		 *     mediaType="application/json",
		 *     @OA\Schema(ref="#/components/schemas/Language")
		 *   )
		 * )
		 */
		$full = checkParam('full', null, 'optional');
		if($full) {
			return [
				'id'                   => $language->id,
				'name'                 => $language->name,
                'description'          => @$language->translations->where('iso_translation',$this->i10n)->first()->description ?? "",
				'autonym'              => ($language->autonym) ? $language->autonym->name : '',
                'glotto_id'            => $language->glotto_id,
                'iso'                  => $language->iso,
                'maps'                 => $language->maps,
                'area'                 => $language->area,
                'population'           => $language->population,
				'country_id'           => $language->country_id,
				'country_name'         => $language->primaryCountry->name ?? '',
				'codes'                => $language->codes->pluck('code','source') ?? '',
				'alternativeNames'     => array_unique(array_flatten($language->translations->pluck('name')->ToArray())) ?? '',
				'dialects'             => $language->dialects->pluck('name') ?? '',
				'classifications'      => $language->classifications->pluck('name','classification_id') ?? '',
				'bibles'               => $language->bibles,
				'resources'            => $language->resources
			];
		}

		/**
		 * @OA\Response(
		 *   response="v4_languages.all",
		 *   description="The minimized language return for the single language route",
		 *   @OA\MediaType(
		 *     mediaType="application/json",
		 *     @OA\Schema(ref="#/components/schemas/Language")
		 *   )
		 * )
		 */
		$return = [
			'iso_2b' => $language->iso2B,
			'iso'    => $language->iso,
			'name'   => $language->translation->name ?? $language->name,
			'bibles' => $language->bibles_count ?? $language->bibles->count()
		];
		if($language->relationLoaded('translations')) {
			$return['alt_names'] = $language->translations->mapWithKeys(function ($translation) {
				return [$translation->translation_iso->iso => $translation->name];
			});
        }
		if($language->bibles) $return['filesets'] = $language->bibles->pluck('filesets')->flatten()->count();
		return $return;
	}

}
