<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
class LanguageDivision extends Model
{

    protected $table = 'geo.languages_divisions';

    public function macros()
    {
        return $this->belongsToMany(Language::class, 'iso', 'macro_id');
    }

    public function micros()
    {
        return $this->belongsToMany(Language::class, 'iso', 'micro_id');
    }

}
