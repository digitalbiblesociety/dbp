<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFilesetType
 *
 * @property int $id
 * @property string $set_type_code
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \App\Models\Bible\BibleFileset $fileset
 * @method static BibleFilesetType whereCreatedAt($value)
 * @method static BibleFilesetType whereId($value)
 * @method static BibleFilesetType whereName($value)
 * @method static BibleFilesetType whereSetTypeCode($value)
 * @method static BibleFilesetType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BibleFilesetType extends Model
{
    public $table = "bible_fileset_types";

    protected $hidden = ['updated_at','id'];

    public function fileset()
    {
    	return $this->belongsTo(BibleFileset::class);
    }

}
