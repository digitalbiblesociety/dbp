<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
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
