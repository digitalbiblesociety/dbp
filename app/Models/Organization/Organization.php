<?php

namespace App\Models\Organization;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFilesetConnection;
use App\Models\Bible\BibleLink;
use App\Models\Language\Language;
use App\Models\Resource\Resource;
use App\Models\Bible\Bible;
use App\Models\User\Role;
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
 * @method static Organization whereId($value)
 * @property int $id
 * @method static Organization whereSlug($value)
 * @property $slug
 * @method static Organization whereAbbreviation($value)
 * @property $abbreviation
 * @method static Organization whereNotes($value)
 * @property $notes
 * @method static Organization wherePrimarycolor($value)
 * @property $primaryColor
 * @method static Organization whereSecondarycolor($value)
 * @property $secondaryColor
 * @method static Organization whereInactive($value)
 * @property boolean $inactive
 * @method static Organization whereUrlFacebook($value)
 * @property $url_facebook
 * @method static Organization whereUrlWebsite($value)
 * @property $url_website
 * @method static Organization whereUrlDonate($value)
 * @property $url_donate
 * @method static Organization whereUrlTwitter($value)
 * @property $url_twitter
 * @method static Organization whereAddress($value)
 * @property $address
 * @method static Organization whereAddress2($value)
 * @property $address2
 * @method static Organization whereCity($value)
 * @property $city
 * @method static Organization whereState($value)
 * @property $state
 * @method static Organization whereCountry($value)
 * @property $country
 * @method static Organization whereZip($value)
 * @property $zip
 * @method static Organization wherePhone($value)
 * @property $phone
 * @method static Organization whereEmail($value)
 * @property $email
 *
 * @OA\Schema (
 *     type="object",
 *     description="Organization",
 *     title="Organization",
 *     @OA\Xml(name="Organization")
 * )
 *
 */
class Organization extends Model
{
    protected $connection = 'dbp';
    // The attributes excluded from the model's JSON form.
    protected $hidden = ['logo','facebook','twitter','id','code','created_at','updated_at','notes'];
    protected $fillable = ['name', 'email', 'password','facebook','twitter','website','address','phone'];

    /**
     *
     * @OA\Property(
     *     title="id",
     *     description="The Organization's incrementing id",
     *     type="integer",
     *     minimum=0
     * )
     *
     */
    protected $id;
    /**
     *
     * @OA\Property(
     *     title="slug",
     *     description="The Organization's slug",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $slug;
    /**
     *
     * @OA\Property(
     *     title="abbreviation",
     *     description="The Organization's abbreviation",
     *     type="string",
     *     maxLength=6,
     *     nullable=true
     * )
     *
     */
    protected $abbreviation;
    /**
     *
     * @OA\Property(
     *     title="notes",
     *     description="Archivist notes about the organization being described",
     *     type="string",
     *     nullable=true
     * )
     *
     */
    protected $notes;
    /**
     *
     * @OA\Property(
     *     title="primaryColor",
     *     description="The Organization's primary color derived from their logo",
     *     type="string",
     *     maxLength=7,
     *     minLength=7,
     *     nullable=true
     * )
     *
     */
    protected $primaryColor;
    /**
     *
     * @OA\Property(
     *     title="secondaryColor",
     *     description="The Organization's secondary color derived from their logo",
     *     type="string",
     *     maxLength=7,
     *     minLength=7,
     *     nullable=true
     * )
     *
     */
    protected $secondaryColor;
    /**
     *
     * @OA\Property(
     *     title="inactive",
     *     description="If the organization has not responded to several attempts to contact this value will be set to true",
     *     type="boolean",
     *     nullable=true
     * )
     *
     */
    protected $inactive;
    /**
     *
     * @OA\Property(
     *     title="url_facebook",
     *     description="The URL to the organization's facebook page",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $url_facebook;
    /**
     *
     * @OA\Property(
     *     title="url_website",
     *     description="The url to the Organization's website",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $url_website;
    /**
     *
     * @OA\Property(
     *     title="url_donate",
     *     description="The url to the organization's donation page",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $url_donate;
    /**
     *
     * @OA\Property(
     *     title="url_twitter",
     *     description="The url to the organization's twitter page",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $url_twitter;
    /**
     *
     * @OA\Property(
     *     title="address",
     *     description="The Organization's address",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $address;
    /**
     *
     * @OA\Property(
     *     title="address2",
     *     description="The Organization's second line of the address",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $address2;
    /**
     *
     * @OA\Property(
     *     title="city",
     *     description="The organization's city",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $city;
    /**
     *
     * @OA\Property(
     *     title="state",
     *     description="The Organization's state",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $state;
    /**
     *
     * @OA\Property(
     *     title="country",
     *     description="ThThe Organization's country",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $country;
    /**
     *
     * @OA\Property(
     *     title="zip",
     *     description="The Organization's zip",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $zip;
    /**
     *
     * @OA\Property(
     *     title="phone",
     *     description="The Organization's phone number",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $phone;
    /**
     *
     * @OA\Property(
     *     title="email",
     *     description="The Organization's email address",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $email;

    public function scopeIncludeMemberResources($query, $membership)
    {
        return $query->when($membership, function ($q) use ($membership) {
            $q->join('organization_relationships', function ($join) use ($membership) {
                $join->on('organizations.id', '=', 'organization_relationships.organization_child_id')
                     ->where('organization_relationships.organization_parent_id', $membership);
            });
        });
    }


    public function translations()
    {
        return $this->hasMany(OrganizationTranslation::class, 'organization_id', 'id');
    }

    public function currentTranslation()
    {
        return $this->hasOne(OrganizationTranslation::class, 'organization_id', 'id')->where('language_id', $GLOBALS['i18n_id']);
    }

    public function vernacularTranslation()
    {
        return $this->hasOne(OrganizationTranslation::class)->where('vernacular', 1);
    }

    public function bibles()
    {
        return $this->belongsToMany(Bible::class, 'bible_organizations');
    }

    public function links()
    {
        return $this->hasMany(BibleLink::class, 'provider', 'slug');
    }

    public function filesets()
    {
        return $this->hasManyThrough(BibleFilesetConnection::class, BibleFileset::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class, 'organization_id', 'id');
    }

    public function logos()
    {
        return $this->hasMany(OrganizationLogo::class);
    }

    public function logo()
    {
        return $this->hasOne(OrganizationLogo::class, 'organization_id', 'id');
    }

    public function logoIcon()
    {
        return $this->hasOne(OrganizationLogo::class, 'organization_id', 'id')->where('icon', 1);
    }

    public function members()
    {
        return $this->hasMany(Role::class);
    }

    public function relationships()
    {
        return $this->hasMany(OrganizationRelationship::class, 'organization_child_id');
    }

    public function memberships()
    {
        return $this->hasMany(OrganizationRelationship::class, 'organization_parent_id');
    }
}
