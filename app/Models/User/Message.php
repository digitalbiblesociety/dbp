<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $connection = 'dbp_users';
    protected $table = 'messages';
    protected $fillable = ['email', 'subject', 'purpose', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
