<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleBook
 *
 * @property string $bible_id
 * @property string $book_id
 * @property string|null $name
 * @property string|null $name_short
 * @property \App\Models\Bible\Text $chapters
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\Bible $bible
 * @property-read \App\Models\Bible\Book $book
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleBook whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleBook whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleBook whereChapters($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleBook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleBook whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleBook whereNameShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleBook whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
