<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = "user_notes";
    protected $fillable = ['user_id','bible_id','reference_id','highlights','notes'];

}
