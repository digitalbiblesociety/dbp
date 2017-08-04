<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

class LanguageCode extends Model
{

    protected $table = 'geo.languages_codes';
    protected $fillable = ['code', 'source', 'glotto_id'];
    public $timestamps = false;

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
