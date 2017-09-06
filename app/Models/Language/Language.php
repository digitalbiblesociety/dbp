<?php

namespace App\Models\Language;

use App\Models\Bible\Bible;
use App\Models\Bible\Video;
use App\Models\Country\CountryRegion;
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

	public $table = "languages";
    public $primaryKey = 'id';

    protected $hidden = ["pivot"];
    protected $fillable = ['glotto_id','iso','name','level','maps','development','use','location','area','population','population_notes','notes','typology','writing','description','family_pk','father_pk','child_dialect_count','child_family_count','child_language_count','latitude','longitude','pk','status','country_id','scope'];

    public function alphabets()
    {
        return $this->BelongsToMany(Alphabet::class,'alphabet_language','script')->distinct();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->HasMany(LanguageTranslation::class,'language_source');
    }

    public function currentTranslation($iso = null)
    {
    	if($iso == null) $iso = \i18n::getCurrentLocale();
        $language = Language::where('iso',$iso)->first();
        return $this->HasOne(LanguageTranslation::class)->where('glotto_translation', $language->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries()
    {
        return $this->BelongsToMany(Country::class, 'country_language');
    }

    public function primaryCountry()
    {
        return $this->BelongsTo(Country::class,'country_id','id','countries');
    }

    public function region()
    {
    	return $this->HasOne(CountryRegion::class,'country_id');
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
        return $this->HasMany(Bible::class,'iso','iso');
    }

    public function bibleCount()
    {
	    return $this->HasMany(Bible::class);
    }

    public function biblesSophia()
    {
        return $this->HasMany(Bible::class)->has('sophia');
    }

    public function biblesWithoutSophia()
    {
        return $this->HasMany(Bible::class)->has('sophia', '<', 1);
    }

    public function resources()
    {
        return $this->HasMany(Resource::class);
    }

    public function films()
    {
        return $this->HasMany(Video::class);
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
        return $this->HasMany(LanguageCode::class, 'language_id','id');
    }

    public function iso639_2()
    {
        return $this->HasOne(LanguageCode::class);
    }

    public function classifications()
    {
        return $this->HasMany(LanguageClassification::class);
    }

    public function alternativeNames()
    {
        return $this->HasMany(LanguageAltName::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dialects()
    {
        return $this->HasMany(LanguageDialect::class,'language_id','id');
    }

	public function parent()
	{
		return $this->HasOne(LanguageDialect::class,'dialect_id', 'id');
	}

}
