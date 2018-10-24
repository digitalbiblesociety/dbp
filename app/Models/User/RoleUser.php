<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
	protected $connection = 'dbp_users';
	protected $table = 'dbp_users.role_user';
}
