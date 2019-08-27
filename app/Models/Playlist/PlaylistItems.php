<?php

namespace App\Models\Playlist;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * App\Models\Playlist
 * @mixin \Eloquent
 * 
 * @property int $id
 * @property int $playlist_id
 * @property string $fileset_id
 * @property string $book_id
 * @property int $chapter_start
 * @property int $chapter_end
 * @property int $verse_start
 * @property int $verse_end
 * @property int $duration
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Playlist Item",
 *     title="Playlist Item"
 * )
 *
 */

class PlaylistItems extends Model implements Sortable
{
    use SortableTrait;

    protected $connection = 'dbp_users';
    public $table         = 'playlist_items';
    protected $fillable   = ['playlist_id', 'fileset_id', 'book_id', 'chapter_start', 'chapter_end', 'verse_start', 'verse_end', 'duration'];
    protected $hidden     = ['playlist_id', 'created_at', 'updated_at', 'order_column'];

    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="integer",
     *   description="The playlist item id"
     * )
     *
     */
    protected $id;

    /**
     *
     * @OA\Property(
     *   title="playlist_id",
     *   type="integer",
     *   description="The playlist id"
     * )
     *
     */
    protected $playlist_id;

    /**
     *
     * @OA\Property(
     *   title="fileset_id",
     *   type="string",
     *   description="The fileset id"
     * )
     *
     */
    protected $fileset_id;
    /**
     *
     * @OA\Property(
     *   title="book_id",
     *   type="string",
     *   description="The book_id",
     * )
     *
     */
    protected $book_id;
    /**
     *
     * @OA\Property(
     *   title="chapter_start",
     *   type="integer",
     *   description="The chapter_start",
     *   minimum=0,
     *   maximum=150,
     *   example=4
     * )
     *
     */
    protected $chapter_start;
    /**
     *
     * @OA\Property(
     *   title="chapter_end",
     *   type="integer",
     *   description="If the Bible File spans multiple chapters this field indicates the last chapter of the selection",
     *   nullable=true,
     *   minimum=0,
     *   maximum=150,
     *   example=5
     * )
     *
     */
    protected $chapter_end;
    /**
     *
     * @OA\Property(
     *   title="verse_start",
     *   type="integer",
     *   description="The starting verse at which the BibleFile reference begins",
     *   minimum=1,
     *   maximum=176,
     *   example=5
     * )
     *
     */
    protected $verse_start;

    /**
     *
     * @OA\Property(
     *   title="verse_end",
     *   type="integer",
     *   description="If the Bible File spans multiple verses this value will indicate the last verse in that reference. This value is inclusive, so for the reference John 1:1-4. The value would be 4 and the reference would contain verse 4.",
     *   nullable=true,
     *   minimum=1,
     *   maximum=176,
     *   example=5
     * )
     *
     */
    protected $verse_end;

    /**
     *
     * @OA\Property(
     *   title="duration",
     *   type="integer",
     *   description="The playlist item calculated duration"
     * )
     *
     */
    protected $duration;

    /** @OA\Property(
     *   title="updated_at",
     *   type="string",
     *   description="The timestamp the playlist item was last updated at",
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
     *   title="created_at",
     *   type="string",
     *   description="The timestamp the playlist item was created at"
     * )
     *
     * @method static Note whereCreatedAt($value)
     * @public Carbon $created_at
     */
    protected $created_at;

    protected $appends = array('completed');

    /**
     * @OA\Property(
     *   property="completed",
     *   title="completed",
     *   type="boolean",
     *   description="If the playlist item is completed"
     * )
     */
    public function getCompletedAttribute()
    {
        $user = Auth::user();
        if (empty($user)) {
            return false;
        }

        $complete = PlaylistItemsComplete::where('playlist_item_id', $this->attributes['id'])
            ->where('user_id', $user->id)->first();

        return !empty($complete);
    }

    public function complete()
    {
        $user = Auth::user();
        $completed_item = PlaylistItemsComplete::firstOrNew([
            'user_id'               => $user->id,
            'playlist_item_id'      => $this['id']
        ]);
        $completed_item->save();
    }

    public function unComplete()
    {
        $user = Auth::user();
        $completed_item = PlaylistItemsComplete::where('playlist_item_id', $this['id'])
            ->where('user_id', $user->id);
        $completed_item->delete();
    }
}
