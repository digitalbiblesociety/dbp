<?php

namespace App\Models\Bible;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

class BibleFilesetTag extends Model
{
	public $table = 'bible_fileset_tags';
	public $primaryKey = 'bible_fileset_id';
	public $incrementing = false;
	protected $keyType = "string";
	protected $hidden = ["created_at","updated_at"];
	protected $fillable = ['name','description','admin_only','notes','iso'];

	public function fileset()
	{
		return $this->belongsTo(BibleFileset::class);
	}

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}

}
