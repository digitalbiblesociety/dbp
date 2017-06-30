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

	public function transformForDataTables(Language $language)
	{
		return [
			$language->name,
			$language->glotto_id,
		];
	}

	public function transformForV2(Language $language) {
		return [
			'language_code'        => $language->iso ?? '',
            'language_name'        => $language->autonym ?? '',
            'english_name'         => $language->name ?? '',
            'language_iso'         => $language->iso ?? '',
            'language_iso_2B'      => $language->iso639_2b ?? '',
            'language_iso_2T'      => $language->iso639_2t ?? '',
            'language_iso_1'       => $language->iso639_1 ?? '',
            'language_iso_name'    => $language->name ?? '',
            'language_family_code' => $language->iso ?? ''
		];
	}

	public function transformForV4(Language $language) {
		return [
			'name_current' => $language->name_current,
			'name' => $language->name
		];
	}

}
