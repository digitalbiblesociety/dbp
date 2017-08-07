<?php

namespace App\Models\Bible;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

use App\Models\Bible\Audio;
use App\Models\Bible\BibleTranslation;
use App\Models\Bible\BibleEquivalent;

class Bible extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Hides values from json return for api
     *
     * created_at and updated at are only used for archival work. pivots contain duplicate data;
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    /**
     * @var array
     */
    protected $fillable = ['id', 'iso', 'date', 'script', 'derived', 'copyright'];
    /**
     * @var bool
     */
    public $incrementing = false;
	public $timestamps = false;

    /**
     *
     * Titles and descriptions for every text can be translated into any language.
     * This relationship returns those translations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function translations()
    {
        return $this->HasMany(BibleTranslation::class)->where('name', '!=', '');
    }

    public function currentTranslation()
    {
        $language = Language::where('iso',\i18n::getCurrentLocale())->first();
        return $this->HasOne(BibleTranslation::class)->where('glotto_id', $language->id)->select('bible_id','name');
    }

    public function vernacularTranslation()
    {
        return $this->HasOne(BibleTranslation::class)->where('vernacular', '=', 1);
    }

    public function translation($iso)
    {
        return $this->HasOne(BibleTranslation::class)->where('iso', $iso);
    }

    public function books()
    {
	    return $this->HasMany(BibleBook::class,'abbr');
    }

    public function chapters()
    {
        $texts = Text::where("bible_id",$this->abbr)->select("book_id","chapter")->distinct()->get();
        foreach($texts as $text) {
            $chapters[] = $text->book_id.$text->chapter;
        }
        return $chapters;
    }

    public function translators()
    {
        return $this->BelongsToMany('App\Models\Bible\Translator');
    }

    public function printable()
    {
        return $this->hasOne('App\Models\Bible\Printable');
    }

    /*
    |--------------------------------------------------------------------------
    | Equivalents
    |--------------------------------------------------------------------------
    |
    | All of these relationships are focused upon the bible equivalents table
    | they handle external bible API connections to our different partners
    | like the Digital Bible Platform and the Digital Bible Library ect
    |
    */
    public function equivalents()
    {
        return $this->HasMany(BibleEquivalent::class);
    }

    public function audio()
    {
        return $this->HasMany(Audio::class);
    }

    public function hasType($type)
    {
        return $this->HasMany(BibleEquivalent::class)->where('type',$type);
    }

    public function sophia()
    {
        return \Schema::connection('sophia')->hasTable($this->abbr.'_vpl');
    }

    public function dbp()
    {
        return $this->HasMany(BibleEquivalent::class)->where('site','bible.is');
    }

    public function dbl()
    {
        return $this->HasMany(BibleEquivalent::class)->where('type','Digital Bible Library');
    }

    public function eSword()
    {
        return $this->HasMany(BibleEquivalent::class)->where('type','eSword');
    }

    public function eBible()
    {
        return $this->HasMany(BibleEquivalent::class)->where('type','eBible');
    }

    /**
     * Basically anybody who helps out with the bible Translation
     *
     * @return mixed
     */
    public function contributors()
    {
        return $this->belongsToMany(Organization::class);
    }

    /**
     * Basically anybody who helps out with the bible Translation
     *
     * @return mixed
     */
    public function publishers()
    {
        return $this->belongsToMany(Organization::class)->where('contributionType','=', 2);
    }

    /**
     * Basically anybody who helps out with the bible Translation
     *
     * @return mixed
     */
    public function owners()
    {
        return $this->belongsToMany(Organization::class)->where('contributionType','=', 3);
    }

    /**
     * Each Bible has many links that attach
     *
     * @return mixed
     */
    public function reviews()
    {
        return $this->HasMany('App\Models\Bible\BibleReview');
    }


    /**
     * Each Bible has many links that attach
     *
     * @return mixed
     */
    public function links()
    {
        return $this->HasMany('App\Models\Bible\BibleLink');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function language()
    {
        return $this->hasOne('App\Models\Language\Language','id','glotto_id')->select('name','id','country_id','iso');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function alphabet()
    {
        return $this->hasOne('App\Models\Language\Alphabet','script','script');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->HasMany('App\Models\Bible\BibleVideos')->orderBy('order','asc');
    }

}