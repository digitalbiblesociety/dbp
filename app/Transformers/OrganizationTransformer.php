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
        $translation = $organization->translations->where('language_id', $GLOBALS['i18n_id'])->first();
        $organization->name = $translation->name ?? ucwords(str_replace('-', ' ', $organization->slug));
        $organization->description = $translation->description ?? '';
        $organization->tagline = $translation->description_short ?? '';

        switch ((int) $this->version) {
            case 2:
            case 3:
                return $this->transformForV2($organization);
            case 4:
            default:
                return $this->transformForV4($organization);
        }
    }

    public function transformForV2(Organization $organization)
    {
        switch ($this->route) {

            /**
             *
             * @see Controller: \App\Http\Controllers\Organization\OrganizationsController::index
             *
             * @OA\Schema (
             *  type="array",
             *  schema="v2_volume_organization_list",
             *  description="The volume organization list",
             *  title="v2_volume_organization_list",
             *  @OA\Xml(name="v2_volume_organization_list"),
             *  @OA\Items(
             *          @OA\Property(property="organization_id",   ref="#/components/schemas/Organization/properties/id"),
             *          @OA\Property(property="organization_name", ref="#/components/schemas/OrganizationTranslation/properties/name"),
             *          @OA\Property(property="number_volumes",    @OA\Schema(type="string", description="Number of Bible Volumes"))
             *     )
             *   )
             * )
             *
             */
            case 'v2_volume_organization_list':
                return [
                    'organization_id'   => (string) $organization->id,
                    'organization_name' => (string) $organization->name,
                    'number_volumes'    => (string) $organization->bibles_count,
                ];
                break;

            default:
                return [
                    'id'                  => $organization->id,
                    'name'                => $organization->vernacularTranslation ? $organization->vernacularTranslation->name : '',
                    'english_name'        => $organization->name ?? '',
                    'description'         => $organization->vernacularTranslation ? $organization->vernacularTranslation->description : '',
                    'english_description' => $organization->description_short ?? '',
                    'web_url'             => $organization->url_website,
                    'donation_url'        => $organization->url_donate,
                    'enabled'             => 'true',
                    'address'             => $organization->address,
                    'address2'            => null,
                    'city'                => null,
                    'state'               => null,
                    'country'             => null,
                    'zip'                 => null,
                    'phone'               => $organization->phone
                ];
        }
    }

    /**
     * @param Organization $organization
     *
     * @return array
     */
    public function transformForV4(Organization $organization)
    {
        switch ($this->route) {
            case 'v4_organizations.one':
                return [
                    'slug'              => $organization->slug,
                    'name'              => $organization->name,
                    'description'       => $organization->description,
                    'description_short' => $organization->tagline,
                    'phone'             => $organization->phone,
                    'email'             => $organization->email,
                    'logos'             => $organization->logos,
                    'colors' => [
                        'primary'      => $organization->primaryColor,
                        'secondary'    => $organization->secondaryColor,
                    ],
                    'urls' => [
                        'site'          => (string) $organization->url_website,
                        'donate'        => (string) $organization->url_donate,
                        'twitter'       => (string) $organization->url_twitter,
                        'facebook'      => (string) $organization->url_facebook,
                        'instagram'     => (string) $organization->url_instagram,
                    ],
                    'address' => [
                        'line_1'            => $organization->address,
                        'line_2'            => $organization->address2,
                        'city'              => $organization->city,
                        'state'             => $organization->state,
                        'country'           => $organization->country,
                        'zip'               => $organization->zip,
                    ]
                ];

            default:
            case 'v4_organizations.all':
                $output = [
                    'id'                => $organization->id,
                    'name'              => $organization->name,
                    'description_short' => $organization->tagline,
                    'slug'              => $organization->slug,
                    'logo'              => optional($organization->logos->where('icon', 0)->first())->url,
                    'logo_icon'         => optional($organization->logos->where('icon', 1)->first())->url,
                    'phone'             => $organization->phone,
                    'email'             => $organization->email,
                    'latitude'          => $organization->latitude,
                    'longitude'         => $organization->longitude,
                    'bible_count'       => $organization->bibles_count,
                    'colors'            => [
                        'primary'   => $organization->primaryColor,
                        'secondary' => $organization->secondaryColor,
                    ],
                    'urls'              => [
                        'site'     => $organization->url_website,
                        'donate'   => $organization->url_donate,
                        'twitter'  => $organization->url_twitter,
                        'facebook' => $organization->url_facebook,
                    ]
                ];

                if ($organization->relationLoaded('relationships')) {
                    $output['relationships'] = $organization->relationships;
                }

                if ($organization->relationLoaded('memberships')) {
                    $output['memberships'] = $organization->memberships;
                }

                return $output;
        }
    }
}
