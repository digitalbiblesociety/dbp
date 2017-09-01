<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
use App\Models\Country\CountryTranslation;
use App\Models\Bible\Bible;
class Country extends Model
{

    protected $table = 'countries';
    protected $hidden = ["pivot"];
    public $incrementing = false;
    public $keyType = 'string';

    public function currentTranslation($iso = "eng")
    {
        $language = Language::where('iso',$iso)->first();
        return $this->HasOne(CountryTranslation::class)->where('glotto_id', $language->id);
    }

    public function translations()
    {
        return $this->HasMany(CountryTranslation::class);
    }

    public function languages()
    {
        return $this->BelongsToMany(Language::class, 'country_language', 'country_id', 'glotto_id')->distinct();
    }

    public function bibles()
    {
        return $this->HasManyThrough(Bible::class,Language::class, 'iso', 'iso');
    }

    public function regions()
    {
    	return $this->HasMany(CountryRegion::class);
    }

}