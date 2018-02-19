<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Project
 *
 * @property string $id
 * @property string $name
 * @property string|null $url_avatar
 * @property string|null $url_avatar_icon
 * @property string|null $url_site
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Highlight[] $highlights
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $members
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Note[] $notes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Project whereUrlAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Project whereUrlAvatarIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Project whereUrlSite($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $admins
 */
class Project extends Model
{
	protected $fillable = ['id','name','url_avatar','url_avatar_icon','url_site','description','role'];
	public $keyType = 'string';
	public $incrementing = false;


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