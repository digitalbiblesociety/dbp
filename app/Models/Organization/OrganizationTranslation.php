<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization\OrganizationTranslation
 *
 * @property-read \App\Models\Organization\Organization $organization
 * @mixin \Eloquent
 *
 * @property int $organization_id
 * @property int $vernacular
 * @property int $alt
 * @property string $name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $language_iso
 * @property string|null $description_short
 *
 * @method static OrganizationTranslation whereOrganizationId($value)
 * @method static OrganizationTranslation whereVernacular($value)
 * @method static OrganizationTranslation whereAlt($value)
 * @method static OrganizationTranslation whereName($value)
 * @method static OrganizationTranslation whereDescription($value)
 * @method static OrganizationTranslation whereCreatedAt($value)
 * @method static OrganizationTranslation whereUpdatedAt($value)
 *
 * @OA\Schema (
 *     type="object",
 *     description="The alternative names in different languages for an organization",
 *     title="Organization Translation",
 *     @OA\Xml(name="OrganizationTranslation")
 * )
 *
 */
class OrganizationTranslation extends Model
{
    protected $connection = 'dbp';
    protected $primaryKey = 'organization_id';
    protected $fillable = ['iso', 'name','description'];
    protected $table = 'organization_translations';
    protected $hidden = ['created_at','updated_at','organization_id','description'];

    /**
     *
     * @OA\Property(
     *     title="language_iso",
     *     description="The iso code for the translation language",
     *     type="string",
     *     minLength=3
     * )
     *
     * @method static OrganizationTranslation whereLanguageIso($value)
     *
     */
    protected $language_iso;

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
    protected $organization_id;

    /**
     *
     * @OA\Property(
     *     title="id",
     *     description="If the current translation is the primary/vernacular translation",
     *     type="boolean"
     * )
     *
     */
    protected $vernacular;
    /**
     *
     * @OA\Property(
     *     title="alt",
     *     description="If the current name is a secondary title for the organization",
     *     type="boolean"
     * )
     *
     */
    protected $alt;
    /**
     *
     * @OA\Property(
     *     title="name",
     *     description="The current translated name for the organization",
     *     type="string",
     *     maxLength=191
     * )
     *
     */
    protected $name;
    /**
     *
     * @OA\Property(
     *     title="description",
     *     description="The current translated description for the organization",
     *     type="string"
     * )
     *
     */
    protected $description;
    /**
     *
     * @OA\Property(
     *     title="description_short",
     *     description="The current translated shortened description for the organization",
     *     type="string"
     * )
     *
     * @method static OrganizationTranslation whereDescriptionShort($value)
     *
     */
    protected $description_short;
    protected $created_at;
    protected $updated_at;


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
