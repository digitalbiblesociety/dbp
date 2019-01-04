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
    protected $hidden = ['id', 'hash_id'];
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
        return $this->belongsTo(BibleFileset::class, 'hash_id', 'hash_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function concordance()
    {
        return $this->hasMany(BibleConcordance::class);
    }

    public function scopeWithVernacularMetaData($query, $bible)
    {
        $dbp = config('database.connections.dbp.database');
        $query->leftJoin($dbp.'.numeral_system_glyphs as glyph_chapter', function ($join) use ($bible) {
            $join->on('bible_verses.chapter', 'glyph_chapter.value')
             ->where('glyph_chapter.numeral_system_id', $bible->numeral_system_id);
        })
        ->leftJoin($dbp.'.numeral_system_glyphs as glyph_start', function ($join) use ($bible) {
            $join->on('bible_verses.verse_start', 'glyph_start.value')
                 ->where('glyph_start.numeral_system_id', $bible->numeral_system_id);
        })
        ->leftJoin($dbp.'.numeral_system_glyphs as glyph_end', function ($join) use ($bible) {
            $join->on('bible_verses.verse_end', 'glyph_end.value')
                 ->where('glyph_end.numeral_system_id', $bible->numeral_system_id);
        })
        ->leftJoin('books', 'books.id', 'bible_verses.book_id')
        ->leftJoin('bible_books', function ($join) use ($bible) {
            $join->on('bible_verses.book_id', 'bible_books.book_id')->where('bible_books.bible_id', $bible->id);
        });
    }
}
