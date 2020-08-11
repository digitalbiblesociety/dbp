<?php

namespace App\Models\Playlist;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\User\User;

/**
 * App\Models\Playlist
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $name
 * @property string $user_id
 * @property bool $featured
 * @property bool $draft
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 *
 * @OA\Schema (
 *     type="object",
 *     description="The User created Playlist",
 *     title="Playlist"
 * )
 *
 */
class Playlist extends Model
{
    use SoftDeletes;

    protected $connection = 'dbp_users';
    public $table         = 'user_playlists';
    protected $fillable   = ['user_id', 'name', 'external_content', 'draft'];
    protected $hidden     = ['user_id', 'deleted_at', 'plan_id', 'language_id'];
    protected $dates      = ['deleted_at'];
    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="integer",
     *   description="The playlist id",
     *   minimum=0
     * )
     *
     */
    protected $id;
    /**
     *
     * @OA\Property(
     *   title="name",
     *   type="string",
     *   description="The name of the playlist"
     * )
     *
     */
    protected $external_content;
    /**
     *
     * @OA\Property(
     *   title="external_content",
     *   type="string",
     *   description="The url to external content"
     * )
     *
     */
    protected $name;
    /**
     *
     * @OA\Property(
     *   title="user_id",
     *   type="string",
     *   description="The user that created the playlist"
     * )
     *
     */
    protected $user_id;
    /**
     *
     * @OA\Property(
     *   title="featured",
     *   type="boolean",
     *   description="If the playlist is featured"
     * )
     *
     */
    protected $featured;
    /** @OA\Property(
     *   title="updated_at",
     *   type="string",
     *   description="The timestamp the playlist was last updated at",
     *   nullable=true
     * )
     *
     * @method static Note whereUpdatedAt($value)
     * @public Carbon|null $updated_at
     */
    protected $updated_at;
    /**
     *
     * @OA\Property(
     *   title="draft",
     *   type="boolean",
     *   description="If the playlist is draft"
     * )
     *
     */
    protected $draft;
    /**
     *
     * @OA\Property(
     *   title="created_at",
     *   type="string",
     *   description="The timestamp the playlist was created at"
     * )
     *
     * @method static Note whereCreatedAt($value)
     * @public Carbon $created_at
     */
    protected $created_at;
    protected $deleted_at;
    protected $appends = ['verses'];

    public function getFeaturedAttribute($featured)
    {
        return (bool) $featured;
    }

    public function getDraftAttribute($draft)
    {
        return (bool) $draft;
    }

    /**
     *
     * @OA\Property(
     *   title="verses",
     *   type="integer",
     *   description="The playlist verses count"
     * )
     *
     */
    public function getVersesAttribute()
    {
        return PlaylistItems::where('playlist_id', $this['id'])->get()->sum('verses');
    }

    /**
     *
     * @OA\Property(
     *   property="following",
     *   title="following",
     *   type="boolean",
     *   description="If the current user follows the playlist"
     * )
     *
     */
    public function getFollowingAttribute($following)
    {
        return (bool) $following;
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name');
    }

    public function items()
    {
        return $this->hasMany(PlaylistItems::class)->orderBy('order_column');
    }
}
