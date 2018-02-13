<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class BibleFilesetSize extends Model
{
    protected $table = "bible_fileset_sizes";

    public function filesetConnection()
	{
		return $this->hasOne(BibleFilesetConnection::class);
	}

}
