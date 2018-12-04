<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema (
 *     type="object",
 *     description="The BibleVerse model stores the unformatted Bible Text for searching & JSON returned verses",
 *     title="BibleVerse",
 *     @OA\Xml(name="BibleVerse")
 * )
 *
 * @package App\Models\Bible
 */
class BibleVerse extends Model
{
    protected $connection = 'dbp';
    protected $table = 'bible_verses';
    public $timestamps = false;

    /**
     * @OA\Property(
     *   title="id",
     *   type="integer",
     *   description="The incrementing id for the Bible Verse"
     * )
     */
    protected $id;

    /**
     * @OA\Property(ref="#/components/schemas/BibleFileset/properties/hash_id")
     */
    protected $hash_id;

    /**
     * @OA\Property(ref="#/components/schemas/Book/properties/id")
     */
    protected $book_id;

    /**
     * @OA\Property(ref="#/components/schemas/BibleFile/properties/chapter_start")
     */
    protected $chapter;

    /**
     * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_start")
     */
    protected $verse_number;

    /**
     * @OA\Property(
     *   title="verse_text",
     *   type="string",
     *   description="The text of the Bible Verse"
     * )
     */
    protected $verse_text;


    public function fileset()
    {
        return $this->belongsTo(BibleFileset::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function concordance()
    {
        return $this->hasMany(BibleConcordance::class);
    }

    public function referenceScope($book_id,$chapter,$verse)
    {
        return $this->when('book_id', function ($query) use($book_id) {
                $query->where('book_id',$book_id);
            })->when('chapter', function ($query) use($chapter) {
                $query->where('chapter',$chapter);
            })->when('verse_start', function ($query) use($verse) {
                $query->where('verse_start',$verse);
            });
    }

}
