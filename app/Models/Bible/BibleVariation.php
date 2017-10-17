<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\BibleFile;

class BibleVariation extends Model
{

	public function files()
	{
		return $this->HasMany(BibleFile::class);
	}

}
