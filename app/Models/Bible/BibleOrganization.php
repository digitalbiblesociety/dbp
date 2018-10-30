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
 * @method static BibleOrganization whereBibleId($value)
 * @method static BibleOrganization whereBibleVariationId($value)
 * @method static BibleOrganization whereCreatedAt($value)
 * @method static BibleOrganization whereOrganizationId($value)
 * @method static BibleOrganization whereRelationshipType($value)
 * @method static BibleOrganization whereUpdatedAt($value)
 *
 * @OA\Schema (
 *     type="object",
 *     description="BibleOrganization",
 *     title="BibleOrganization",
 *     @OA\Xml(name="BibleOrganization")
 * )
 *
 */
class BibleOrganization extends Model
{
	protected $connection = 'dbp';
    protected $table = 'bible_organizations';
    public $timestamps = false;
    public $incrementing = false;


	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Bible/properties/id")
	 * @var string|null $bible_id
	 */
	protected $bible_id;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Organization/properties/id")
	 * @var
	 */
	protected $organization_id;

	/**
	 *
	 * @OA\Property(
	 *   title="relationship_type",
	 *   type="string",
	 *   description=""
	 * )
	 * @var
	 */
	protected $relationship_type;

	/**
	 *
	 * @OA\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description=""
	 * )
	 * @var
	 */
	protected $created_at;

	/**
	 *
	 * @OA\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description=""
	 * )
	 * @var
	 */
	protected $updated_at;


	public function bible()
	{
		return $this->belongsTo(Bible::class,'bible_id','id');
	}

}
