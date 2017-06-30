<?php

namespace App\Models\Bible;

use App\Models\Language\Language;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{

    protected $table = "audio";

    public function language()
    {
        return $this->hasOne(Language::class);
    }

}
