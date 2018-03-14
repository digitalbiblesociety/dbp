<?php

namespace App\Models\Language;

use App\Models\Bible\Bible;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\Alphabet
 *
 * @property string $script
 * @property string $name
 * @property string|null $unicode_pdf
 * @property string|null $family
 * @property string|null $type
 * @property string|null $white_space
 * @property string|null $open_type_tag
 * @property string|null $complex_positioning
 * @property int $requires_font
 * @property int $unicode
 * @property int|null $diacritics
 * @property int|null $contextual_forms
 * @property int|null $reordering
 * @property int|null $case
 * @property int|null $split_graphs
 * @property string|null $status
 * @property string|null $baseline
 * @property string|null $ligatures
 * @property string|null $direction
 * @property string|null $direction_notes
 * @property string|null $sample
 * @property string|null $sample_img
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Bible[] $bibles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\AlphabetFont[] $fonts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\AlphabetNumber[] $numerals
 * @property-read \App\Models\Language\AlphabetFont $primaryFont
 * @property-read \App\Models\Language\AlphabetFont $regular
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereCase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereComplexPositioning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereContextualForms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereDiacritics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereDirectionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereLigatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereOpenTypeTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereReordering($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereRequiresFont($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereSampleImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereScript($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereSplitGraphs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereUnicode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereUnicodePdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereWhiteSpace($value)
 * @mixin \Eloquent
 */
class Alphabet extends Model
{
    protected $table = "alphabets";
    protected $primaryKey = 'script';
    public $incrementing = false;

	protected $casts = [
		'complex_positioning' => 'boolean',
		'requires_font'       => 'boolean',
		'unicode'             => 'boolean',
		'diacritics'          => 'boolean',
		'contextual_forms'    => 'boolean',
		'reordering'          => 'boolean',
		'case'                => 'boolean',
		'split_graphs'        => 'boolean',
	];

    protected $hidden = ["created_at","updated_at","directionNotes","requiresFont"];
    protected $fillable = [ "script", "name", "unicode_pdf", "family", "type", "white_space", "open_type_tag", "complex_positioning", "requires_font", "unicode", "diacritics", "contextual_forms", "reordering", "case", "split_graphs", "status", "baseline", "ligatures", "direction", "sample", "sample_img"];

    public function languages()
    {
        return $this->BelongsToMany(Language::class,'alphabet_language','script_id');
    }

    public function fonts()
    {
        return $this->HasMany(AlphabetFont::class,'script_id','script');
    }

    public function primaryFont()
    {
        return $this->HasOne(AlphabetFont::class,'script_id','script');
    }

    public function regular()
    {
        return $this->HasOne(AlphabetFont::class,'script_id','script')->where('fontWeight',400);
    }

	public function bibles()
	{
		return $this->HasMany(Bible::class,'script','script');
	}

	public function numerals()
	{
		return $this->HasMany(AlphabetNumber::class,'script_id');
	}

}
