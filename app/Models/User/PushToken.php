<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User
 * @mixin \Eloquent
 *
 * @property string $push_token
 * @property string $platform
 * @property Carbon $created_at
 *
 *
 * @OA\Schema (
 *     type="object",
 *     description="The User Push Token",
 *     title="Push Token"
 * )
 *
 */
class PushToken extends Model
{
    protected $connection = 'dbp_users';
    protected $table = 'user_push_tokens';
    protected $fillable = ['push_token', 'user_id', 'platform'];
    protected $hidden = ['id', 'user_id', 'created_at'];
    protected $user_id;
    /**
     *
     * @OA\Property(
     *   title="push_token",
     *   type="string",
     *   description="The device push notification token"
     * )
     *
     */
    protected $push_token;
    /**
     *
     * @OA\Property(
     *   title="platform",
     *   type="string",
     *   description="The device platform (eg: android)"
     * )
     *
     */
    protected $platform;
    protected $created_at;
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
