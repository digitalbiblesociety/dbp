<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization\OrganizationTranslation
 *
 * @property string $language_iso
 * @property int $organization_id
 * @property int $vernacular
 * @property int $alt
 * @property string $name
 * @property string|null $description
 * @property string|null $description_short
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Organization\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationTranslation whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationTranslation whereDescriptionShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationTranslation whereLanguageIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationTranslation whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationTranslation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Organization\OrganizationTranslation whereVernacular($value)
 * @mixin \Eloquent
 */
class OrganizationTranslation extends Model
{
    protected $primaryKey = 'organization_id';
    protected $fillable = ['iso', 'name','description'];
    public $incrementing = false;
    protected $hidden = ['created_at','updated_at','organization_id','description'];

    public function organization()
    {
        return $this->BelongsTo(Organization::class);
    }

}
