<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Highlight
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Highlight model",
 *     title="Highlight",
 *     @OAS\Xml(name="Highlight")
 * )
 *
 */
class Highlight extends Model
{
    public $table = 'user_highlights';
    protected $fillable = ['user_id','bible_id','book_id','project_id','chapter','verse_start','verse_end','highlight_start','highlighted_words','highlighted_color','reference'];

     /**
      *
	  * @OAS\Property(
	  *   title="id",
	  *   type="integer",
	  *   description="The highlight id",
      *   minimum=0
	  * )
      *
      * @method static Highlight whereId($value)
      * @property int $id
      */
     protected $id;
     /**
      *
	  * @OAS\Property(
	  *   title="user_id",
	  *   type="string",
	  *   description="The user that created the highlight"
	  * )
      *
      * @method static Highlight whereUserId($value)
      * @property string $user_id
      */
     protected $user_id;
     /**
      *
      * @OAS\Property(ref="#/components/schemas/BibleFileset/properties/id")
      * @method static Highlight whereBibleId($value)
      * @property string $bible_id
      */
     protected $bible_id;
     /**
      *
      * @OAS\Property(ref="#/components/schemas/Book/properties/id")
      * @method static Highlight whereBookId($value)
      * @property string $book_id
      */
     protected $book_id;
     /**
      *
      * @OAS\Property(ref="#/components/schemas/BibleFile/properties/chapter_start")
      * @method static Highlight whereChapter($value)
      * @property int $chapter
      */
     protected $chapter;
     /**
      *
	  * @OAS\Property(
	  *   title="highlighted_color",
	  *   type="string",
	  *   description="The highlight's highlighted color in hexadecimal notation.",
      *   example="#4488bb",
      *   minLength=3,
      *   maxLength=7
	  * )
      *
      * @method static Highlight whereHighlightedColor($value)
      * @property string|null $highlighted_color
      */
     protected $highlighted_color;
     /**
      *
      * @OAS\Property(ref="#/components/schemas/BibleFile/properties/verse_start")
      * @method static Highlight whereVerseStart($value)
      * @property int $verse_start
      */
     protected $verse_start;

	/**
	 *
	 * @OAS\Property(type="string")
	 * @method static Highlight whereReference($value)
	 * @property int $verse_start
	 */
	protected $reference;

     /**
      *
      * @OAS\Property(ref="#/components/schemas/Project/properties/id")
      * @method static Highlight whereProjectId($value)
      * @property string|null $project_id
      */
     protected $project_id;
     /**
      *
	  * @OAS\Property(
	  *   title="highlight_start",
	  *   type="integer",
	  *   description="The number of words from the beginning of the verse to start the highlight at. For example, if the verse Genesis 1:1 had a `highlight_start` of 4 and a highlighted_words equal to 2. The result would be: In the beginning `[God created]` the heavens and the earth.",
      *   minimum=0
	  * )
      *
      * @method static Highlight whereHighlightStart($value)
      * @property int $highlight_start
      */
     protected $highlight_start;
     /**
      *
	  * @OAS\Property(
	  *   title="highlighted_words",
	  *   type="string",
      *   description="The number of words being highlighted. For example, if the verse Genesis 1:1 had a `highlight_start` of 4 and a highlighted_words equal to 2. The result would be: In the beginning `[God created]` the heavens and the earth.",
	  * )
      *
      * @method static Highlight whereHighlightedWords($value)
      * @property int $highlighted_words
      */
     protected $highlighted_words;
     /**
      *
	  * @OAS\Property(
	  *   title="created_at",
	  *   type="string",
	  *   description="The highlight's created_at timestamp"
	  * )
      *
      * @method static Highlight whereCreatedAt($value)
      * @property \Carbon\Carbon|null $created_at
      */
     protected $created_at;
     /**
      *
	  * @OAS\Property(
	  *   title="updated_at",
	  *   type="string",
	  *   description="The highlight's updated_at timestamp"
	  * )
      *
      * @method static Highlight whereUpdatedAt($value)
      * @property \Carbon\Carbon|null $updated_at
      */
     protected $updated_at;

}
