<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Book;
class BookCode extends Model
{
    protected $hidden = ['book_id'];
	public $incrementing = false;
	public $timestamps = false;

	public function book()
	{
		return $this->belongsTo(Book::class);
	}
}
