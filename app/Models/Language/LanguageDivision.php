<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Language\LanguageDivision
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\Language[] $macros
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\Language[] $micros
 * @mixin \Eloquent
 */
class LanguageDivision extends Model
{

    protected $table = 'languages_divisions';

    public function macros()
    {
        return $this->belongsToMany(Language::class, 'iso', 'macro_id');
    }

    public function micros()
    {
        return $this->belongsToMany(Language::class, 'iso', 'micro_id');
    }

}
