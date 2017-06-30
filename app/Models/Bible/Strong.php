<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class Strong extends Model
{
    protected $table = "bible_strongs";
    public $incrementing = false;
    protected $primaryKey = 'strong_id';
}
