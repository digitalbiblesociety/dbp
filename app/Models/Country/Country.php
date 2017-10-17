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

    public function translations($iso = null)
    {
    	if(!isset($iso)) return $this->HasMany(CountryTranslation::class);
    	$language = Language::where('iso',$iso)->first();
    	return $this->HasMany(CountryTranslation::class)->where('language_id',$language->id);
    }

    public function languages()
    {
        return $this->BelongsToMany(Language::class)->distinct();
    }

    public function regions()
    {
    	return $this->HasMany(CountryRegion::class);
    }

}