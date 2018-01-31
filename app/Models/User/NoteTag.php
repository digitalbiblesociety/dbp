<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\NoteTag
 *
 * @property int $id
 * @property int $note_id
 * @property string $type
 * @property string $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\NoteTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\NoteTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\NoteTag whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\NoteTag whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\NoteTag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\NoteTag whereValue($value)
 * @mixin \Eloquent
 */
class NoteTag extends Model
{
    public $table = "user_note_tags";
	protected $fillable = ['type','value'];

}
