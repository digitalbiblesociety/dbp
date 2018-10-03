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
 * @method static Builder|BibleOrganization whereBibleId($value)
 * @method static Builder|BibleOrganization whereBibleVariationId($value)
 * @method static Builder|BibleOrganization whereCreatedAt($value)
 * @method static Builder|BibleOrganization whereOrganizationId($value)
 * @method static Builder|BibleOrganization whereRelationshipType($value)
 * @method static Builder|BibleOrganization whereUpdatedAt($value)
 */
class BibleOrganization extends Model
{
	protected $connection = 'dbp';
    protected $table = 'bible_organizations';
    public $timestamps = false;
    public $incrementing = false;

	public function bible()
	{
		return $this->belongsTo(Bible::class,'bible_id','id');
	}

}
