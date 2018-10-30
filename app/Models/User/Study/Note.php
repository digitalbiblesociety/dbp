<?php

namespace App\Models\User\Study;

use App\Models\Bible\Book;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Note
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $book_id
 * @property int $chapter
 * @property int $verse_start
 * @property int|null $verse_end
 * @property string $user_id
 * @property string $bible_id
 * @property string|null $reference_id
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Note's model",
 *     title="Note",
 *     @OA\Xml(name="Note")
 * )
 *
 */
class Note extends Model
{
	protected $connection = 'dbp_users';
    protected $table = 'user_notes';
    protected $hidden = ['user_id'];
    protected $fillable = ['id','user_id','bible_id','book_id','chapter','verse_start','verse_end','notes','created_at','updated_at'];

	/**
	 *
	 * @OA\Property(
	 *   title="id",
	 *   type="integer",
	 *   description="The unique incrementing id for each NoteTag",
	 *   minimum=0
	 * )
	 *
	 * @method static Note whereId($value)
	 */
	protected $id;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Book/properties/id")
	 * @method static Note whereBookId($value)
	 */
	protected $book_id;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/BibleFile/properties/chapter_start")
	 * @method static Note whereChapter($value)
	 */
	protected $chapter;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_start")
	 * @method static Note whereVerseStart($value)
	 */
	protected $verse_start;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_end")
	 * @method static Note whereVerseEnd($value)
	 */
	protected $verse_end;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/User/properties/id")
	 * @method static Note whereUserId($value)
	 */
	protected $user_id;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Bible/properties/id")
	 * @method static Note whereBibleId($value)
	 */
	protected $bible_id;

	/**
	 *
	 * @OA\Property(
	 *   title="reference_id",
	 *   type="string",
	 *   description="The unique incrementing id for each NoteTag"
	 * )
	 *
	 * @method static Note whereReferenceId($value)
	 */
	protected $reference_id;

	/**
	 *
	 * @OA\Property(
	 *   title="notes",
	 *   type="string",
	 *   description="The body of the notes",
	 *   nullable=true
	 * )
	 *
	 * @method static Note whereNotes($value)
	 */
	protected $notes;

	/**
	 *
	 * @OA\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The timestamp the note was created at"
	 * )
	 *
	 * @method static Note whereCreatedAt($value)
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
	 */
	protected $updated_at;

	public function getNotesAttribute($note)
	{
		return decrypt($note);
	}

	/**
	 *
	 * @property-read User $user
	 *
	 */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

	/**
	 *
	 * @property-read AnnotationTag[] $tags
	 *
	 */
    public function tags()
    {
    	return $this->hasMany(AnnotationTag::class,'note_id','id');
    }

    public function book()
    {
    	return $this->belongsTo(Book::class);
    }

}
