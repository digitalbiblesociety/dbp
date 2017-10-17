<?php

namespace App\Models\Organization;

use App\Models\Language\Language;
use App\Models\Resource\Resource;
use App\Models\Bible\Bible;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization\OrganizationTranslation;
use App\Traits\Uuids;

class Organization extends Model
{
    protected $fillable = ['name', 'email', 'password','facebook','twitter','website','address','phone'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['pivot','logo','facebook','twitter','id','code'];

    public function translations($iso = null)
    {
    	if($iso) return $this->HasOne(OrganizationTranslation::class,'organization_id','id')->where('language_iso', $iso);
        return $this->HasMany(OrganizationTranslation::class);
    }

    public function currentTranslation()
    {
        return $this->HasOne(OrganizationTranslation::class)->where('language_iso',\i18n::getCurrentLocale());
    }

	public function vernacularTranslation()
	{
		return $this->HasOne(OrganizationTranslation::class)->where('vernacular',1);
	}

    public function bibles()
    {
        return $this->belongsToMany(Bible::class,'bible_organizations');
    }

    public function resources()
    {
        return $this->HasMany(Resource::class);
    }

	public function logos()
	{
		return $this->HasMany(OrganizationLogo::class);
	}

	public function logo()
	{
		return $this->HasOne(OrganizationLogo::class,'organization_id','id')->where('language_iso','eng');
	}

	public function logoIcon()
	{
		return $this->HasOne(OrganizationLogo::class,'organization_id','id')->where('icon',1);
	}

	public function members()
	{
		return $this->BelongsToMany(User::class, 'user_roles')->withPivot('access_level');
	}


	/*
	 * Organizational Relationship Relationships
	 */

	public function relationships()
	{
		return $this->hasMany(OrganizationRelationship::class, 'organization_parent_id');
	}

	public function dbl()
	{
		$dbl = Organization::where('slug','the-digital-bible-library')->first();
		return $this->hasOne(OrganizationRelationship::class, 'organization_child_id')->where('organization_parent_id',$dbl->id);
	}

}
