<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Bible;
class Translator extends Model
{
    protected $dates = ['created_at', 'updated_at'];
    protected $hidden = ['pivot','created_at','updated_at'];
    public $incrementing = false;


    public function bibles()
    {
        return $this->BelongsToMany(Bible::class);
    }


}