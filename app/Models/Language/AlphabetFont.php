<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Alphabet;
class AlphabetFont extends Model
{
    protected $table = 'geo.alphabet_fonts';
    protected $hidden = ['iso'];

    public function script()
    {
        return $this->belongsTo(Alphabet::class);
    }

}