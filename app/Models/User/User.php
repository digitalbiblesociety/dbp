<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use App\Models\User\Role;
use App\Models\User\Account;
use App\Traits\Uuids;
use App\Models\Organization\Organization;

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
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {
	public $incrementing = false;
	public $table = 'users';
	public $keyType = 'string';
	use Notifiable, Authenticatable, Authorizable, CanResetPassword;
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


	/**
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   type="string",
	 *   description="The unique id for the user",
	 *   maxLength=64,
	 *   example="4E7Fk8AWGvZCCV7"
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
	 *   maxLength=191,
	 *   example="Elu Thingol"
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
	 *   description="The preferred name for the user or an informal means of addressing them",
	 *   nullable="true",
	 *   example="Elwë"
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
	 *   description="The url to the user's profile picture",
	 *   nullable="true",
	 *   example="https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png"
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
	 *   format="email",
	 *   description="The user's email address",
	 *   example="thingol@valinor.org"
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
	 *   example=true
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
	 *   description="The token sent to the user to verify that user's email",
	 *   example="B95p56KqHrz8D3w"
	 * )
	 *
	 * @method static User whereEmailToken($value)
	 * @property string $email_token
	 */
	protected $email_token;

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function getEmailForPasswordReset()
	{
		return $this->email;
	}

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

	public function developer()
	{
		return $this->BelongsToMany(Project::class, 'project_members')->where('role','developer')->withPivot('role','subscribed');
	}

	public function projects()
	{
		return $this->BelongsToMany(Project::class, 'project_members')->withPivot('role','subscribed');
	}

	public function projectMembers()
	{
		return $this->HasMany(ProjectMember::class);
	}

	// Roles

	public function admin()
	{
		return $this->hasOne(Role::class)->where('role','admin');
	}

	public function canAlterUsers()
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
