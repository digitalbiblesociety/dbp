<?php

namespace App\Models\Bible;

use App\Models\Language\Language;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\AudioReferences;
use App\Models\Bible\Book;

class Audio extends Model
{

    protected $table = "bible_audio";
    public $timestamps = false;

    public function language()
    {
        return $this->hasOne(Language::class);
    }

	public function book()
	{
		return $this->BelongsTo(Book::class);
	}

	public function references()
	{
		return $this->hasMany(AudioReferences::class);
	}

	public function firstReference()
	{
		return $this->hasOne(AudioReferences::class);
	}

}
