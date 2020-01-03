<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class APIToken extends Model
{
    protected $connection = 'dbp_users';
    protected $table = 'user_api_tokens';
    protected $fillable = ['api_token','user_id'];
    protected $user_id;
    protected $api_token;
    protected $created_at;
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
