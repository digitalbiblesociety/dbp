<?php

namespace App\Models\User\Study;

use App\Models\Bible\Bible;
use App\Models\Bible\Book;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Study
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $book_id
 * @property int $chapter
 * @property int $verse_start
 * @property string $user_id
 * @property string $bible_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Note whereId($value)
 * @method static Note whereBookId($value)
 * @method static Note whereChapter($value)
 * @method static Note whereVerseStart($value)
 * @method static Note whereUserId($value)
 * @method static Note whereBibleId($value)
 *
 * @OA\Schema (
 *     type="object",
 *     description="The User created Bookmark",
 *     title="Bookmark",
 *     @OA\Xml(name="Bookmark")
 * )
 *
 */
class Bookmark extends Model
{

	protected $table = 'user_bookmarks';
	protected $fillable = ['id','bible_id', 'user_id', 'book_id', 'chapter', 'verse_start'];

	/**
	 *
	 * @OA\Property(
	 *   title="id",
	 *   type="integer",
	 *   description="The unique incrementing id for each Bookmark",
	 *   minimum=0
	 * )
	 */
	protected $id;

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
	 * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_start")
	 */
	protected $verse_start;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/User/properties/id")
	 */
	protected $user_id;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Bible/properties/id")
	 */
	protected $bible_id;

	/** @OA\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The timestamp the Note was last updated at",
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
	 *   description="The timestamp the note was created at"
	 * )
	 *
	 * @method static Note whereCreatedAt($value)
	 * @public Carbon $created_at
	 */
	protected $created_at;

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

	public function bible()
	{
		return $this->belongsTo(Bible::class);
	}

	public function tags()
	{
		return $this->hasMany(AnnotationTag::class,'bookmark_id','id');
	}


}
