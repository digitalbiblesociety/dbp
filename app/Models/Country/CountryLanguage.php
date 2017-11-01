<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
class CountryLanguage extends Model
{
    protected $table = "country_language";
	public $timestamps = false;
	public $incrementing = false;

	public function language()
	{
		return $this->belongsTo(Language::class);
	}

}
