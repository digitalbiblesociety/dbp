<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFilesetConnection
 *
 * @mixin \Eloquent
 */
class BibleFilesetConnection extends Model
{
    public $incrementing = false;

    public function fileset()
    {
    	return $this->belongsTo(BibleFileset::class);
    }

	public function bible()
	{
		return $this->belongsTo(Bible::class);
	}

	public function size()
	{
		return $this->belongsTo(BibleFilesetSize::class);
	}

	public function type()
	{
		return $this->belongsTo(BibleFilesetType::class);
	}

}
