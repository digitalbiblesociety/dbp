<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization\OrganizationRelationship
 *
 * @property int $organization_parent_id
 * @property int $organization_child_id
 * @property string $type
 * @property string $relationship_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Organization\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationRelationship whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationRelationship whereOrganizationChildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationRelationship whereOrganizationParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationRelationship whereRelationshipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationRelationship whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationRelationship whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganizationRelationship extends Model
{
	protected $primaryKey = 'organization_parent_id';
	protected $fillable = [
		'type',
		'organization_child_id',
		'organization_parent_id',
		'relationship_id'
	];
	public $incrementing = false;

	public function organization()
	{
		return $this->BelongsTo(Organization::class);
	}

}
