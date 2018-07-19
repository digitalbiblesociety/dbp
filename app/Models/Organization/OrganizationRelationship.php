<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization\OrganizationRelationship
 *
 * @property-read \App\Models\Organization\Organization $organization
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Relationships for the Organization",
 *     title="Organization Relationship",
 *     @OAS\Xml(name="OrganizationRelationship")
 * )
 *
 */
class OrganizationRelationship extends Model
{
	protected $primaryKey = 'organization_parent_id';
	protected $fillable = ['type','organization_child_id','organization_parent_id','relationship_id'];
	public $incrementing = false;


	/**
	 *
	 * @OAS\Property(
	 *     title="organization_parent_id",
	 *     description="The Organization's id",
	 *     type="integer",
	 *     minimum=0
	 * )
	 *
	 * @method static OrganizationRelationship whereOrganizationParentId($value)
	 * @property int $organization_parent_id
	 */
	protected $organization_parent_id;

	/**
	 *
	 * @OAS\Property(
	 *     title="organization_child_id",
	 *     description="The Organization's id",
	 *     type="integer",
	 *     minimum=0
	 * )
	 *
	 * @method static OrganizationRelationship whereOrganizationChildId($value)
	 * @property int $organization_child_id
	 */
	protected $organization_child_id;
	/**
	 *
	 * @OAS\Property(
	 *     title="type",
	 *     description="The Organization's type",
	 *     type="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static OrganizationRelationship whereType($value)
	 * @property string $type
	 */
	protected $type;

	/**
	 *
	 * @OAS\Property(
	 *     title="relationship_id",
	 *     description="The Organization's relationship_id",
	 *     type="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static OrganizationRelationship whereRelationshipId($value)
	 * @property string $relationship_id
	 */
	protected $relationship_id;
	/**
	 *
	 * @method static OrganizationRelationship whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 */
	protected $created_at;
	/**
	 *
	 * @method static OrganizationRelationship whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 */
	protected $updated_at;

	public function child_organization()
	{
		return $this->BelongsTo(Organization::class,'organization_child_id');
	}

	public function parent_organization()
	{
		return $this->BelongsTo(Organization::class,'organization_parent_id');
	}

}
