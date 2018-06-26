<?php

namespace App\Models\Bible;

use App\Models\Country\Country;
use App\Models\Language\Alphabet;
use App\Models\User\Access;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;


/**
 * App\Models\Bible\Bible
 *
 * @property-read \App\Models\Language\Alphabet $alphabet
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleBook[] $books
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Video[] $videos
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileset[] $filesetAudio
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileset[] $filesetFilm
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileset[] $filesetText
 * @property int $priority
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Bible wherePriority($value)
 * @property int $open_access
 * @property int $connection_fab
 * @property int $connection_dbs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Access[] $access
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Bible whereConnectionDbs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Bible whereConnectionFab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Bible whereOpenAccess($value)
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Bible",
 *     title="Bible",
 *     @OAS\Xml(name="Bible")
 * )
 *
 */
class Bible extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    //protected $dates = ['date'];

    /**
     * Hides values from json return for api
     *
     * created_at and updated at are only used for archival work. pivots contain duplicate data;
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

	/**
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   type="string",
	 *   description="The Archivist created Bible ID string. This will be between six and twelve letters usually starting with the iso639-3 code and ending with the acronym for the Bible",
	 *   minLength=6,
	 *   maxLength=12,
	 *   example="ENGESV"
	 * )
	 *
	 * @method static Bible whereId($value)
	 * @property string $id
	 */
	protected $id;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Language/properties/id")
	 *
	 * @method static Bible whereLanguageId($value)
	 * @property integer $language_id
	 */
	protected $language_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="date",
	 *   type="integer",
	 *   description="The year the Bible was originally published",
     *   minimum=1,
     *   maximum=2030
	 * )
	 *
	 * @method static Bible whereDate($value)
	 * @property integer $date
	 */
	protected $date;
	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/BibleFilesetSize/properties/set_size_code")
	 *
	 * @method static Bible whereScope($value)
	 * @property string|null $scope
	 */
	protected $scope;

	/**
	 *
     * @OAS\Property(ref="#/components/schemas/Alphabet/properties/script")
	 *
	 * @method static Bible whereScript($value)
	 * @property string|null $script
	 */
	protected $script;

	/**
	 *
	 * @OAS\Property(
	 *   title="derived",
	 *   type="string",
     *   nullable=true,
	 *   description="This field indicates the `bible_id` of the Scriptures that the current Scriptures being described are derived. For example, because the NIrV (New International Reader's Version) was created from / inspired by the NIV (New International Version). If this model was describing ENGNIRV the derived field would be ENGNIV.",
	 * )
	 *
	 * @method static Bible whereDerived($value)
	 * @property string|null $derived
	 */
	protected $derived;

	/**
	 *
	 * @OAS\Property(
	 *   title="copyright",
	 *   type="string",
	 *   description="A short copyright description for the bible text.",
     *   maxLength=191
	 * )
	 *
	 * @method static Bible whereCopyright($value)
	 * @property string|null $copyright
	 */
	protected $copyright;

	/**
	 *
	 * @OAS\Property(
	 *   title="in_progress",
	 *   type="string",
	 *   description="If the Bible being described is currently in progress.",
	 * )
	 *
	 * @method static Bible whereInProgress($value)
	 * @property string|null $in_progress
	 */
	protected $in_progress;

	/**
	 *
	 * @OAS\Property(
	 *   title="versification",
	 *   type="string",
	 *   description="The versification system for ordering books and chapters",
	 *   enum={"protestant","luther","synodal","german","kjva","vulgate","lxx","orthodox","nrsva","catholic","finnish"}
	 * )
	 *
	 * @method static Bible whereVersification($value)
	 * @property string|null $versification
	 */
	protected $versification;

	/**
	 *
	 * @OAS\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The timestamp at which the bible was originally created"
	 * )
	 *
	 * @method static Bible whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 */
	protected $created_at;
	/**
	 *
	 * @OAS\Property(
	 *   title="updated_at",
	 *   type="string",
     *   description="The timestamp at which the bible was last updated"
	 * )
	 *
	 * @method static Bible whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 */
	protected $updated_at;

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
        return $this->HasMany(BibleTranslation::class)->where('name','!=','');
    }
	public function translatedTitles()
	{
		return $this->HasMany(BibleTranslation::class)->where('name','!=','');
	}
    public function currentTranslation()
    {
        return $this->HasOne(BibleTranslation::class)->where('iso', \i18n::getCurrentLocale())->select('bible_id','name')->where('name','!=','');
    }
    public function vernacularTranslation()
    {
        return $this->HasOne(BibleTranslation::class)->where('vernacular', '=', 1)->where('name','!=','');
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
	    return $this->hasManyThrough(BibleFileset::class,BibleFilesetConnection::class, 'bible_id','hash_id','id','hash_id');
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
        return $this->HasMany(BibleLink::class)->where('visible',true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function language()
    {
        return $this->hasOne(Language::class,'id','language_id')->select('name','id','country_id','iso','iso2T','iso2B','iso1','autonym');
    }

    public function country()
    {
    	return $this->hasManyThrough(Country::class,Language::class,'iso','id','iso','country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function alphabet()
    {
        return $this->hasOne(Alphabet::class,'script','script')->select(['script','name','direction','unicode','requires_font']);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos()
    {
        return $this->HasMany(Video::class)->orderBy('order','asc');
    }

}