<?php

namespace App\Models\User;

use App\Models\Profile;
use App\Models\Social;
use App\Models\User\Study\Bookmark;
use App\Models\User\Study\Highlight;
use App\Models\User\Study\Note;
use App\Models\User\RoleUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

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
 * @property integer $id
 * @property string $name
 * @property string $password
 * @property string $nickname
 * @property string $avatar
 * @property string $email
 * @property boolean $verified
 * @property string $email_token
 *
 * @OA\Schema (
 *     type="object",
 *     description="The User model communicates information about everyone involved with the project",
 *     title="User",
 *     @OA\Xml(name="User")
 * )
 *
 */
class User extends Model implements Authenticatable
{
	use HasRoleAndPermission;
	use Notifiable;
	use SoftDeletes;
	use AuthenticableTrait;

	protected $connection = 'dbp_users';
	protected $table     = 'users';
	protected $fillable  = ['name', 'first_name', 'last_name', 'email', 'password', 'activated', 'token', 'signup_ip_address', 'signup_confirmation_ip_address', 'signup_sm_ip_address', 'admin_ip_address', 'updated_ip_address', 'deleted_ip_address'];
	protected $hidden    = ['password', 'remember_token', 'activated', 'token'];
	protected $dates     = ['deleted_at'];

	/**
	 *
	 * @OA\Property(
	 *   title="id",
	 *   type="integer",
	 *   description="The unique id for the user"
	 * )
	 *
	 * @method static User whereId($value)
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
		return $this->hasMany(Account::class);
	}

	public function github()
	{
		return $this->hasOne(Account::class)->where('provider','github');
	}

	public function google()
	{
		return $this->hasOne(Account::class)->where('provider','google');
	}

	public function bitbucket()
	{
		return $this->hasOne(Account::class)->where('provider','bitbucket');
	}

	public function notes()
	{
		return $this->hasMany(Note::class);
	}

	public function bookmarks()
	{
		return $this->hasMany(Bookmark::class);
	}

	public function highlights()
	{
		return $this->hasMany(Highlight::class);
	}

	public function projects()
	{
		return $this->belongsToMany(Project::class, 'project_members')->withPivot('role','subscribed');
	}

	public function projectMembers()
	{
		return $this->hasMany(ProjectMember::class);
	}

	// Roles

	public function authorizedArchivist($id = null)
	{
		return $this->hasOne(Role::class)->where('name','archivist')->where('organization_id',$id);
	}

	public function role($role = null,$organization = null)
	{
		return $this->hasOne(Role::class)->where('role_id',$role)->when($organization, function($q) use ($organization) {
			$q->where('organization_id', '=', $organization);
		});
	}

	public function organizations()
	{
		return $this->hasManyThrough(Organization::class, RoleUser::class, 'user_id', 'id', 'id', 'organization_id');
	}

	public function permissions()
	{
		return $this->hasMany(AccessGroup::class);
	}

	public function social()
	{
		return $this->hasMany(Social::class);
	}

	public function profile()
	{
		return $this->hasOne(Profile::class,'user_id','id');
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
