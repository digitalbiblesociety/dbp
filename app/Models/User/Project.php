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
 * @OAS\Schema (
 *     type="object",
 *     description="The Project's model",
 *     title="Project",
 *     @OAS\Xml(name="Project")
 * )
 *
 */
class Project extends Model
{
	protected $fillable = ['id','name','url_avatar','url_avatar_icon','url_site','description','role'];
	public $keyType = 'string';
	public $incrementing = false;

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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

}