<?php

namespace App\Models\User\Study;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Study
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The user created Bookmark",
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
	 *
	 * @method static Note whereId($value)
	 * @property int $id
	 */
	protected $id;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Book/properties/id")
	 * @method static Note whereBookId($value)
	 * @property string $book_id
	 */
	protected $book_id;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/BibleFile/properties/chapter_start")
	 * @method static Note whereChapter($value)
	 * @property int $chapter
	 */
	protected $chapter;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_start")
	 * @method static Note whereVerseStart($value)
	 * @property int $verse_start
	 */
	protected $verse_start;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/User/properties/id")
	 * @method static Note whereUserId($value)
	 * @property string $user_id
	 */
	protected $user_id;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Bible/properties/id")
	 * @method static Note whereBibleId($value)
	 * @property string $bible_id
	 */
	protected $bible_id;

	/**
	 *
	 * @OA\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The timestamp the note was created at"
	 * )
	 *
	 * @method static Note whereCreatedAt($value)
	 * @property Carbon $created_at
	 */
	protected $created_at;

	/**
	 *
	 * @OA\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The timestamp the Note was last updated at",
	 *   nullable=true
	 * )
	 *
	 * @method static Note whereUpdatedAt($value)
	 * @property Carbon|null $updated_at
	 */
	protected $updated_at;

	public function setCreatedAtAttribute()
	{
		return Carbon::now()->toDateString();
	}


	/**
	 *
	 * @property-read User $user
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}


}
