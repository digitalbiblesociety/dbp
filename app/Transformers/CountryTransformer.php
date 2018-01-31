<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Country\Country;
class CountryTransformer extends BaseTransformer
{

	/**
	 * A Fractal transformer for the Country Collection.
	 * This function will format requests depending on the
	 * $_GET variables passed to it via the frontend site
	 *
	 * @param Country $country
	 *
	 * @return array
	 */
	public function transform($country)
	{
		switch ($this->version) {
			case "jQueryDataTable": return $this->transformForDataTables($country);
			case "2":
			case "3": return $this->transformForV2($country);
			case "4":
			default: return $this->transformForV4($country);
		}
	}

	public function transformForDataTables($country)
	{
		if(!$country->hidden) {
			$name = $country->currentTranslation->name ?? $country->name;
			return [
				"<a href='".env('APP_URL')."/countries/".$country->id."'>$name</a>",
				$country->continent,
				$country->id,
				$country->iso_a3,
				$country->fips
			];
		}
	}

	public function transformForV4($country)
	{
		$translation = $country->translations($this->iso)->first();
		return [
			'name'           => ($translation) ? $translation->name : $country->name,
			'continent_code' => $country->continent,
			'languages'      => $country->languagesFiltered->pluck('name','iso'),
			'codes' => [
				'fips'       => $country->fips,
				'iso_a3'     => $country->iso_a3,
				'iso_a2'     => $country->id
			]
		];
	}

	public function transformForV2($country)
	{
		return $country->toArray();
	}

}
