<?php

namespace App\Models\Language;

use App\Models\Bible\Bible;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
use App\Models\Language\AlphabetFont;
class Alphabet extends Model
{
    protected $table = "alphabets";
    protected $primaryKey = 'script';
    public $incrementing = false;

    protected $hidden = ["created_at","updated_at","directionNotes","requiresFont"];
    protected $fillable = [ "script", "name", "unicode_pdf", "family", "type", "white_space", "open_type_tag", "complex_positioning", "requires_font", "unicode", "diacritics", "contextual_forms", "reordering", "case", "split_graphs", "status", "baseline", "ligatures", "direction", "sample", "sample_img"];

    public function languages()
    {
        return $this->BelongsToMany(Language::class,'alphabet_language','script');
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
