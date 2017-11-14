<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\Text
 *
 * @property string $id
 * @property string $bible_id
 * @property string|null $bible_variation_id
 * @property string $book_id
 * @property int $chapter_number
 * @property int $verse_start
 * @property int|null $verse_end
 * @property string $verse_text
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFile[] $audio
 * @property-read \App\Models\Bible\Bible $bible
 * @property-read \App\Models\Bible\Book $book
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFile[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileTimestamp[] $timestamps
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Text whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Text whereBibleVariationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Text whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Text whereChapterNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Text whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Text whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Text whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Text whereVerseEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Text whereVerseStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Text whereVerseText($value)
 * @mixin \Eloquent
 */
class Text extends Model
{
	protected $table = 'bible_text';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = ['bible_id','book_id','chapter','verse_start','verse_end','verse_content'];
    public $incrementing = false;
    public $timestamps = false;

    public function bible()
    {
        return $this->hasOne(Bible::class, 'id', 'bible_id');
    }

    public function book()
    {
        return $this->hasOne(Book::class,'id','book_id');
    }

    public function chapters()
    {
        $chapters = Text::where('bible_id',$this->bible_id)->Where('book_id',$this->book_id)->select('chapter')->distinct()->orderBy('chapter')->get()->ToArray();
        foreach($chapters as $key => $chapter) {
            $chapters[$key] = $chapter['chapter'];
        }
        return $chapters;
    }

    public function languageSpecificNumber($chapter)
    {
        switch($this->bible->iso) {
            case "guj": {
                $vern_numbers = array('૦','૧','૨','૩','૪','૫','૬','૭','૮','૯');
                break;
            }
            case "lif": {
                $vern_numbers = array('᥆','᥇','᥈','᥉','᥊','᥋','᥌','᥍','᥎','᥏');
                break;
            }
            default: {
                $vern_numbers = array('0','1','2','3','4','5','6','7','8','9');
            }
        }

        if(strlen($chapter) == 1) return $vern_numbers[$chapter];
        $numbers = str_split($chapter);
        $output = "";
        foreach($numbers as $number) {
            $output .= $vern_numbers[$number];
        }
        return $output;
    }

    public function previousChapter()
    {
        $previousChapter = Text::where('bible_id',$this->bible_id)->Where('book_id',$this->book_id)->where('chapter',($this->chapter - 1))->count();
        if($previousChapter != 0) return ($this->chapter - 1);
        return false;
    }

    public function nextChapter()
    {
        $previousChapter = Text::where('bible_id',$this->bible_id)->Where('book_id',$this->book_id)->where('chapter',($this->chapter + 1))->count();
        if($previousChapter != 0) return ($this->chapter + 1);
        return false;
    }

    /*
     * File Connections
     */

    public function files()
    {
	    return $this->hasMany(BibleFile::class,'bible_id', 'bible_id');
    }

    public function audio()
    {
    	return $this->hasMany(BibleFile::class,'bible_id', 'bible_id')->where('file_type', 'audio');
    }

	public function timestamps()
	{
		return $this->hasManyThrough(
			BibleFileTimestamp::class,
			BibleFile::class,
			'bible_id', // Foreign key on users table...
			'bible_id', // Foreign key on timestamps table...
			'bible_id', // Local key on countries table...
			'bible_id' // Local key on users table...
		);
	}


}