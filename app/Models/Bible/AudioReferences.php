<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class AudioReferences extends Model
{
	protected $table = 'bible_audio_references';
	public $timestamps = false;

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

}
