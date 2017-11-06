<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization\OrganizationLogo
 *
 * @property int $organization_id
 * @property string|null $language_iso
 * @property string|null $url
 * @property int $icon
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Organization\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationLogo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationLogo whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationLogo whereLanguageIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationLogo whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationLogo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationLogo whereUrl($value)
 * @mixin \Eloquent
 */
class OrganizationLogo extends Model
{
	protected $hidden = ['organization_id'];
	protected $primaryKey = 'organization_logos';

	public function organization()
	{
		return $this->BelongsTo(Organization::class);
	}

}
