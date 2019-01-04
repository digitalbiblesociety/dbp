<?php

namespace App\Models\User\Study;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\BibleFileset;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Highlight
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $user_id
 * @property string $bible_id
 * @property string $book_id
 * @property int $chapter
 * @property string|null $highlighted_color
 * @property int $verse_start
 * @property string|null $project_id
 * @property int $highlight_start
 * @property int $highlighted_words
 *
 * @method static Highlight whereId($value)
 * @method static Highlight whereUserId($value)
 * @method static Highlight whereBibleId($value)
 * @method static Highlight whereBookId($value)
 * @method static Highlight whereChapter($value)
 * @method static Highlight whereHighlightedColor($value)
 * @method static Highlight whereVerseStart($value)
 * @method static Highlight whereProjectId($value)
 * @method static Highlight whereHighlightStart($value)
 * @method static Highlight whereHighlightedWords($value)
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Highlight model",
 *     title="Highlight",
 *     @OA\Xml(name="Highlight")
 * )
 *
 */
class Highlight extends Model
{
    protected $connection = 'dbp_users';
    public $table = 'user_highlights';
    protected $fillable = ['user_id','bible_id','book_id','project_id','chapter','verse_start','verse_end','highlight_start','highlighted_words','highlighted_color'];
    protected $hidden = ['user_id','project_id'];

     /**
      *
      * @OA\Property(
      *   title="id",
      *   type="integer",
      *   description="The highlight id",
      *   minimum=0
      * )
      *
      */
    protected $id;
     /**
      *
      * @OA\Property(
      *   title="user_id",
      *   type="string",
      *   description="The user that created the highlight"
      * )
      *
      */
    protected $user_id;
     /**
      *
      * @OA\Property(ref="#/components/schemas/Bible/properties/id")
      */
    protected $bible_id;
     /**
      *
      * @OA\Property(ref="#/components/schemas/Book/properties/id")
      */
    protected $book_id;
     /**
      *
      * @OA\Property(ref="#/components/schemas/BibleFile/properties/chapter_start")
      */
    protected $chapter;
     /**
      *
      * @OA\Property(
      *   title="highlighted_color",
      *   type="string",
      *   description="The highlight's highlighted color in either hex, rgb, or rgba notation.",
      *   example="#4488bb"
      * )
      *
      */
    protected $highlighted_color;
     /**
      *
      * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_start")
      */
    protected $verse_start;

    /**
     *
     * @OA\Property(type="string")
     * @method static Highlight whereReference($value)
     */
    protected $reference;

     /**
      *
      * @OA\Property(ref="#/components/schemas/Project/properties/id")
      */
    protected $project_id;
     /**
      *
      * @OA\Property(
      *   title="highlight_start",
      *   type="integer",
      *   description="The number of words from the beginning of the verse to start the highlight at. For example, if the verse Genesis 1:1 had a `highlight_start` of 4 and a highlighted_words equal to 2. The result would be: In the beginning `[God created]` the heavens and the earth.",
      *   minimum=0
      * )
      *
      */
    protected $highlight_start;
     /**
      *
      * @OA\Property(
      *   title="highlighted_words",
      *   type="string",
      *   description="The number of words being highlighted. For example, if the verse Genesis 1:1 had a `highlight_start` of 4 and a highlighted_words equal to 2. The result would be: In the beginning `[God created]` the heavens and the earth.",
      * )
      *
      */
    protected $highlighted_words;



    public function color()
    {
        return $this->belongsTo(HighlightColor::class, 'highlighted_color', 'id');
    }

    public function bible()
    {
        return $this->belongsTo(Bible::class);
    }

    public function book()
    {
        return $this->hasOne(BibleBook::class, 'book_id', 'book_id');
    }

    public function tags()
    {
        return $this->hasMany(AnnotationTag::class, 'highlight_id', 'id');
    }
}
