<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
use App\Models\Country\CountryTranslation;
use App\Models\Bible\Bible;

/**
 * App\Models\Country\Country
 *
 * @property string $id
 * @property string $iso_a3
 * @property string $fips
 * @property string $continent
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country\CountryRegion[] $regions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country\CountryTranslation[] $translations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\Country whereContinent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\Country whereFips($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\Country whereIsoA3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\Country whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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