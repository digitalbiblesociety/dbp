<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Password Reset Model",
 *     title="PasswordReset",
 *     @OA\Xml(name="PasswordReset")
 * )
 *
 */
class PasswordReset extends Model
{
    protected $connection = 'dbp_users';
    protected $table = 'password_resets';
    protected $fillable = ['email','token','reset_path','created_at'];
    public $incrementing = false;
    public $keyType = 'string';
    public $primaryKey = 'token';
    public $timestamps = false;

    /**
     *
     * @OA\Property(
     *   title="email",
     *   type="string",
     *   description="The email of the user who requested the password reset",
     *   format="email",
     *   maxLength=191
     * )
     *
     * @method static whereEmail($value)
     * @property $email
     */
    protected $email;

    /**
     *
     * @OA\Property(
     *   title="token",
     *   type="string",
     *   description="The generated token for the password reset",
     *   maxLength=191
     * )
     *
     * @method static whereToken($value)
     * @property $token
     */
    protected $token;

    /**
     *
     * @OA\Property(
     *   title="reset_path",
     *   type="string",
     *   description="The url to redirect the user to create a new password",
     *   maxLength=191
     * )
     *
     * @method static whereResetPath($value)
     * @property $reset_path
     */
    protected $reset_path;

    public function user()
    {
        return $this->BelongsTo(User::class, 'email', 'email');
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::now();
    }
}
