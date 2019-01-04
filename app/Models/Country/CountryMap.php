<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

class CountryMap extends Model
{
    protected $hidden = ['created_at','updated_at','country_id','name'];
    protected $connection = 'dbp';
}
