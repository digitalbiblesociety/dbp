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
 * @mixin \Eloquent
 *
 * @property-read Role $archivist
 * @property-read Role $authorizedArchivist
 * @property-read \App\Models\Organization\Organization[] $organizations
 * @property-read \App\Models\Bible\BibleFileSetPermission[] $permissions
 * @property-read Role $role
 * @property-read Role[] $roles
 * @property-read Note[] $notes
 * @property-read Role $admin
 * @property-read Role $canCreateUsers
 * @property-read Key[] $key
 * @property-read Project[] $projects
 * @property-read Key[] $keys
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The User model communicates information about everyone involved with the project",
 *     title="User",
 *     @OAS\Xml(name="User")
 * )
 *
 */
class User extends Authenticatable
{
	public $incrementing = false;
	public $table = 'users';
	public $keyType = 'string';
    use Notifiable;

	/**
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   type="string",
	 *   description="The unique id for the user",
	 *   maxLength=64
	 * )
	 *
	 * @method static User whereId($value)
	 * @property string $id
	 */
	protected $id;

	/**
	 *
	 * @OAS\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The name of the user",
	 *   maxLength=191
	 * )
	 *
	 * @method static User whereName($value)
	 * @property string $name
	 */
	protected $name;

	/**
	 *
	 * @OAS\Property(
	 *   title="password",
	 *   type="string",
	 *   description="The password for the user's account",
	 *   format="password",
	 *   maxLength=191
	 * )
	 *
	 * @method static User wherePassword($value)
	 * @property string $password
	 */
	protected $password;

	/**
	 *
	 * @OAS\Property(
	 *   title="nickname",
	 *   type="string",
	 *   description="The preferred name for the user"
	 * )
	 *
	 * @method static User whereNickname($value)
	 * @property string $nickname
	 */
	protected $nickname;

	/**
	 *
	 * @OAS\Property(
	 *   title="avatar",
	 *   type="string",
	 *   description="The user's profile picture"
	 * )
	 *
	 * @method static User whereAvatar($value)
	 * @property string $avatar
	 */
	protected $avatar;

	/**
	 *
	 * @OAS\Property(
	 *   title="email",
	 *   type="string",
	 *   description="The user's email address"
	 * )
	 *
	 * @method static User whereEmail($value)
	 * @property string $email
	 */
	protected $email;

	/**
	 *
	 * @OAS\Property(
	 *   title="verified",
	 *   type="boolean",
	 *   description="If the user has verified the email address they've provided or if they're connected via a social account",
	 *   default=false
	 * )
	 *
	 * @method static User whereVerified($value)
	 * @property boolean $verified
	 */
	protected $verified;

	/**
	 *
	 * @OAS\Property(
	 *   title="email_token",
	 *   type="string",
	 *   description="The token sent to the user to verify that user's email"
	 * )
	 *
	 * @method static User whereEmailToken($value)
	 * @property string $email_token
	 */
	protected $email_token;

    /**
     * The attributes that are mass assignable.
     *
     * @var array $fillable
     */
    protected $fillable = ['id','name', 'nickname', 'avatar', 'verified', 'email', 'password', 'email_token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function keys()
	{
		return $this->hasMany(Key::class,'user_id','id');
	}

	/**
	 *
	 * Account Relations for the User
	 *
	 * @property-read Account $bitbucket
	 * @property-read Account $github
	 * @property-read Account $google
	 * @property-read Account[] $accounts
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
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

	public function role($role = null,$organization = null)
	{
		return $this->HasOne(Role::class)->where('role',$role)->when($organization, function($q) use ($organization) {
			$q->where('organization_id', '=', $organization);
		});
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
