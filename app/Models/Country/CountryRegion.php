<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

class CountryRegion extends Model
{
	protected $table = 'geo.country_regions';
	public $incrementing = false;
	public $timestamps = false;
}
