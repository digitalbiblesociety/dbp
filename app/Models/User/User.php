<?php

namespace App\Models\User;

use App\Models\Organization\Organization;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\User\Role;
use App\Models\User\Account;
use App\Traits\Uuids;

/**
 * App\Models\User\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Account[] $accounts
 * @property-read \App\Models\User\Role $archivist
 * @property-read \App\Models\User\Role $authorizedArchivist
 * @property-read \App\Models\User\Account $bitbucket
 * @property-read \App\Models\User\Account $github
 * @property-read \App\Models\User\Account $google
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization\Organization[] $organizations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileSetPermission[] $permissions
 * @property-read \App\Models\User\Role $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Role[] $roles
 * @mixin \Eloquent
 * @property string $id
 * @property string|null $name
 * @property string|null $password
 * @property string|null $nickname
 * @property string|null $avatar
 * @property string|null $email
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Note[] $notes
 * @property-read \App\Models\User\Role $admin
 * @property-read \App\Models\User\Role $canCreateUsers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Key[] $key
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Project[] $projects
 */
class User extends Authenticatable
{
	public $incrementing = false;
	public $keyType = 'string';
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function key()
	{
		return $this->HasMany(Key::class);
	}

	public function accounts()
	{
		return $this->HasMany(Account::class);
	}

	public function github()
	{
		return $this->HasOne(Account::class)->where('provider','github');
	}

	public function google()
	{
		return $this->HasOne(Account::class)->where('provider','google');
	}

	public function bitbucket()
	{
		return $this->HasOne(Account::class)->where('provider','bitbucket');
	}

	public function roles()
	{
		return $this->HasMany(Role::class);
	}

	public function notes()
	{
		return $this->HasMany(Note::class);
	}

	public function projects()
	{
		return $this->HasMany(Project::class);
	}

	// Roles

	public function admin()
	{
		return $this->hasOne(Role::class)->where('role','admin');
	}

	public function canCreateUsers()
	{
		return $this->hasOne(Role::class)->where('role','admin')->OrWhere('role','user_creator');
	}

	public function archivist()
	{
		return $this->hasOne(Role::class)->where('role','archivist');
	}

	public function authorizedArchivist($id = null)
	{
		return $this->hasOne(Role::class)->where('role','archivist')->where('organization_id',$id);
	}

	public function role($role = null)
	{
		return $this->HasOne(Role::class)->where('role',$role);
	}

	public function organizations()
	{
		return $this->HasManyThrough(Organization::class, Role::class, 'user_id', 'id', 'id', 'organization_id');
	}

	public function permissions()
	{
		return $this->hasMany(Access::class);
	}

}
