<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class BibleFilesetCopyrightOrganization extends Model
{
    public $table = "bible_fileset_copyright_organizations";
	protected $primaryKey = 'hash_id';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = false;

	public function copyright()
	{
		return $this->belongsTo(BibleFilesetCopyright::class, 'hash_id','hash_id');
	}

}
