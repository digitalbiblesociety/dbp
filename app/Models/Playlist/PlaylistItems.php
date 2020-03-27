<?php

namespace App\Models\Playlist;

use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleVerse;
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
 * @property int $verses
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
    protected $fillable   = ['playlist_id', 'fileset_id', 'book_id', 'chapter_start', 'chapter_end', 'verse_start', 'verse_end', 'duration', 'verses'];
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

    /**
     *
     * @OA\Property(
     *   title="verses",
     *   type="integer",
     *   description="The playlist item verses count"
     * )
     *
     */
    protected $verses;

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

    public function calculateDuration()
    {
        $playlist_item = (object) $this->attributes;
        $this->attributes['duration'] = $this->getDuration($playlist_item) ?? 0;
        return $this;
    }



    private function getDuration($playlist_item)
    {
        $fileset = BibleFileset::whereId($playlist_item->fileset_id)->first();
        if (!$fileset) {
            return 0;
        }

        $bible_files = BibleFile::with('streamBandwidth.transportStreamTS')->with('streamBandwidth.transportStreamBytes')->where([
            'hash_id' => $fileset->hash_id,
            'book_id' => $playlist_item->book_id,
        ])
            ->where('chapter_start', '>=', $playlist_item->chapter_start)
            ->where('chapter_start', '<=', $playlist_item->chapter_end)
            ->get();
        $duration = 0;
        if ($fileset->set_type_code === 'audio_stream' || $fileset->set_type_code === 'audio_drama_stream') {
            foreach ($bible_files as $bible_file) {
                $currentBandwidth = $bible_file->streamBandwidth->first();
                $transportStream = sizeof($currentBandwidth->transportStreamBytes) ? $currentBandwidth->transportStreamBytes : $currentBandwidth->transportStreamTS;
                if ($playlist_item->verse_end && $playlist_item->verse_start) {
                    $transportStream = $this->processVersesOnTransportStream($playlist_item, $transportStream, $bible_file);
                }

                foreach ($transportStream as $stream) {
                    $duration += $stream->runtime;
                }
            }
        } else {
            foreach ($bible_files as $bible_file) {
                $duration += $bible_file->duration ?? 180;
            }
        }

        return $duration;
    }

    private function processVersesOnTransportStream($item, $transportStream, $bible_file)
    {
        if ($item->chapter_end  === $item->chapter_start) {
            $transportStream = $transportStream->splice(1, $item->verse_end)->all();
            return collect($transportStream)->slice($item->verse_start - 1)->all();
        }

        $transportStream = $transportStream->splice(1)->all();
        if ($bible_file->chapter_start === $item->chapter_start) {
            return collect($transportStream)->slice($item->verse_start - 1)->all();
        }
        if ($bible_file->chapter_start === $item->chapter_end) {
            return collect($transportStream)->splice(0, $item->verse_end)->all();
        }

        return $transportStream;
    }


    protected $appends = ['completed', 'full_chapter', 'path'];


    public function calculateVerses()
    {
        $fileset = BibleFileset::where('id', $this['fileset_id'])
            ->whereNotIn('set_type_code', ['text_format'])
            ->first();
        $bible_files = BibleFile::where('hash_id', $fileset->hash_id)
            ->where([
                ['book_id', $this['book_id']],
                ['chapter_start', '>=', $this['chapter_start']],
                ['chapter_start', '<', $this['chapter_end']],
            ])
            ->get();
        $verses_middle = 0;
        foreach ($bible_files as $bible_file) {
            $verses_middle += ($bible_file->verse_start - 1) + $bible_file->verse_end;
        }
        if (!$this['verse_start'] && !$this['verse_end']) {
            $verses = $verses_middle;
        } else {
            $verses = $verses_middle - ($this['verse_start'] - 1) + $this['verse_end'];
        }

        // Try to get the verse count from the bible_verses table
        if (!$verses) {
            $text_fileset = $fileset->bible->first()->filesets->where('set_type_code', 'text_plain')->first();
            if ($text_fileset) {
                $verses = BibleVerse::where('hash_id', $text_fileset->hash_id)
                    ->where([
                        ['book_id', $this['book_id']],
                        ['chapter', '>=', $this['chapter_start']],
                        ['chapter', '<=', $this['chapter_end']],
                    ])
                    ->count();
            }
        }

        $this->attributes['verses'] =  $verses;
        return $this;
    }

    public function getVerseText()
    {
        $fileset = BibleFileset::where('id', $this['fileset_id'])->first();
        $text_fileset = $fileset->bible->first()->filesets->where('set_type_code', 'text_plain')->first();
        $verses = null;
        if ($text_fileset) {
            $where = [
                ['book_id', $this['book_id']],
                ['chapter', '>=', $this['chapter_start']],
                ['chapter', '<=', $this['chapter_end']],
            ];
            if ($this['verse_start'] && $this['verse_end']) {
                $where[] = ['verse_start', '>=', $this['verse_start']];
                $where[] = ['verse_end', '<=', $this['verse_end']];
            }
            $verses = BibleVerse::where('hash_id', $text_fileset->hash_id)
                ->where($where)
                ->get()->pluck('verse_text');
        }

        return $verses;
    }

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

    /**
     * @OA\Property(
     *   property="full_chapter",
     *   title="full_chapter",
     *   type="boolean",
     *   description="If the playlist item is a full chapter item"
     * )
     */
    public function getFullChapterAttribute()
    {
        return (bool) !$this->attributes['verse_start'] && !$this->attributes['verse_end'];
    }

    /**
     * @OA\Property(
     *   property="path",
     *   title="path",
     *   type="string",
     *   description="Hls path of the playlist item"
     * )
     */
    public function getPathAttribute()
    {
        return route('v4_playlists_item.hls', ['playlist_item_id'  => $this->attributes['id'], 'v' => checkParam('v'), 'key' => checkParam('key')]);
    }

    public function fileset()
    {
        return $this->belongsTo(BibleFileset::class);
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
