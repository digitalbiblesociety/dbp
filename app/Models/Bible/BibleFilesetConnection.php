<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFilesetConnection
 *
 * @mixin \Eloquent
 * @property string $hash_id
 * @property string $bible_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\Bible $bible
 * @property-read \App\Models\Bible\BibleFileset $fileset
 * @property-read \App\Models\Bible\BibleFilesetSize $size
 * @property-read \App\Models\Bible\BibleFilesetType $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetConnection whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetConnection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetConnection whereHashId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetConnection whereUpdatedAt($value)
 */
class BibleFilesetConnection extends Model
{
    public $incrementing = false;
    public $keyType = 'string';
    public $primaryKey = 'hash_id';

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
