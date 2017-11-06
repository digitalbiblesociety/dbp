<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use App\Models\Organization\Organization;
/**
 * App\Models\User\Role
 *
 * @property-read \App\Models\Organization\Organization $organization
 * @property-read \App\Models\User\User $role
 * @mixin \Eloquent
 * @property string $user_id
 * @property int $organization_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Role whereUserId($value)
 */
class Role extends Model
{

	protected $table = 'user_roles';
	public $incrementing = false;
	public $timestamps = true;
	public $fillable = ['organization_id','user_id','role'];

	/**
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
