<?php

namespace App\Models\User;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\User\Role;
use App\Models\User\Account;

class User extends Authenticatable
{
	public $incrementing = false;
    use Notifiable;
    use Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


	public function accounts()
	{
		return $this->HasMany(Account::class);
	}

	public function github()
	{
		return $this->HasOne(Account::class)->where('provider','github');
	}

	public function google()
	{
		return $this->HasOne(Account::class)->where('provider','google');
	}

	public function bitbucket()
	{
		return $this->HasOne(Account::class)->where('provider','bitbucket');
	}

	public function roles()
	{
		return $this->HasMany(Role::class);
	}

	public function archivist()
	{
		return $this->hasOne(Role::class)->where('role','archivist');
	}

	public function role($id)
	{
		return $this->HasOne(Role::class)->where('organization_id',$id);
	}

}
