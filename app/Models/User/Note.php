<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Note
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Note's model",
 *     title="Note",
 *     @OAS\Xml(name="Note")
 * )
 *
 */
class Note extends Model
{
    protected $table = "user_notes";
    protected $hidden = ['user_id','project_id'];
    protected $fillable = ['user_id','bible_id','book_id','project_id','chapter','verse_start','verse_end','bookmark','notes'];

	/**
	 *
	 * @OAS\Property(
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
	 '
	 * @method static Note whereBookId($value)
	 * @property string $book_id
	 */
	protected $book_id;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/BibleFile/properties/chapter_start")
	 * @method static Note whereChapter($value)
	 * @property int $chapter
	 */
	protected $chapter;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/BibleFile/properties/verse_start")
	 * @method static Note whereVerseStart($value)
	 * @property int $verse_start
	 */
	protected $verse_start;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/BibleFile/properties/verse_end")
	 * @method static Note whereVerseEnd($value)
	 * @property int|null $verse_end
	 */
	protected $verse_end;

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(ref="#/components/schemas/User/properties/id")
	 * @method static Note whereUserId($value)
	 * @property string $user_id
	 */
	protected $user_id;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Bible/properties/id")
	 * @method static Note whereBibleId($value)
	 * @property string $bible_id
	 */
	protected $bible_id;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Project/properties/id")
	 * @method static Note whereProjectId($value)
	 * @property string $project_id
	 */
	protected $project_id;

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
