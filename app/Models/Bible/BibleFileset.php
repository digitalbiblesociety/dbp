<?php

namespace App\Models\Bible;

use App\Models\Organization\Organization;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class BibleFileset extends Model
{

	public $incrementing = false;
	protected $keyType = "string";
	protected $fillable = ['name','set_type','organization_id','variation_id','bible_id'];

	public function bible()
	{
		return $this->belongsTo(Bible::class);
	}

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}

	public function files()
	{
		return $this->HasMany(BibleFile::class,'set_id');
	}

	public function permissions()
	{
		return $this->hasMany(BibleFileSetPermission::class);
	}

	public function users()
	{
		return $this->hasMany(BibleFileSetPermission::class)->select('user_id');
	}
}
