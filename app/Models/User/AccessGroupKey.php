<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\AccessGroupFunction
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Access Group Key",
 *     title="AccessGroupKey",
 *     @OA\Xml(name="AccessGroupKey")
 * )
 *
 */
class AccessGroupKey extends Model
{
	protected $connection = 'dbp_users';
	public $table = 'dbp_users.access_group_keys';
	public $fillable = ['access_group_id','key_id'];

	/**
	 *
	 * @OA\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The name for each access group"
	 * )
	 *
	 * @method static AccessGroupKey whereName($value)
	 * @property string $access_group_id
	 */
	protected $access_group_id;

	/**
	 *
	 * @OA\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The name for each access group"
	 * )
	 *
	 * @method static AccessGroupKey whereName($value)
	 * @property string $key_id
	 */
	protected $key_id;

	public function access()
	{
		return $this->belongsTo(AccessGroup::class,'access_group_id','id');
	}

	public function type()
	{
		return $this->hasManyThrough(AccessType::class, AccessGroupType::class,'id','id','key_id','access_type_id');
	}

	public function user()
	{
		return $this->BelongsTo(Key::class);
	}

}
