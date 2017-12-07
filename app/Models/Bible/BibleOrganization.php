<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleOrganization
 *
 * @property string|null $bible_id
 * @property string|null $bible_variation_id
 * @property int|null $organization_id
 * @property string $relationship_type
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\Bible\Bible|null $bible
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleOrganization whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleOrganization whereBibleVariationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleOrganization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleOrganization whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleOrganization whereRelationshipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleOrganization whereUpdatedAt($value)
 */
class BibleOrganization extends Model
{
    protected $table = "bible_organizations";
    public $timestamps = false;
    public $incrementing = false;

	public function bible()
	{
		return $this->BelongsTo(Bible::class,'bible_id','id');
	}

}
