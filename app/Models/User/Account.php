<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Account
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Account Model describes the connections between Users and their social accounts",
 *     title="Account",
 *     @OA\Xml(name="Account")
 * )
 *
 */
class Account extends Model
{
    protected $connection = 'dbp_users';
    protected $table = 'user_accounts';
    protected $fillable = ['user_id', 'provider_user_id', 'provider_id', 'project_id'];
    protected $hidden = ['user_id'];
    protected $incrementing = false;

    /**
     *
     * @OA\Property(
     *   title="user_id",
     *   type="string",
     *   description="The user id for the user who has the account being described"
     * )
     *
     * @method static Account whereUserId($value)
     * @property string $user_id
     */
    protected $user_id;

    /**
     *
     * @OA\Property(
     *   title="provider_id",
     *   type="string",
     *   description="The social account provider that the user has logged in with",
     *   example="facebook"
     * )
     *
     * @method static Account whereProviderId\($value)
     * @property string $provider
     */
    protected $provider_id;

    /**
     *
     * @OA\Property(
     *   title="provider_user_id",
     *   type="string",
     *   description="The key of the provider for the account being described",
     *   example=""
     * )
     *
     * @method static Account whereProviderUserId($value)
     * @property string $provider_user_id
     */
    protected $provider_user_id;

    /**
     *
     * @OA\Property(
     *   title="created_at",
     *   type="string",
     *   description="The time the social account was originally connected to the user"
     * )
     *
     * @method static Account whereCreatedAt($value)
     * @property \Carbon\Carbon|null $created_at
     */
    protected $created_at;

    /**
     *
     * @OA\Property(
     *   title="updated_at",
     *   type="string",
     *   description="The time the social account was last updated"
     * )
     *
     * @method static Account whereUpdatedAt($value)
     * @property \Carbon\Carbon|null $updated_at
     */
    protected $updated_at;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
