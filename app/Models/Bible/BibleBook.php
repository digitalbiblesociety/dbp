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
 * @method static BibleBook whereBibleId($value)
 * @method static BibleBook whereBookId($value)
 * @method static BibleBook whereChapters($value)
 * @method static BibleBook whereCreatedAt($value)
 * @method static BibleBook whereName($value)
 * @method static BibleBook whereNameShort($value)
 * @method static BibleBook whereUpdatedAt($value)
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

}
