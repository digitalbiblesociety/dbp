<?php

namespace App\Models\Bible;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class BibleFile extends Model
{
	protected $table = "bible_files";

	public $incrementing = false;
	public $primaryKey = 'id';

	use Uuids;

	public function language()
	{
		return $this->hasOne(Language::class);
	}

	public function bible()
	{
		return $this->BelongsTo(Bible::class);
	}

	public function book()
	{
		return $this->BelongsTo(Book::class);
	}

	public function references()
	{
		return $this->hasMany(BibleFileTimestamp::class);
	}

	public function firstReference()
	{
		return $this->hasOne(BibleFileTimestamp::class);
	}

}
