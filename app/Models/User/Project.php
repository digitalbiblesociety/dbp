<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Project
 *

 * @property-read User[] $members
 * @property-read Note[] $notes
 * @property-read User[] $users
 * @property-read User[] $admins
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Project's model",
 *     title="Project",
 *     @OA\Xml(name="Project")
 * )
 *
 */
class Project extends Model
{
	protected $connection = 'dbp_users';
	protected $table = 'projects';
	protected $fillable = ['id','name','url_avatar','url_avatar_icon','url_site','description','role','reset_path'];
	public $keyType = 'string';
	public $incrementing = false;

	/**
	 *
	 * @OA\Property(
	 *   title="id",
	 *   type="string",
	 *   description="The unique id for the project",
	 *   maxLength=24
	 * )
	 *
	 * @method static Project whereId($value)
	 * @property string $id
	 */
	protected $id;
	/**
	 *
	 * @OA\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The name of the project",
	 *   maxLength=191
	 * )
	 *
	 * @method static Project whereName($value)
	 * @property string $name
	 */
	protected $name;
	/**
	 *
	 * @OA\Property(
	 *   title="url_avatar",
	 *   type="string",
	 *   description="The url to the logo / main identifying image for the project",
	 *   maxLength=191
	 * )
	 *
	 * @method static Project whereUrlAvatar($value)
	 * @property string|null $url_avatar
	 */
	protected $url_avatar;
	/**
	 *
	 * @OA\Property(
	 *   title="url_avatar_icon",
	 *   type="string",
	 *   description="The url to the logo / main identifying image for the project in a form that is suitable for small images (less than 100 pixels)",
	 *   maxLength=191
	 * )
	 *
	 * @method static Project whereUrlAvatarIcon($value)
	 * @property string|null $url_avatar_icon
	 */
	protected $url_avatar_icon;
	/**
	 *
	 * @OA\Property(
	 *   title="url_site",
	 *   type="string",
	 *   description="The url to the site that is currently making use of the API",
	 *   maxLength=191
	 * )
	 *
	 * @method static Project whereUrlSite($value)
	 * @property string|null $url_site
	 */
	protected $url_site;

	/**
	 *
	 * @OA\Property(
	 *   title="reset_path",
	 *   type="string",
	 *   description="The url to the location that contains the password reset form. Note this should end with a / and expect the final url path item to be a token. Example: https://example.com/password/reset/ with the expectation of receiving https://example.com/password/reset/{TOKEN_ID}",
	 *   example="https://example.com/password/reset/",
	 *   maxLength=191
	 * )
	 *
	 * @method static Project whereResetPath($value)
	 * @property string|null $reset_path
	 */
	protected $reset_path;

	/**
	 *
	 * @OA\Property(
	 *   title="description",
	 *   type="string",
	 *   description="The description of the project and it's goals"
	 * )
	 *
	 * @method static Project whereDescription($value)
	 * @property string|null $description
	 */
	protected $description;

	/**
	 *
	 * @OA\Property(
	 *   title="description",
	 *   type="string",
	 *   description="The day the project was added to the api"
	 * )
	 *
     * @method static Project whereCreatedAt($value)
     * @property \Carbon\Carbon|null $created_at
    */
	protected $created_at;

	/**
	 *
	 * @OA\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The day the project was last updated"
	 * )
	 *
	 * @method static Project whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 */
	protected $updated_at;
/**
 *
 * @property-read Highlight[] $highlights
 */
	protected $highlights;


	public function admins()
	{
		return $this->belongsToMany(User::class,'project_members')->where('admin',true)->withPivot('role');
	}

    public function members()
    {
    	return $this->belongsToMany(User::class,'project_members')->where('role','!=','user')->withPivot('role');
    }

    public function users()
    {
	    return $this->belongsToMany(User::class,'project_members')->where('role','user')->withPivot('role');
    }

    public function notes()
    {
    	return $this->hasMany(Note::class);
    }

    public function highlights()
    {
    	return $this->hasMany(Highlight::class);
    }

    public function oauthProviders()
    {
    	return $this->hasMany(ProjectOauthProvider::class,'project_id','id');
    }

}