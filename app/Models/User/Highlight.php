<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Highlight
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $user_id
 * @property string $bible_id
 * @property string $book_id
 * @property int $chapter
 * @property string|null $highlighted_color
 * @property int $verse_start
 * @property string|null $project_id
 * @property int $highlight_start
 * @property int $highlighted_words
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereChapter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereHighlightStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereHighlightedColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereHighlightedWords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereVerseStart($value)
 * @property int|null $verse_end
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Highlight whereVerseEnd($value)
 */
class Highlight extends Model
{
    public $table = 'user_highlights';
    protected $fillable = ['user_id','bible_id','book_id','project_id','chapter','verse_start','verse_end','highlight_start','highlighted_words','highlighted_color'];

}
