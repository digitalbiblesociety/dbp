<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\User\Project
 *

 * @property-read User[] $members
 * @property-read Note[] $notes
 * @property-read User[] $users
 * @property-read User[] $admins
 * @mixin \Eloquent
 *
 * @property string $id
 * @property string $name
 * @property string|null $url_avatar
 * @property string|null $url_avatar_icon
 * @property string|null $url_site
 * @property string|null $reset_path
 * @property string|null $description
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $updated_at
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
    use SoftDeletes;

    protected $connection = 'dbp_users';
    protected $table = 'projects';
    protected $fillable = ['id','name','url_avatar','url_avatar_icon','url_site','description','role','reset_path'];
    public $keyType = 'integer';
    public $incrementing = false;

    protected $dates = ['deleted_at'];

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
     */
    protected $description;

    /**
     *
     * @OA\Property(
     *   title="description",
     *   type="string",
     *   description="The day the project was soft deleted"
     * )
     *
     * @method static Project whereDeletedAt($value)
     */
    protected $deleted_at;

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
     */
    protected $updated_at;
/**
 *
 * @property-read Highlight[] $highlights
 */
    protected $highlights;

    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function developers()
    {
        $role = Role::where('slug', 'developer')->first();
        return $this->hasMany(ProjectMember::class)->where('role_id', $role->id);
    }

    public function admins()
    {
        $role = Role::where('slug', 'admin')->first();
        return $this->hasMany(ProjectMember::class)->where('role_id', $role->id);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, ProjectMember::class, 'project_id','id','project_id','user_id');
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
        return $this->hasMany(ProjectOauthProvider::class, 'project_id', 'id');
    }
}
