<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

class CountryTranslation extends Model
{
    protected $table = 'geo.country_translations';
    protected $hidden = ["country_id","vernacular"];
    public $incrementing = false;
    public $timestamps = false;

    public function language()
    {
        return $this->belongsTo('App\Models\Country\Country');
    }

}
