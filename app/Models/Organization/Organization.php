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
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileset[] $filesets
 * @property-read mixed $bibles_count
 * @property-read mixed $filesets_count
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Organization",
 *     title="Organization",
 *     @OAS\Xml(name="Organization")
 * )
 *
 */
class Organization extends Model
{
    protected $fillable = ['name', 'email', 'password','facebook','twitter','website','address','phone'];
	public $incrementing = false;


	/**
	 *
	 * @OAS\Property(
	 *     title="id",
	 *     description="The Organization's incrementing id",
	 *     format="int",
	 *     minimum=0
	 * )
	 *
	 * @method static Organization whereId($value)
	 * @property int $id
	 */
	protected $id;
	/**
	 *
	 * @OAS\Property(
	 *     title="slug",
	 *     description="The Organization's slug",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereSlug($value)
	 * @property $slug
	 */
	protected $slug;
	/**
	 *
	 * @OAS\Property(
	 *     title="abbreviation",
	 *     description="The Organization's abbreviation",
	 *     format="string",
	 *     maxLength=6
	 * )
	 *
	 * @method static Organization whereAbbreviation($value)
	 * @property $abbreviation
	 */
	protected $abbreviation;
	/**
	 *
	 * @OAS\Property(
	 *     title="notes",
	 *     description="Archivist notes about the organization being described",
	 *     format="string"
	 * )
	 *
	 * @method static Organization whereNotes($value)
	 * @property $notes
	 */
	protected $notes;
	/**
	 *
	 * @OAS\Property(
	 *     title="primaryColor",
	 *     description="The Organization's primary color derived from their logo",
	 *     format="string",
	 *     maxLength=7,
	 *     minLength=7
	 * )
	 *
	 * @method static Organization wherePrimarycolor($value)
	 * @property $primaryColor
	 */
	protected $primaryColor;
	/**
	 *
	 * @OAS\Property(
	 *     title="secondaryColor",
	 *     description="The Organization's secondary color derived from their logo",
	 *     format="string",
	 *     maxLength=7,
	 *     minLength=7
	 * )
	 *
	 * @method static Organization whereSecondarycolor($value)
	 * @property $secondaryColor
	 */
	protected $secondaryColor;
	/**
	 *
	 * @OAS\Property(
	 *     title="inactive",
	 *     description="The Organization's inactive",
	 *     format="boolean"
	 * )
	 *
	 * @method static Organization whereInactive($value)
	 * @property boolean $inactive
	 */
	protected $inactive;
	/**
	 *
	 * @OAS\Property(
	 *     title="url_facebook",
	 *     description="The URL to the organization's facebook page",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereUrlFacebook($value)
	 * @property $url_facebook
	 */
	protected $url_facebook;
	/**
	 *
	 * @OAS\Property(
	 *     title="url_website",
	 *     description="The url to the Organization's website",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereUrlWebsite($value)
	 * @property $url_website
	 */
	protected $url_website;
	/**
	 *
	 * @OAS\Property(
	 *     title="url_donate",
	 *     description="The url to the organization's donation page",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereUrlDonate($value)
	 * @property $url_donate
	 */
	protected $url_donate;
	/**
	 *
	 * @OAS\Property(
	 *     title="url_twitter",
	 *     description="The url to the organization's twitter page",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereUrlTwitter($value)
	 * @property $url_twitter
	 */
	protected $url_twitter;
	/**
	 *
	 * @OAS\Property(
	 *     title="address",
	 *     description="The Organization's address",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereAddress($value)
	 * @property $address
	 */
	protected $address;
	/**
	 *
	 * @OAS\Property(
	 *     title="address2",
	 *     description="The Organization's second line of the address",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereAddress2($value)
	 * @property $address2
	 */
	protected $address2;
	/**
	 *
	 * @OAS\Property(
	 *     title="city",
	 *     description="The organization's city",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereCity($value)
	 * @property $city
	 */
	protected $city;
	/**
	 *
	 * @OAS\Property(
	 *     title="state",
	 *     description="The Organization's state",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereState($value)
	 * @property $state
	 */
	protected $state;
	/**
	 *
	 * @OAS\Property(
	 *     title="country",
	 *     description="ThThe Organization's country",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereCountry($value)
	 * @property $country
	 */
	protected $country;
	/**
	 *
	 * @OAS\Property(
	 *     title="zip",
	 *     description="The Organization's zip",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereZip($value)
	 * @property $zip
	 */
	protected $zip;
	/**
	 *
	 * @OAS\Property(
	 *     title="phone",
	 *     description="The Organization's phone number",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization wherePhone($value)
	 * @property $phone
	 */
	protected $phone;
	/**
	 *
	 * @OAS\Property(
	 *     title="email",
	 *     description="The Organization's email address",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 * @method static Organization whereEmail($value)
	 * @property $email
	 */
	protected $email;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['logo','facebook','twitter','id','code'];

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
		return $this->HasOne(OrganizationLogo::class,'organization_id','id');
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
