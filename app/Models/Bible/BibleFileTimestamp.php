<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class BibleFileTimestamp extends Model
{
	protected $table = 'bible_file_timestamps';
	public $timestamps = false;

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

}
