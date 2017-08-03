<?php

namespace App\Models\Language;

use App\Models\Bible\Bible;
use App\Models\Bible\Film;
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
	public $table = "geo.languages";
    public $primaryKey = 'id';
    public $timestamps = false;

    protected $hidden = ["pivot"];
    protected $fillable = ['country_id'];

    public function alphabets()
    {
        return $this->BelongsToMany(Alphabet::class,'alphabet_language','script')->distinct();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->HasMany(LanguageTranslation::class, 'glotto_language')->select('name', 'glotto_language');
    }

    public function currentTranslation($iso = null)
    {
    	if($iso == null) $iso = \i18n::getCurrentLocale();
        $language = Language::where('iso',$iso)->first();
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
        return $this->BelongsToMany(Country::class, 'geo.country_language');
    }

    public function countriesByID()
    {
        return \DB::table('country_language')->where('iso',$this->iso)->select('country_id')->get()->pluck('country_id')->ToArray();
    }

    public function primaryCountry()
    {
        return $this->HasOne(Country::class,'id','country_id');
    }

    public function region()
    {
    	return $this->HasOne(CountryRegion::class,'id','country_id');
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
        return $this->HasMany(Bible::class);
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
        return $this->HasMany(Film::class);
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
        return $this->HasMany(LanguageCode::class);
    }

    public function iso639_2()
    {
        return $this->HasOne(LanguageCode::class)->where('source','Iso 639-2');
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
        return $this->HasMany(LanguageDialect::class);
    }

	/**
	 * Returns a resource based upon the glottologue, ethnologue, or walls ID
	 *
	 * @param $id
	 */
	public function fetchByID($id = null) {
		if(isset($_GET['language_id'])) $id = $_GET['language_id'];
		$length = strlen($id);
    	switch ($length) {
		    case 3: return $this->where('iso',$id)->first();
		    case 8: return $this->find($id);
	    }
		return false;
    }

}
