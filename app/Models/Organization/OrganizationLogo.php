<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization\OrganizationLogo
 *
 * @property-read \App\Models\Organization\Organization $organization
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Logo for the Organization",
 *     title="Organization Logo",
 *     @OAS\Xml(name="OrganizationLogo")
 * )
 *
 */
class OrganizationLogo extends Model
{
	protected $hidden = ['organization_id'];
	protected $primaryKey = 'organization_logos';

	/**
	 *
	 * @OAS\Property(
	 *     title="id",
	 *     description="The Organization's id",
	 *     type="integer",
	 *     minimum=0
	 * )
	 *
	 * @method static OrganizationLogo whereOrganizationId($value)
	 * @property int $organization_id
	 */
	protected $organization_id;

	/**
	 *
	 * @OAS\Property(
	 *     title="language_iso",
	 *     description="If the organization's logo contains words, this iso field indicates what language they are.",
	 *     type="string",
	 *     minLength=3,
	 *     maxLength=3,
	 *     example="eng",
	 *     nullable=true
	 * )
	 *
	 * @method static OrganizationLogo whereLanguageIso($value)
	 * @property string|null $language_iso
	 */
	protected $language_iso;

	/**
	 *
	 * @OAS\Property(
	 *     title="url",
	 *     description="The url to this organization's logo",
	 *     type="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static OrganizationLogo whereUrl($value)
	 * @property string|null $url
	 */
	protected $url;

	/**
	 *
	 * @OAS\Property(
	 *     title="url",
	 *     description="If true the url is pointed at a logo suitable for use as an icon",
	 *     type="boolean"
	 * )
	 *
	 * @method static OrganizationLogo whereIcon($value)
	 * @property boolean $icon
	 */
	protected $icon;

	/**
	 *
	 * @method static OrganizationLogo whereCreatedAt($value)
	 * @property Carbon $created_at
	 *
	 */
	protected $created_at;

	/**
	 *
	 * @method static OrganizationLogo whereUpdatedAt($value)
	 * @property Carbon $updated_at
	 *
	 */
	protected $updated_at;

	public function organization()
	{
		return $this->BelongsTo(Organization::class);
	}

}
