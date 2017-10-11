<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileTimestamp;

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
        return $this->hasOne('App\Models\Bible\Bible', 'abbr', 'bible_id');
    }

    public function book()
    {
        return $this->hasOne('App\Models\Bible\Book','id','book_id');
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