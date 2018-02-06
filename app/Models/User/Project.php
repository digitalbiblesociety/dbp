<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	public $keyType = 'string';
	public $incrementing = false;

    public function members()
    {
    	return $this->belongsToMany(User::class)->where('role','!=','user');
    }

    public function users()
    {
	    return $this->belongsToMany(User::class)->where('role','user');
    }

    public function notes()
    {
    	return $this->hasMany(Note::class);
    }

    public function highlights()
    {
    	return $this->hasMany(Highlight::class);
    }

}