<?php

namespace App\Models\Bible\Study;

use App\Models\Bible\Book;
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
     * @OA\Property(ref="#/components/schemas/Commentary/properties/id")
     */
    protected $commentary_id;

    /**
     * @OA\Property(
     *   title="title",
     *   type="string",
     *   description="The section title of the commentary",
     *   maxLength=191
     * )
     */
    protected $title;

    /**
     * @OA\Property(
     *   title="content",
     *   type="string",
     *   description="The content of the commentary section"
     * )
     */
    protected $content;

    /**
     * @OA\Property(ref="#/components/schemas/BibleFile/properties/book_id")
     */
    protected $book_id;

    /**
     * @OA\Property(ref="#/components/schemas/BibleFile/properties/chapter_start")
     */
    protected $chapter_start;

    /**
     * @OA\Property(ref="#/components/schemas/BibleFile/properties/chapter_end")
     */
    protected $chapter_end;

    /**
     * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_start")
     */
    protected $verse_start;

    /**
     * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_end")
     */
    protected $verse_end;

    public function commentary()
    {
        return $this->belongsTo(Commentary::class);
    }

    public function scopeOrderByBook()
    {
        //return $this->
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
