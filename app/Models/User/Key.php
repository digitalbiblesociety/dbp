<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    public $table = 'user_keys';
    protected $primaryKey = 'key';
    public $incrementing = 'false';
    protected $keyType = 'string';
}
