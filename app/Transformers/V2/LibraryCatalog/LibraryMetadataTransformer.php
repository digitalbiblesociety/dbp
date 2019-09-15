<?php

namespace App\Transformers\V2\LibraryCatalog;

use League\Fractal\TransformerAbstract;

/**
 * Class LibraryMetadataTransformer
 *
 *
 * @package App\Transformers\V2\LibraryCatalog
 */
class LibraryMetadataTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @OA\Schema (
     *     type="object",
     *     schema="v2_library_metadata",
     *     description="The various version ids in the old version 2 style",
     *     title="v2_library_metadata",
     *     @OA\Xml(name="v2_library_metadata"),
     *     @OA\Items(
     *          @OA\Property(property="dam_id",            ref="#/components/schemas/BibleFileset/properties/id"),
     *          @OA\Property(property="mark",              ref="#/components/schemas/BibleFilesetCopyright/properties/copyright"),
     *          @OA\Property(property="volume_summary",    ref="#/components/schemas/BibleFilesetCopyright/properties/copyright_description"),
     *          @OA\Property(property="font_copyright",    ref="#/components/schemas/AlphabetFont/properties/copyright"),
     *          @OA\Property(property="font_url",          ref="#/components/schemas/AlphabetFont/properties/url"),
     *          @OA\Property(property="organization",
     *              @OA\Schema(type="object",
     *                  @OA\Items(
     *                      @OA\Property(property="organization_id",      ref="#/components/schemas/Organization/properties/id"),
     *                      @OA\Property(property="organization",         ref="#/components/schemas/OrganizationTranslation/properties/name"),
     *                      @OA\Property(property="organization_english", ref="#/components/schemas/OrganizationTranslation/properties/name"),
     *                      @OA\Property(property="organization_role",    ref="#/components/schemas/BibleOrganizations/properties/relationship_type"),
     *                      @OA\Property(property="organization_url",     ref="#/components/schemas/Organization/properties/url_website"),
     *                      @OA\Property(property="organization_donation",ref="#/components/schemas/Organization/properties/url_donate"),
     *                      @OA\Property(property="organization_address", ref="#/components/schemas/Organization/properties/address"),
     *                      @OA\Property(property="organization_address2",ref="#/components/schemas/Organization/properties/address2"),
     *                      @OA\Property(property="organization_city",    ref="#/components/schemas/Organization/properties/city"),
     *                      @OA\Property(property="organization_state",   ref="#/components/schemas/Organization/properties/state"),
     *                      @OA\Property(property="organization_country", ref="#/components/schemas/Organization/properties/country"),
     *                      @OA\Property(property="organization_zip",     ref="#/components/schemas/Organization/properties/zip"),
     *                      @OA\Property(property="organization_phone",   ref="#/components/schemas/Organization/properties/phone"),
     *                   )
     *                  )
     *              )
     *          )
     *     )
     * )
     * @param $bible_fileset
     *
     * @return array
     */
    public function transform($bible_fileset)
    {
        $output = [
            'dam_id'         => $bible_fileset->dam_id,
            'mark'           => optional($bible_fileset->copyright)->copyright,
            'volume_summary' => optional($bible_fileset->copyright)->copyright_description,
            'font_copyright' => null,
            'font_url'       => null
        ];

        $organization = optional(optional($bible_fileset->copyright)->organizations)->first();
        if ($organization) {
            $output['organization'][] = [
                'organization_id'       => (string) $organization->id,
                'organization'          => (string) optional($organization->translations->where('vernacular', 1)->first())->name,
                'organization_english'  => optional($organization->translations->where('language_id', $GLOBALS['i18n_id'])->first())->name ?? $organization->slug,
                'organization_role'     => optional($bible_fileset->copyright)->role->roleTitle->name,
                'organization_url'      => $organization->url_website,
                'organization_donation' => $organization->url_donate,
                'organization_address'  => $organization->address,
                'organization_address2' => $organization->address2,
                'organization_city'     => $organization->city,
                'organization_state'    => $organization->state,
                'organization_country'  => $organization->country,
                'organization_zip'      => $organization->zip,
                'organization_phone'    => $organization->phone,
            ];
        }
        return $output;
    }
}
