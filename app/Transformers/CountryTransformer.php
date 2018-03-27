<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Country\Country;
use App\Models\Country\JoshuaProject;
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
		if(is_a($country, JoshuaProject::class)) {
			$name = (isset($country->country->translation)) ? $country->country->translation->name : $country->country->name;
			return [
				"<a href='/$this->iso/countries/".$country->country->id."'><svg class=\"icon\"><use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"/img/flags.svg#".$country->country->id."\"></use></svg>".$name."</a>",
				$country->country->continent,
				number_format($country->population),
				number_format($country->population_unreached),
				"<a href='/$this->iso/languages/$country->language_official_iso'>".$country->language_official_name."</a>",
				$country->people_groups,
				$country->people_groups_unreached,
				$country->joshua_project_scale,
				$country->primary_religion,
				$country->percent_christian,
				$country->resistant_belt,
				$country->percent_literate
			];
		}

		if(!$country->hidden) {
			$name = $country->currentTranslation->name ?? $country->name;
			return [
				"<a href='/countries/".$country->id."'>$name</a>",
				$country->continent,
				$country->id,
				$country->iso_a3,
				$country->fips
			];
		}
	}

	public function transformForV4($country)
	{
		switch($this->route) {
			case "v4_countries.all": {
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
			case "v4_countries.one": {
				$translation = $country->translations($this->iso)->first();
				return [
					'name'           => ($translation) ? $translation->name : $country->name,
					'introduction'   => $country->introduction,
					'continent_code' => $country->continent,
					'languages'      => $country->languagesFiltered->map(function ($language) {
						return [
							'name'   => $language->name,
							'iso'    => $language->iso,
							'bibles' => $language->bibles->pluck('currentTranslation.name','id')
						];
					}),
					'codes' => [
						'fips'       => $country->fips,
						'iso_a3'     => $country->iso_a3,
						'iso_a2'     => $country->id
					]
				];
			}
		}
	}

	public function transformForV2($country)
	{
		return $country->toArray();
	}

}
