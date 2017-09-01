<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class BibleOrganization extends Model
{
    protected $table = "bible_organizations";
    public $timestamps = false;
    public $incrementing = false;

	public function bible()
	{
		return $this->BelongsTo(Bible::class,'bible_id','id');
	}

}
