<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Bible;
use App\Models\Bible\Book;
class BibleBook extends Model
{
    protected $table = "bible_books";
    public $incrementing = false;
    public $fillable = ['abbr','book_id', 'name', 'name_short', 'chapters'];

    public function bible()
    {
    	return $this->belongsTo(Bible::class);
    }

    public function book()
    {
    	return $this->belongsTo(Book::class);
    }

    public function chapters()
    {
    	return $this->belongsTo(Text::class,'book_id','bible_book');
    }

}
