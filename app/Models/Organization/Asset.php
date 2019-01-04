<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organization\Bucket
 *
 * @OA\Schema (
 *     type="object",
 *     description="Asset",
 *     title="Asset",
 *     @OA\Xml(name="Asset")
 * )
 *
 * @property-read \App\Models\Organization\Organization $organization
 * @mixin \Eloquent
 * @property int $hidden
 */
class Asset extends Model
{
    protected $connection = 'dbp';
    public $incrementing = false;
    public $keyType = 'string';
    public $hidden = ['created_at','updated_at'];
    public $fillable = ['id','organization_id','hidden','asset_type','base_name','protocol'];

    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The id of the Bucket",
     *   maxLength=64,
     *   minLength=24
     * )
     *
     * @method static Asset whereId($value)
     * @property string $id
     *
     */
    protected $id;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Organization/properties/id")
     *
     * @method static Asset whereOrganizationId($value)
     * @property int $organization_id
     *
     */
    protected $organization_id;

    protected $asset_type;

    /**
     *
     * @OA\Property(
     *   title="created_at",
     *   type="string",
     *   description="The timestamp at which the bucket was created",
     * )
     *
     * @method static Asset whereCreatedAt($value)
     * @property Carbon $created_at
     *
     */
    protected $created_at;
    /**
     *
     * @OA\Property(
     *   title="updated_at",
     *   type="string",
     *   description="The timestamp at which the bucket was last updated",
     * )
     *
     * @method static Asset whereUpdatedAt($value)
     * @property Carbon $updated_at
     *
     */
    protected $updated_at;

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
