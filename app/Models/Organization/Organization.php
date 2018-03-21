<?php

namespace App\Models\Organization;

use App\Models\Bible\BibleFileset;
use App\Models\Language\Language;
use App\Models\Resource\Resource;
use App\Models\Bible\Bible;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization\OrganizationTranslation;
use App\Traits\Uuids;

/**
 * App\Models\Organization\Organization
 *
 * @property int $id
 * @property string $slug
 * @property string|null $abbreviation
 * @property string|null $notes
 * @property string|null $primaryColor
 * @property string|null $secondaryColor
 * @property int|null $inactive
 * @property string|null $url_facebook
 * @property string|null $url_website
 * @property string|null $url_donate
 * @property string|null $url_twitter
 * @property string|null $address
 * @property string|null $address2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property int|null $zip
 * @property string|null $phone
 * @property string|null $email
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Bible[] $bibles
 * @property-read \App\Models\Organization\OrganizationTranslation $currentTranslation
 * @property-read \App\Models\Organization\OrganizationRelationship $dbl
 * @property-read \App\Models\Organization\OrganizationLogo $logo
 * @property-read \App\Models\Organization\OrganizationLogo $logoIcon
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization\OrganizationLogo[] $logos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $members
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization\OrganizationRelationship[] $relationships
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource\Resource[] $resources
 * @property-read \App\Models\Organization\OrganizationTranslation $translations
 * @property-read \App\Models\Organization\OrganizationTranslation $vernacularTranslation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereInactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization wherePrimaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereSecondaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereUrlDonate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereUrlFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereUrlTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereUrlWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\Organization whereZip($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileset[] $filesets
 * @property-read mixed $bibles_count
 * @property-read mixed $filesets_count
 */
class Organization extends Model
{
    protected $fillable = ['name', 'email', 'password','facebook','twitter','website','address','phone'];
	public $incrementing = false;
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['pivot','logo','facebook','twitter','id','code'];

    public function translations($iso = null)
    {
    	if($iso) return $this->HasOne(OrganizationTranslation::class,'organization_id','id')->where('language_iso', $iso);
        return $this->HasMany(OrganizationTranslation::class,'organization_id','id');
    }

    public function currentTranslation()
    {
        return $this->HasOne(OrganizationTranslation::class,'organization_id','id')->where('language_iso',\i18n::getCurrentLocale());
    }

	public function vernacularTranslation()
	{
		return $this->HasOne(OrganizationTranslation::class)->where('vernacular',1);
	}

    public function bibles()
    {
        return $this->belongsToMany(Bible::class,'bible_organizations');
    }

	public function getBiblesCountAttribute()
	{
		return $this->bibles ? $this->bibles->count() : 0;
	}

    public function filesets()
    {
    	return $this->HasMany(BibleFileset::class);
    }

	public function getFilesetsCountAttribute()
	{
		return $this->filesets ? $this->filesets->count() : 0;
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
		return $this->BelongsToMany(User::class, 'user_roles');
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
