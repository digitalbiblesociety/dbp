<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
/**
 * App\Models\User\Account
 *
 * @property-read \App\Models\User\User $user
 * @mixin \Eloquent
 * @property string $user_id
 * @property string $provider
 * @property string $provider_user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Account whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Account whereProviderUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Account whereUserId($value)
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Account whereId($value)
 */
class Account extends Model
{
	public $incrementing = false;
	protected $table = 'user_accounts';
	protected $fillable = ['user_id', 'provider_user_id', 'provider'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
