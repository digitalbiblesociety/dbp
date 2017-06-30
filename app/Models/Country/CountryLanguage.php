<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

class CountryLanguage extends Model
{
    protected $table = "geo.country_language";
	public $incrementing = false;
	public $timestamps = false;
}
