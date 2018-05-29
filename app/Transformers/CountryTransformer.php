<?php

namespace App\Transformers;

use App\Models\Country\Country;
use App\Models\Country\JoshuaProject;


use App\Models\Country\FactBook\CountryEthnicities;
use App\Models\Country\FactBook\CountryRegions;
use App\Models\Country\FactBook\CountryTranslations;

use App\Transformers\Factbook\CommunicationsTransformer;
use App\Transformers\Factbook\EconomyTransformer;
use App\Transformers\Factbook\EnergyTransformer;
use App\Transformers\Factbook\GeographyTransformer;
use App\Transformers\Factbook\GovernmentTransformer;
use App\Transformers\Factbook\IssuesTransformer;
use App\Transformers\Factbook\LanguageTransformer;
use App\Transformers\Factbook\PeopleTransformer;
use App\Transformers\Factbook\EthnicitiesTransformer;
use App\Transformers\Factbook\RegionsTransformer;
use App\Transformers\Factbook\ReligionsTransformer;
use App\Transformers\Factbook\TransportationTransformer;

class CountryTransformer extends BaseTransformer
{

	protected $availableIncludes = [
		'communications',
		'economy',
		'energy',
		'geography',
		'government',
		'government',
		'issues',
		'language',
		'people',
		'ethnicities',
		'religions',
		'translations',
		'transportation'
	];

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
			return [
				"<a href='/countries/".$country->id."'><svg class='icon'><use xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='/img/flags.svg#" .$country->id. "'></use></svg> $country->name</a>",
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

			/**
			 * @OAS\Schema (
			*	type="array",
			*	schema="v4_countries.all",
			*	description="The minimized country return for the all countries route",
			*	title="v4_countries.all",
			*	@OAS\Xml(name="v4_countries.all"),
			*	@OAS\Items(          @OAS\Property(property="name",              ref="#/components/schemas/Country/properties/name"),
			 *          @OAS\Property(property="continent_code",    ref="#/components/schemas/Country/properties/continent"),
			 *          @OAS\Property(property="languages",         @OAS\Schema(type="array",
			 *              @OAS\Items(@OAS\Schema(description="A key value pair consisting of an iso code and language name", example={"eng"="English"}))))
			 *      )
			 *   )
			 * )
			 */
			case "v4_countries.all": {
				if($country->relationLoaded('translation')) {
					$output['name'] = (isset($country->translation->name)) ? $country->translation->name : $country->name;
				} else {
					$output['name'] = $country->name;
				}
				$output['continent_code'] = $country->continent;
				$output['codes'] = [
					'fips'       => $country->fips,
					'iso_a3'     => $country->iso_a3,
					'iso_a2'     => $country->id
				];
				if($country->relationLoaded('languagesFiltered')) {
					$output['languages'] = $country->languagesFiltered->mapWithKeys(function ($item) {
						return [ $item['iso'] => $item['translation']['name'] ?? $item['name'] ];
					});
				}
				return $output;
			}
			case "v4_countries.jsp": {
				return [
					"id"                      => $country->country->id,
					"continent"               => $country->country->continent,
					"population"              => number_format($country->population),
					"population_unreached"    => number_format($country->population_unreached),
					"language_official_name"  => $country->language_official_name,
					"people_groups"           => $country->people_groups,
					"people_groups_unreached" => $country->people_groups_unreached,
					"joshua_project_scale"    => $country->joshua_project_scale,
					"primary_religion"        => $country->primary_religion,
					"percent_christian"       => $country->percent_christian,
					"resistant_belt"          => $country->resistant_belt,
					"percent_literate"        => $country->percent_literate
				];
			}

			/**
			 * @OAS\Schema (
			*	type="array",
			*	schema="v4_countries.one",
			*	description="The minimized country return for the all countries route",
			*	title="v4_countries.one",
			*	@OAS\Xml(name="v4_countries.one"),
			*	@OAS\Items(
			 *          @OAS\Property(property="name",              ref="#/components/schemas/Country/properties/name"),
			 *          @OAS\Property(property="continent_code",    ref="#/components/schemas/Country/properties/continent"),
			 *          @OAS\Property(property="languages",         @OAS\Schema(type="array",
			 *          @OAS\Items(@OAS\Schema(description="A key value pair consisting of an iso code and language name", example={"eng"="English"}))))
			 *     )
			 *   )
			 * )
			 */
			case "v4_countries.one": {
				return [
					'name'           => $country->name,
					'introduction'   => $country->introduction,
					'continent_code' => $country->continent,
					'geography'      => $country->geography,
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

	public function includeCommunications(Country $country)
	{
		return $this->item($country->communications->toArray(), new CommunicationsTransformer());
	}

	public function includeEconomy(Country $country)
	{
		return $this->item($country->economy->toArray(), new EconomyTransformer());
	}

	public function includeEnergy(Country $country)
	{
		return $this->item($country->energy->toArray(), new EnergyTransformer());
	}

	public function includeGeography(Country $country)
	{
		return $this->item($country->geography->toArray(), new GeographyTransformer());
	}

	public function includeGovernment(Country $country)
	{
		return $this->item($country->government->toArray(), new GovernmentTransformer());
	}

	public function includeIssues(Country $country)
	{
		return $this->item($country->issues->toArray(), new IssuesTransformer());
	}

	public function includeLanguage(Country $country)
	{
		return $this->item($country->language->toArray(), new LanguageTransformer());
	}

	public function includePeople(Country $country)
	{
		return $this->item($country->people->toArray(), new PeopleTransformer());
	}

	public function includeEthnicities(Country $country)
	{
		return $this->item($country->ethnicities->toArray(), new EthnicitiesTransformer());
	}

	public function includeRegions(Country $country)
	{
		return $this->item($country->ethnicities->toArray(), new RegionsTransformer());
	}

	public function includeReligions(Country $country)
	{
		return $this->item($country->religions->toArray(), new ReligionsTransformer());
	}

	public function includeTransportation(Country $country)
	{
		return $this->item($country->transportation->toArray(), new TransportationTransformer());
	}

}
