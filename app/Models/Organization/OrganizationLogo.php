<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class OrganizationLogo extends Model
{
	protected $hidden = ['organization_id'];
	protected $primaryKey = 'organization_logos';
	public $timestamps = false;

	public function organization()
	{
		return $this->BelongsTo(Organization::class);
	}

}
