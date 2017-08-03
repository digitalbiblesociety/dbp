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
			$language->alternativeNames,
			$language->name,
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
		return [
			'language_code'        => $language->iso ?? '',
            'language_name'        => $language->autonym ?? '',
            'english_name'         => $language->translations("eng")->name ?? $language->name,
            'language_iso'         => $language->iso ?? '',
            'language_iso_2B'      => $language->iso639_2->code ?? '',
            'language_iso_2T'      => $language->iso639_2->code ?? '',
            'language_iso_1'       => $language->iso639_1 ?? '',
            'language_iso_name'    => $language->name ?? '',
            'language_family_code' => $language->iso ?? ''
		];
	}

	/**
	 * @param Language $language
	 *
	 * @return array
	 */
	public function transformForV4(Language $language) {
			return [
				'glotto_code'     => $language->id,
				'iso_code'        => $language->iso,
				'name'            => $language->name,
				'count_bible'     => $language->bibles_count
			];
	}

}
