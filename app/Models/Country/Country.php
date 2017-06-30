<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
class Country extends Model
{

    protected $table = 'geo.countries';
    public $incrementing = false;
    protected $hidden = ["pivot"];

    public function currentTranslation($iso)
    {
        $language = Language::where('iso',$iso)->first();
        return $this->HasOne('App\Models\Country\CountryTranslation')->where('glotto_id', $language->id)->name;
    }

    public function translations()
    {
        return $this->HasMany('App\Models\Country\CountryTranslation');
    }

    public function languages()
    {
        return $this->BelongsToMany('App\Models\Language\Language', 'country_language', 'country_id', 'glotto_id')->distinct();
    }

    public function bibles()
    {
        return $this->HasManyThrough('App\Models\Bible\Bible','App\Models\Language\Language', 'iso', 'iso');
    }

}