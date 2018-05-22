<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $fillable = ['project_id','role','subscribed'];

}
