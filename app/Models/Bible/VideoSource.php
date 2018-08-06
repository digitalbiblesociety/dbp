<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class VideoSource extends Model
{
	protected $connection = 'dbp';
	protected $table = "video_sources";

	public function video()
	{
		$this->belongsTo(Video::class);
	}

}
