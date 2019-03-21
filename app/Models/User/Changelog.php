<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    public $table = 'changelog';
    protected $hidden = ['id', 'created_at', 'updated_at','subheading'];
    public $timestamps = ['released_at'];
}
