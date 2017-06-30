<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

class LanguageDialect extends Model
{
    public $primaryKey = 'glotto_id';
    protected $table = 'geo.languages_dialects';
    public $incrementing = false;
    public $timestamps = false;

    public function language()
    {
        return $this->belongsTo(Language::class,'child');
    }

}
