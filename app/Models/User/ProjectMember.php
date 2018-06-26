<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectMember
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Project member's model",
 *     title="ProjectMember",
 *     @OAS\Xml(name="ProjectMember")
 * )
 *
 *
 */
class ProjectMember extends Model
{
    protected $fillable = ['project_id','role','subscribed'];
    public $incrementing = false;
    public $keyType = 'string';
    public $primaryKey = 'user_id';

	/**
	 *
     * @OAS\Property(
     *   title="user_id",
     *   type="integer",
     *   description="The incrementing ID for the account",
     *   minimum=0,
     *   example="4"
     * )
	 *
	 */
	protected $user_id;
	/**
	 *
     * @OAS\Property(
     *   title="project_id",
     *   type="integer",
     *   description="The incrementing ID for the account",
     *   minimum=0,
     *   example="4"
     * )
	 *
	 */
	protected $project_id;
	/**
	 *
     * @OAS\Property(
     *   title="role",
     *   type="integer",
     *   description="The incrementing ID for the account",
     *   minimum=0,
     *   example="4"
     * )
	 *
	 */
	protected $role;
	/**
	 *
     * @OAS\Property(
     *   title="subscribed",
     *   type="integer",
     *   description="The incrementing ID for the account",
     *   minimum=0,
     *   example="4"
     * )
	 *
	 */
	protected $subscribed;


	public function user()
	{
		return $this->hasOne(User::class,'id','user_id');
	}
}
