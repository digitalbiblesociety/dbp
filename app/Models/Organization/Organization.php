<?php

namespace App\Models\Organization;

use App\Models\Language\Language;
use App\Models\Resource\Resource;
use App\Models\Bible\Bible;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization\OrganizationTranslation;

class Organization extends Model
{
    protected $fillable = ['name', 'email', 'password','facebook','twitter','website','address','phone'];
	public $incrementing = false;
	use Uuids;
	
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
        $language = Language::where('iso',\i18n::getCurrentLocale())->first();
        return $this->HasOne(OrganizationTranslation::class)->where('glotto_id',$language->id);
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

}
