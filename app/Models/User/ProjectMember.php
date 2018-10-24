<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectMember
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Project member's model",
 *     title="ProjectMember",
 *     @OA\Xml(name="ProjectMember")
 * )
 *
 *
 */
class ProjectMember extends Model
{
	protected $connection = 'dbp_users';
	protected $table = 'dbp_users.project_members';
    protected $fillable = ['project_id','role','subscribed','token'];
    public $incrementing = false;
    public $keyType = 'string';
    public $primaryKey = 'user_id';
    public $timestamps = false;

	/**
	 *
     * @OA\Property(
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
     * @OA\Property(
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
     * @OA\Property(
     *   title="Role ID",
     *   type="integer",
     *   description="The incrementing ID for the account",
     *   minimum=0,
     *   example="4"
     * )
	 *
	 */
	protected $role_id;
	/**
	 *
     * @OA\Property(
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

	public function project()
	{
		return $this->belongsTo(Project::class);
	}

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

}
