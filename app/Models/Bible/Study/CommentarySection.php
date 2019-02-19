<?php

namespace App\Models\Bible\Study;

use Illuminate\Database\Eloquent\Model;
/**
 * App\Models\Bible\Study\CommentarySection
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     title="Commentary Section",
 *     description="The commentary split up by the biblical references it describes",
 *     @OA\Xml(name="CommentarySection")
 * )
 *
 */
class CommentarySection extends Model
{
    protected $connection = 'dbp';

    protected $fillable = ['commentary_id', 'title', 'content', 'book_id', 'chapter_start', 'chapter_end', 'verse_start', 'verse_end'];
    protected $hidden = ['created_at', 'updated_at', 'id', 'commentary_id'];
    /**
     *
     * @OA\Property(ref="#/components/schemas/Commentary/properties/id")
     */
    protected $commentary_id;

    protected $title;
    protected $content;
    protected $book_id;
    protected $chapter_start;
    protected $chapter_end;
    protected $verse_start;
    protected $verse_end;

    public function commentary()
    {
        return $this->belongsTo(Commentary::class);
    }

}
