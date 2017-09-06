<?php

namespace App\Models\Bible;

use App\Models\Language\Language;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Bible;
use App\Models\Bible\Book;
use App\Models\Bible\VideoTranslations;
class Video extends Model
{

	protected $table = "videos";

	public function bible()
	{
		return $this->belongsTo(Bible::class);
	}

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

	public function related()
	{
		return $this->HasMany(Video::class,'episode', 'episode')->select('episode', 'url');
	}

	public function translations()
	{
		return $this->hasMany(VideoTranslations::class);
	}

}
