<?php

namespace App\Transformers;

use App\Models\Organization\Organization;

class OrganizationTransformer extends BaseTransformer
{
	/**
	 * A Fractal transformer.
	 *
	 * @param Organization $organization
	 *
	 * @return array
	 */
    public function transform(Organization $organization)
    {
    	$translation = $organization->translations->where('language_iso',$GLOBALS['i18n_iso'])->first();
	    $organization->name = $translation->name ?? str_replace('-',' ', $organization->slug);
	    $organization->description = $translation->description ?? '';
	    
	    switch ($this->version) {
		    case "2":
		    case "3": return $this->transformForV2($organization);
		    case "4":
		    default:  return $this->transformForV4($organization);
	    }
    }

	public function transformForV2(Organization $organization) {
		switch($this->route) {
			case "v2_volume_organization_list": {
				return [
					"organization_id"   => $organization->id,
					"organization_name" => $organization->name ?? "",
					"number_volumes"    => $organization->bibles->count() ?? 0,
				];
				break;
			}

			default: {
				return [
					"id"                  => $organization->id,
					"name"                => (isset($organization->vernacularTranslation)) ? $organization->vernacularTranslation->name : "",
					"english_name"        => $organization->name ?? "",
					"description"         => (isset($organization->vernacularTranslation)) ? $organization->vernacularTranslation->description : "",
					"english_description" => $organization->description ?? "",
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

		}
	}

	/**
	 * @param Organization $organization
	 *
	 * @return array
	 */
	public function transformForV4(Organization $organization) {

		// If the Organization contains member organizations return their Bibles as well.
		$bibles[] = $organization->bibles->toArray();
		if($organization->relationLoaded('memberOrganizations')) {
			foreach ($organization->memberOrganizations as $member_organization) {
				$bibles[] = $member_organization->child_organization->bibles->toArray();
			}
		}

		switch($this->route) {
			case "v4_organizations.one": {
				return [
					"slug"              => $organization->slug,
					"name"              => $organization->name,
					"description"       => $organization->description,
					"bibles"            => $bibles,
					"resources"         => $organization->resources,
					"logos"             => $organization->logos,
					"abbreviation"      => $organization->abbreviation,
					"primaryColor"      => $organization->primaryColor,
					"secondaryColor"    => $organization->secondaryColor,
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
					"name"           => $organization->name,
					"description"    => $organization->description,
					"slug"           => $organization->slug,
					"logo"           => @$organization->logos->where('icon',0)->first()->url,
					"logo_icon"      => @$organization->logos->where('icon',1)->first()->url,
					"abbreviation"   => $organization->abbreviation,
					"primaryColor"   => $organization->primaryColor,
					"secondaryColor" => $organization->secondaryColor,
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
