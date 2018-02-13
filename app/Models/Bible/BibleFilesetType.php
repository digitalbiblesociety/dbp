<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFilesetType
 *
 * @property int $id
 * @property string $set_type_code
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\BibleFileset $fileset
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetType whereSetTypeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFilesetType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BibleFilesetType extends Model
{
    public $table = "bible_fileset_types";

    public function fileset()
    {
    	return $this->belongsTo(BibleFileset::class);
    }

}
