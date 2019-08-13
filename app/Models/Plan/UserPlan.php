<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model 
{

    protected $connection = 'dbp_users';
    public $table         = 'user_plans';
    protected $fillable   = ['plan_id', 'user_id', 'start_date', 'suggested_start_date', 'percentage_completed', 'days_completed', 'items_completed'];
    protected $hidden     = ['plan_id', 'created_at', 'updated_at'];
}
