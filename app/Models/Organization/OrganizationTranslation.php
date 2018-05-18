<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization\OrganizationTranslation
 *
 * @property-read \App\Models\Organization\Organization $organization
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The alternative names in different languages for an organization",
 *     title="Organization Translation",
 *     @OAS\Xml(name="OrganizationTranslation")
 * )
 *
 */
class OrganizationTranslation extends Model
{
    protected $primaryKey = 'organization_id';
    protected $fillable = ['iso', 'name','description'];
    protected $table = 'organization_translations';
    //public $incrementing = "false";
    protected $hidden = ['created_at','updated_at','organization_id','description'];

	/**
	 *
	 * @OAS\Property(
	 *     title="language_iso",
	 *     description="The iso code for the translation language",
	 *     type="string",
	 *     minLength=3
	 * )
	 *
	 * @method static OrganizationTranslation whereLanguageIso($value)
	 * @property string $language_iso
	 *
	 */
	protected $language_iso;

	 /**
	  *
	  * @OAS\Property(
	  *     title="id",
	  *     description="The Organization's incrementing id",
	  *     type="integer",
	  *     minimum=0
	  * )
	  *
	  * @method static OrganizationTranslation whereOrganizationId($value)
	  * @property int $organization_id
	  *
	  */
	protected $organization_id;

	 /**
	  *
	  * @OAS\Property(
	  *     title="id",
	  *     description="If the current translation is the primary/vernacular translation",
	  *     type="boolean"
	  * )
	  *
	  * @method static OrganizationTranslation whereVernacular($value)
	  * @property int $vernacular
	  *
	  */
	protected $vernacular;
	 /**
	  *
	  * @OAS\Property(
	  *     title="alt",
	  *     description="If the current name is a secondary title for the organization",
	  *     type="boolean"
	  * )
	  *
	  * @method static OrganizationTranslation whereAlt($value)
	  * @property int $alt
	  *
	  */
	protected $alt;
	 /**
	  *
	  * @OAS\Property(
	  *     title="name",
	  *     description="The current translated name for the organization",
	  *     type="string",
	  *     maxLength=191
	  * )
	  *
	  * @method static OrganizationTranslation whereName($value)
	  * @property string $name
	  *
	  */
	protected $name;
	 /**
	  *
	  * @OAS\Property(
	  *     title="description",
	  *     description="The current translated description for the organization",
	  *     type="string"
	  * )
	  *
	  * @method static OrganizationTranslation whereDescription($value)
	  * @property string|null $description
	  *
	  */
	protected $description;
	/**
	 *
	 * @OAS\Property(
	 *     title="description_short",
	 *     description="The current translated shortened description for the organization",
	 *     type="string"
	 * )
	 *
	 * @method static OrganizationTranslation whereDescriptionShort($value)
	 * @property string|null $description_short
	 *
	 */
	protected $description_short;
	 /**
	  *
	  * @method static OrganizationTranslation whereCreatedAt($value)
	  * @property \Carbon\Carbon|null $created_at
	  */
	protected $created_at;
	 /**
	  *
	  * @method static OrganizationTranslation whereUpdatedAt($value)
	  * @property \Carbon\Carbon|null $updated_at
	  *
	  */
	protected $updated_at;


    public function organization()
    {
        return $this->BelongsTo(Organization::class);
    }

}
