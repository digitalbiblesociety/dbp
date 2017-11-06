<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Alphabet;

/**
 * App\Models\Language\AlphabetFont
 *
 * @property int $id
 * @property string $script_id
 * @property string $fontName
 * @property string $fontFileName
 * @property int|null $fontWeight
 * @property string|null $copyright
 * @property string|null $url
 * @property string|null $notes
 * @property int $italic
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Language\Alphabet $script
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereCopyright($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereFontFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereFontName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereFontWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereItalic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereScriptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetFont whereUrl($value)
 * @mixin \Eloquent
 */
class AlphabetFont extends Model
{
    protected $table = 'alphabet_fonts';
    protected $hidden = ['iso'];

    public function script()
    {
        return $this->belongsTo(Alphabet::class);
    }

}