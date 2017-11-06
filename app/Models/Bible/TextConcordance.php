<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Bible;

/**
 * App\Models\Bible\TextConcordance
 *
 * @property-read \App\Models\Bible\Bible $bible
 * @mixin \Eloquent
 */
class TextConcordance extends Model
{
    public $table = "texts_concordance";
    protected $primaryKey = 'bible_id';
    public $incrementing = false;
    public $timestamps = false;

    public function bible()
    {
        return $this->hasOne(Bible::class, 'abbr');
    }

}