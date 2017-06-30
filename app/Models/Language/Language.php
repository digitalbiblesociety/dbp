<?php

namespace App\Models\Language;

use App\Models\Bible\Bible;
use App\Models\Bible\Film;
use Illuminate\Database\Eloquent\Model;

use App\Models\Language\LanguageCode;
use App\Models\Language\LanguageDialect;
use App\Models\Language\LanguageAltName;
use App\Models\Language\LanguageTranslation;
use App\Models\Language\LanguageClassification;

use App\Models\Language\Alphabet;
use App\Models\Language\AlphabetFont;

use App\Models\Country\Country;


class Language extends Model
{
	public $table = "geo.languages";
    public $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $hidden = ["pivot"];
    protected $fillable = ['country_id'];

    public function alphabets()
    {
        return $this->BelongsToMany(Alphabet::class,'alphabet_language','glotto_id','script')->distinct();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->HasMany(LanguageTranslation::class, 'glotto_language')->select('name', 'iso_language as iso');
    }

    public function currentTranslation()
    {
        $language = Language::where('iso',\i18n::getCurrentLocale())->first();
        return $this->HasOne(LanguageTranslation::class, 'glotto_language')->where('glotto_translation', $language->id);
    }

    public function vernacularTranslation()
    {
        return $this->HasOne(LanguageTranslation::class, 'glotto_language')->where('glotto_translation',$this->iso);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries()
    {
        return $this->BelongsToMany(Country::class, 'country_language', 'glotto_id');
    }

    public function countriesByID()
    {
        return \DB::table('country_language')->where('iso',$this->iso)->select('country_id')->get()->pluck('country_id')->ToArray();
    }

    public function primaryCountry()
    {
        return $this->HasOne(Country::class,'id','country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fonts()
    {
        return $this->HasMany(AlphabetFont::class, 'iso');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bibles()
    {
        return $this->HasMany(Bible::class,'glotto_id');
    }

    public function biblesSophia()
    {
        return $this->HasMany(Bible::class,'glotto_id')->has('sophia');
    }

    public function biblesWithoutSophia()
    {
        return $this->HasMany(Bible::class,'glotto_id')->has('sophia', '<', 1);
    }

    public function resources()
    {
        return $this->HasMany(Resource::class,'glotto_id');
    }

    public function films()
    {
        return $this->HasMany(Film::class,'glotto_id');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    /**
     * Each language may have many Foreign Language Codes besides the iso 639-3
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function codes()
    {
        return $this->HasMany(LanguageCode::class, 'glotto_id');
    }

    public function iso639_2()
    {
        return $this->HasOne(LanguageCode::class,'glotto_id')->where('source','Iso 639-2');
    }

    public function classifications()
    {
        return $this->HasMany(LanguageClassification::class,'glotto_id');
    }

    public function alternativeNames()
    {
        return $this->HasMany(LanguageAltName::class,'glotto_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dialects()
    {
        return $this->HasMany(LanguageDialect::class, 'parent');
    }

}
