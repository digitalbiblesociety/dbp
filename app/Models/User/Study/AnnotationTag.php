<?php

namespace App\Models\User\Study;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Models\User\AnnotationTag
 * @mixin \Eloquent
 *
 * @method static AnnotationTag whereId($value)
 * @method static AnnotationTag whereHighlightId($value)
 * @method static AnnotationTag whereBookmarkId($value)
 * @method static AnnotationTag whereNoteId($value)
 * @method static AnnotationTag whereType($value)
 * @method static AnnotationTag whereValue($value)
 * @method static AnnotationTag whereCreatedAt($value)
 * @method static AnnotationTag whereUpdatedAt($value)
 *
 * @property int $id
 * @property int $note_id
 * @property string $type
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $bookmark_id
 * @property int $highlight_id
 *
 * @OA\Schema (
 *     type="object",
 *     description="The NoteTag's model",
 *     title="NoteTag",
 *     @OA\Xml(name="NoteTag")
 * )
 *
 */
class AnnotationTag extends Model
{
    protected $connection = 'dbp_users';
    public $table = 'user_annotation_tags';
    protected $fillable = ['type','value'];
    protected $hidden = ['created_at','updated_at'];

     /**
      *
      * @OA\Property(
      *   title="id",
      *   type="integer",
      *   description="The unique incrementing id for each NoteTag",
      *   minimum=0
      * )
      *
      */
    protected $id;

     /**
      * @OA\Property(ref="#/components/schemas/Note/properties/id")
      */
    protected $note_id;

    /**
     * @OA\Property(ref="#/components/schemas/Bookmark/properties/id")
     */
    protected $bookmark_id;

    /**
     * @OA\Property(ref="#/components/schemas/Highlight/properties/id")
     */
    protected $highlight_id;

     /**
      *
      * @OA\Property(
      *   title="type",
      *   type="string",
      *   description="The type of tag that this NoteTag is categorized within."
      * )
      *
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
      */
    protected $updated_at;
}
