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
    	$organization->name = ($organization->translations("eng")->first()) ? $organization->translations("eng")->first()->name : $organization->slug;
    	//if($organization->engTranslation) dd($organization->engTranslation->name);
	    switch ($this->version) {
		    case "jQueryDataTable": return $this->transformForDataTables($organization);
		    case "2": {
		    	if($this->route == "v2_volume_organization_list") return $this->transformForV2_VolumeOrganizationListing($organization);
		    	return $this->transformForV2($organization);
		    }
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
			($organization->engTranslation) ? $organization->engTranslation->name : "",
			$organization->id,
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
	 * Volume Organization Listing
	 *
	 * @param Organization $organization
	 *
	 * @return array
	 */
	public function transformForV2_VolumeOrganizationListing(Organization $organization) {
		return [
			"organization_id"   => $organization->id,
			"organization_name" => ($organization->engTranslation) ? $organization->engTranslation->name : "",
			"number_volumes"    => $organization->bibles->count(),
		];
	}

	/**
	 * @param Organization $organization
	 *
	 * @return array
	 */
	public function transformForV4(Organization $organization) {
		return [
			"id"             => $organization->id,
			"name"           => $organization->name,
            "slug"           => $organization->slug,
			"logo"           => ($organization->logo) ? $organization->logo->url : "",
			"icon"           => ($organization->logoIcon) ? $organization->logoIcon->url : "",
            "abbreviation"   => $organization->abbreviation,
            "notes"          => $organization->notes,
            "primaryColor"   => $organization->primaryColor,
            "secondaryColor" => $organization->secondaryColor,
            "inactive"       => $organization->inactive,
            "url_facebook"   => $organization->url_facebook,
            "url_website"    => $organization->url_website,
            "url_donate"     => $organization->url_donate,
            "url_twitter"    => $organization->url_twitter,
            "address"        => $organization->address,
            "address2"       => $organization->address2,
            "city"           => $organization->city,
            "state"          => $organization->state,
            "country"        => $organization->country,
            "zip"            => $organization->zip,
            "phone"          => $organization->phone,
            "email"          => $organization->email
		];
	}

}
