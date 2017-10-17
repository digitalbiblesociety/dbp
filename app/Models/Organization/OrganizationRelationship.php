<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

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
