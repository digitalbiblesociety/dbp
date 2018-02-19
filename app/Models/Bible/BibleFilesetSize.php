<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFilesetSize
 *
 * @property int $id
 * @property string $set_size_code
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\BibleFilesetConnection $filesetConnection
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetSize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetSize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetSize whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetSize whereSetSizeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetSize whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BibleFilesetSize extends Model
{
    protected $table = "bible_fileset_sizes";

    public function filesetConnection()
	{
		return $this->hasOne(BibleFilesetConnection::class);
	}

}
