<?php

namespace App\Models\Bible;

use App\Models\User\Access;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;


/**
 * App\Models\Bible\Bible
 *
 * @property string $id
 * @property string $iso
 * @property string $date
 * @property string|null $scope
 * @property string|null $script
 * @property string|null $derived
 * @property string|null $copyright
 * @property string|null $in_progress
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Language\Alphabet $alphabet
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleBook[] $books
 * @property-read \App\Models\Bible\BibleTranslation $currentTranslation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleEquivalent[] $dbl
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleEquivalent[] $dbp
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleEquivalent[] $eBible
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleEquivalent[] $eSword
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleEquivalent[] $equivalents
 * @property-read \App\Models\Bible\BibleEquivalent $fcbh
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFile[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileset[] $filesets
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleEquivalent[] $hasType
 * @property-read \App\Models\Language\Language $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleLink[] $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization\Organization[] $organizations
 * @property-read \App\Models\Bible\Printable $printable
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleTranslation[] $translations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Translator[] $translators
 * @property-read \App\Models\Bible\BibleTranslation $vernacularTranslation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Video[] $videos
 * @method static Builder|Bible whereCopyright($value)
 * @method static Builder|Bible whereCreatedAt($value)
 * @method static Builder|Bible whereDate($value)
 * @method static Builder|Bible whereDerived($value)
 * @method static Builder|Bible whereId($value)
 * @method static Builder|Bible whereInProgress($value)
 * @method static Builder|Bible whereIso($value)
 * @method static Builder|Bible whereScope($value)
 * @method static Builder|Bible whereScript($value)
 * @method static Builder|Bible whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileset[] $filesetAudio
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileset[] $filesetFilm
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileset[] $filesetText
 * @property int $priority
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Bible wherePriority($value)
 */
class Bible extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    protected $keyType = 'string';

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

    /**
     *
     * Titles and descriptions for every text can be translated into any language.
     * This relationship returns those translations.
     *
     */
    public function translations()
    {
        return $this->HasMany(BibleTranslation::class);
    }
    public function currentTranslation()
    {
        return $this->HasOne(BibleTranslation::class)->where('iso', \i18n::getCurrentLocale())->select('bible_id','name');
    }
    public function vernacularTranslation()
    {
        return $this->HasOne(BibleTranslation::class)->where('vernacular', '=', 1);
    }

    public function books()
    {
	    return $this->HasMany(BibleBook::class);
    }

    public function translators()
    {
        return $this->BelongsToMany(Translator::class);
    }

    public function printable()
    {
        return $this->hasOne(Printable::class);
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

    public function filesets()
    {
	    return $this->HasMany(BibleFileset::class,'bible_id','id');
    }

	public function filesetAudio()
	{
		return $this->HasMany(BibleFileset::class)->where('set_type','Audio');
	}

	public function filesetFilm()
	{
		return $this->HasMany(BibleFileset::class)->where('set_type','Film');
	}

	public function filesetText()
	{
		return $this->HasMany(BibleFileset::class)->where('set_type','Text');
	}

    public function files()
    {
        return $this->HasMany(BibleFile::class);
    }

    public function hasType($type = null)
    {
        return $this->HasMany(BibleEquivalent::class)->where('type',$type);
    }

    public function dbp()
    {
        return $this->HasMany(BibleEquivalent::class)->where('site','bible.is');
    }

	public function fcbh()
	{
		return $this->HasOne(BibleEquivalent::class)->where('site','bible.is');
	}

    public function dbl()
    {
        return $this->HasMany(BibleEquivalent::class)->where('site', 'Digital Bible Library');
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
    public function organizations()
    {
        return $this->BelongsToMany(Organization::class, 'bible_organizations')->withPivot(['relationship_type']);
    }

	public function access()
	{
		return $this->HasMany(Access::class,'bible_id','id');
	}


    /**
     * Each Bible has many links that attach
     *
     * @return mixed
     */
    public function links()
    {
        return $this->HasMany(BibleLink::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function language()
    {
        return $this->hasOne(Language::class,'iso','iso')->select('name','id','country_id','iso','iso2T','iso2B','iso1','autonym');
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
        return $this->HasMany(Video::class)->orderBy('order','asc');
    }

}