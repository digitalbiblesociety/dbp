<?php

namespace App\Models\Bible;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class BibleFileSetPermission extends Model
{
	protected $table = "bible_file_permissions";

	public function fileset()
	{
		return $this->BelongsTo(BibleFileset::class,'bible_fileset_id');
	}

	public function user()
	{
		return $this->BelongsTo(User::class);
	}
}
