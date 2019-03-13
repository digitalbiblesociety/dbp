<?php

namespace App\Models\Bible\Study;

use Illuminate\Database\Eloquent\Model;

class Lexicon extends Model
{
    public $incrementing = false;
    public $connection = 'dbp';


    public function scopeFilterByLanguage($query, $language)
    {
        $query->when($language, function ($query, $language) {
            return $query->where('id','LIKE', $language.'%');
        });
    }

}
