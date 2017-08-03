<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class OrganizationRelationship extends Model
{

	public function organization()
	{
		return $this->BelongsTo(Organization::class);
	}

}
