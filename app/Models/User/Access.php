<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Bible;

class Access extends Model
{
	protected $table = 'user_access';
	protected $primaryKey = 'key_id';
	public $incrementing = false;

	public function bible()
	{
		return $this->BelongsTo(Bible::class,'bible_id','id');
	}
}
