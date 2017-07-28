<?php

namespace App\Transformers;

use App\Models\Organization\Organization;

class OrganizationTransformer extends BaseTransformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Organization $organization)
    {
    	$organization->engTranslation = $organization->translations("eng")->first();
    	//if($organization->engTranslation) dd($organization->engTranslation->name);
	    switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($organization);
		    case "2": return $this->transformForV2($organization);
		    case "4":
		    default: return $this->transformForV4($organization);
	    }
    }


	/**
	 * @param Organization $organization
	 *
	 * @return array
	 */
	public function transformForDataTables(Organization $organization)
	{
		return [
			$organization->name,
			$organization->glotto_id,
		];
	}

	/**
	 * @param Organization $organization
	 *
	 * @return array
	 */
	public function transformForV2(Organization $organization) {
		return [
			"id"                  => $organization->id,
			"name"                => (isset($organization->vernacularTranslation)) ? $organization->vernacularTranslation->name : "",
			"english_name"        => ($organization->engTranslation) ? $organization->engTranslation->name : "",
			"description"         => (isset($organization->vernacularTranslation)) ? $organization->vernacularTranslation->description : "",
			"english_description" => ($organization->engTranslation) ? $organization->engTranslation->description : "",
			"web_url"             => $organization->website,
			"donation_url"        => "",
			"enabled"             => "true",
			"address"             => $organization->address,
			"address2"            => null,
			"city"                => null,
			"state"               => null,
			"country"             => null,
			"zip"                 => null,
			"phone"               => $organization->phone
		];
	}

	/**
	 * @param Organization $organization
	 *
	 * @return array
	 */
	public function transformForV4(Organization $organization) {
		return [
			'glotto_code' => $organization->id,
			'iso_code'    => $organization->iso,
			'name'        => $organization->name
		];
	}

}
