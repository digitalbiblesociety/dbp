<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\AccessGroupFileset
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Access Group Fileset",
 *     title="AccessGroupFileset",
 *     @OA\Xml(name="AccessGroupFileset")
 * )
 *
 */
class AccessGroupFileset extends Model
{
    protected $connection = 'dbp';
    public $table = 'access_group_filesets';
    public $hidden = ['access_group_id'];
    public $fillable = ['hash_id','access_group_id'];


    /**
     *
     * @OA\Property(ref="#/components/schemas/AccessGroup/properties/id")
     *
     * @method static AccessGroupFileset whereName($value)
     * @property string $access_group_id
     */
    protected $access_group_id;

    /**
     *
     * @OA\Property(ref="#/components/schemas/BibleFileset/properties/id")
     *
     * @method static AccessGroupFileset whereHashId($value)
     * @property string $hash_id
     */
    protected $hash_id;

    public function access()
    {
        return $this->belongsTo(AccessGroup::class, 'access_group_id');
    }
}
