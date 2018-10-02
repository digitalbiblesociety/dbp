<?php

namespace App\Models\User\Study;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Note
 * @mixin \Eloquent
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
    protected $table = "user_notes";
    protected $hidden = ['user_id','project_id'];
    protected $fillable = ['id','user_id','bible_id','book_id','project_id','chapter','verse_start','verse_end','bookmark','notes','created_at','updated_at'];

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
	 * @OA\Property(ref="#/components/schemas/BibleFile/properties/verse_end")
	 * @method static Note whereVerseEnd($value)
	 * @property int|null $verse_end
	 */
	protected $verse_end;

	/**
	 *
	 * @OA\Property(
	 *   title="bookmark",
	 *   type="integer",
	 *   description="The unique incrementing id for each NoteTag"
	 * )
	 *
	 * @method static Note whereBookmark($value)
	 * @property int $bookmark
	 */
	protected $bookmark;

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
	 *   title="reference_id",
	 *   type="string",
	 *   description="The unique incrementing id for each NoteTag"
	 * )
	 *
	 * @method static Note whereReferenceId($value)
	 * @property string|null $reference_id
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
	 * @property string|null $notes
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
	 * @property-read NoteTag[] $tags
	 *
	 */
    public function tags()
    {
    	return $this->hasMany(NoteTag::class,'note_id','id');
    }

}
