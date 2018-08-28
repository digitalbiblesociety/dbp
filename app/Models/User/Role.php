<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\Organization\Organization;
/**
 * App\Models\User\Role
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The User's Role model",
 *     title="Role",
 *     @OA\Xml(name="Role")
 * )
 *
 */
class Role extends Model
{
	protected $connection = 'dbp_users';
	protected $table = 'role_user';
	public $incrementing = false;
	public $timestamps = true;
	public $fillable = ['organization_id','user_id','role'];

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/User/properties/id"))
	 *
	 * @method static Role whereUserId($value)
	 * @protected $user_id
	 */
	protected $user_id;
	/**
	 *
	 * @OA\Property(
	 *   title="role",
	 *   type="string",
	 *   description="The user's role",
	 *   maxLength=191
	 * )
	 *
	 * @method static Role whereRole($value)
	 * @protected $role
	 */
	protected $role;
	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Organization/properties/id"))
	 *
	 * @method static Role whereOrganizationId($value)
	 * @protected $organization_id
	 */
	protected $organization_id;

	/**

	 *
	 * @OA\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The timestamp the user was last updated at",
	 *   minimum=0
	 * )
	 *
	 * @property \Carbon\Carbon|null $created_at
     * @property \Carbon\Carbon|null $updated_at
	 */
	protected $updated_at;

	/**
	 *
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function role()
	{
		return $this->BelongsTo(User::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function organization()
	{
		return $this->BelongsTo(Organization::class);
	}

}
