<?php

namespace App\Models\Organization;

use App\Models\Bible\Bible;
use App\Models\Language\Language;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization\OrganizationTranslation;

class Organization extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'email', 'password','facebook','twitter','website','address','phone'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['pivot','logo','facebook','twitter','id','code'];

    public function translations($id = null)
    {
    	if($id) {
    		$language = new Language();
    		$language = $language->fetchByID($id);
		    return $this->HasOne(OrganizationTranslation::class,'organization_id','id')->where('glotto_id', $language->id);
	    }
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
        return $this->belongsToMany(Bible::class);
    }

    public function resources()
    {
        return $this->HasMany(Resource::class);
    }

}
