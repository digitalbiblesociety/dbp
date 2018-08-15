<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\NoteTag
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The NoteTag's model",
 *     title="NoteTag",
 *     @OA\Xml(name="NoteTag")
 * )
 *
 */
class NoteTag extends Model
{
	protected $connection = 'dbp_users';
    public $table = "user_note_tags";
	protected $fillable = ['type','value'];

	 /**
	  *
	  * @OA\Property(
	  *   title="id",
	  *   type="integer",
	  *   description="The unique incrementing id for each NoteTag",
	  *   minimum=0
	  * )
	  *
	  * @method static NoteTag whereId($value)
	  * @property int $id
	  */
	 protected $id;

	 /**
	  *
	  * @OA\Property(
	  *   title="note_id",
	  *   type="integer",
	  *   description="The id for the note which the NoteTag is attached to",
	  *   minimum=0
	  * )
	  *
	  * @method static NoteTag whereNoteId($value)
	  * @property int $note_id
	  */
	 protected $note_id;

	 /**
	  *
	  * @OA\Property(
	  *   title="type",
	  *   type="string",
	  *   description="The type of tag that this NoteTag is categorized within."
	  * )
	  *
	  * @method static NoteTag whereType($value)
	  * @property string $type
	  */
	 protected $type;

	 /**
	  *
	  * @OA\Property(
	  *   title="value",
	  *   type="string",
	  *   description="The value to the type of NoteTag for this note."
	  * )
	  *
	  * @method static NoteTag whereValue($value)
	  * @property string $value
	  */
	 protected $value;

	 /**
	  *
	  * @OA\Property(
	  *   title="created_at",
	  *   type="string",
	  *   description="The timestamp the NoteTag was first created at"
	  * )
	  *
	  * @method static NoteTag whereCreatedAt($value)
	  * @property Carbon $created_at
	  */
	 protected $created_at;

	 /**
	  *
	  * @OA\Property(
	  *   title="updated_at",
	  *   type="string",
	  *   description="The timestamp the NoteTag was last updated"
	  * )
	  *
	  * @method static NoteTag whereUpdatedAt($value)
	  * @property Carbon $updated_at
	  */
	 protected $updated_at;

}
