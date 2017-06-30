<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Book;

class BookTranslation extends Model
{
    protected $table = "book_translations";
    public $incrementing = false;
    protected $hidden = ['created_at','updated_at','book_id','description'];

    public function book()
    {
        return $this->BelongsTo(Book::class, 'usfx', 'id');
    }

}
