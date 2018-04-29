<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Note
 *
 * @property string $user_id
 * @property string $bible_id
 * @property string $project_id
 * @property string|null $reference_id
 * @property string|null $highlights
 * @property string|null $notes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereHighlights($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereUserId($value)
 * @mixin \Eloquent
 * @property int $id
 * @property string $book_id
 * @property int $chapter
 * @property int $verse_start
 * @property int|null $verse_end
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\NoteTag[] $tags
 * @property-read \App\Models\User\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereChapter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereVerseEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereVerseStart($value)
 * @property int $bookmark
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Note whereBookmark($value)
 */
class Note extends Model
{
    protected $table = "user_notes";
    protected $hidden = ['user_id','project_id'];
    protected $fillable = ['user_id','bible_id','book_id','project_id','chapter','verse_start','verse_end','bookmark','notes'];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function tags()
    {
    	return $this->hasMany(NoteTag::class,'note_id','id');
    }

}
