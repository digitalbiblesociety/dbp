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
	    $iso = checkParam('iso', null, 'optional') ?? "eng";
	    $organization->name = ($organization->translations->where('language_iso',$iso)->first()) ? $organization->translations->where('language_iso',$iso)->first()->name : $organization->slug;
	    $organization->description = ($organization->translations->where('language_iso',$iso)->first()) ? $organization->translations->where('language_iso',$iso)->first()->description : '';

	    switch ($this->version) {
		    case "2":
		    case "3": {
		    	if($this->route == "v2_volume_organization_list") return $this->transformForV2_VolumeOrganizationListing($organization);
		    	return $this->transformForV2($organization);
		    }
		    case "4":
		    default: return $this->transformForV4($organization,$iso);
	    }
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

		$bibles[] = $organization->bibles->toArray();
		if($organization->relationLoaded('memberOrganizations')) {
			foreach ($organization->memberOrganizations as $member_organization) {
				$bibles[] = $member_organization->child_organization->bibles->toArray();
			}
		}

		switch($this->route) {
			case "v4_organizations.one": {
				return [
					"id"                => $organization->id,
					"name"              => $organization->name,
					"description"       => $organization->description,
					"slug"              => $organization->slug,
					"bibles"            => $bibles,
					"resources"         => $organization->resources,
					"logos"             => $organization->logos,
					"abbreviation"      => $organization->abbreviation,
					"notes"             => $organization->notes,
					"primaryColor"      => $organization->primaryColor,
					"secondaryColor"    => $organization->secondaryColor,
					"inactive"          => $organization->inactive,
					"url_facebook"      => $organization->url_facebook,
					"url_website"       => $organization->url_website,
					"url_donate"        => $organization->url_donate,
					"url_twitter"       => $organization->url_twitter,
					"address"           => $organization->address,
					"address2"          => $organization->address2,
					"city"              => $organization->city,
					"state"             => $organization->state,
					"country"           => $organization->country,
					"zip"               => $organization->zip,
					"phone"             => $organization->phone,
					"email"             => $organization->email
				];
			}

			default:
			case "v4_organizations.all": {
				return [
					"id"             => $organization->id,
					"name"           => $organization->name,
					"description"    => $organization->description,
					"slug"           => $organization->slug,
					"logos"          => $organization->logos,
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
	}

}
