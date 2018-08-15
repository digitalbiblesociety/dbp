<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Organization\Organization;

use Illuminate\Database\Eloquent\SoftDeletes;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;

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
 * @OA\Schema (
 *     type="object",
 *     description="The User model communicates information about everyone involved with the project",
 *     title="User",
 *     @OA\Xml(name="User")
 * )
 *
 */
class User extends Authenticatable {

	use HasRoleAndPermission;
	use Notifiable;
	use SoftDeletes;
	protected $connection = 'dbp_users';
	protected $table      = 'users';
	protected $fillable   = ['name', 'first_name', 'last_name', 'email', 'password', 'activated', 'token', 'signup_ip_address', 'signup_confirmation_ip_address', 'signup_sm_ip_address', 'admin_ip_address', 'updated_ip_address', 'deleted_ip_address'];
	protected $hidden     = ['password', 'remember_token', 'activated', 'token'];
	protected $dates      = ['deleted_at'];

	/**
	 *
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
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

	public function notes()
	{
		return $this->HasMany(Note::class);
	}

	public function developer()
	{
		return $this->BelongsToMany(Project::class, 'project_members')->where('role','developer')->orWhere('role','admin')->withPivot('role','subscribed');
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
		return $this->hasMany(AccessGroup::class);
	}

	/**
	 * Build Social Relationships.
	 *
	 * @var array
	 */
	public function social()
	{
		return $this->hasMany('App\Models\Social');
	}

	/**
	 * User Profile Relationships.
	 *
	 * @var array
	 */
	public function profile()
	{
		return $this->hasOne('App\Models\Profile');
	}

	// User Profile Setup - SHould move these to a trait or interface...

	public function profiles()
	{
		return $this->belongsToMany('App\Models\Profile')->withTimestamps();
	}

	public function hasProfile($name)
	{
		foreach ($this->profiles as $profile) {
			if ($profile->name == $name) {
				return true;
			}
		}

		return false;
	}

	public function assignProfile($profile)
	{
		return $this->profiles()->attach($profile);
	}

	public function removeProfile($profile)
	{
		return $this->profiles()->detach($profile);
	}

}
