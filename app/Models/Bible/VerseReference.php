<?php

namespace App\Bible;

use App\Models\Bible\Book;
use Illuminate\Database\Eloquent\Model;

class VerseReference extends Model
{
    public $table = 'verse_references';

    protected $book_id;
    protected $chapter;
    protected $verse_start;
    protected $verse_end;

    protected $casts = [
        'chapter'     => 'integer',
        'verse_start' => 'integer',
        'verse_end'   => 'integer',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
