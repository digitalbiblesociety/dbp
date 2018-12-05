<?php

namespace App\Models\Bible;

use App\Models\Country\Country;
use App\Models\Language\Alphabet;
use App\Models\Language\NumeralSystem;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Bible\Bible
 * @mixin \Eloquent
 *
 * @property-read \App\Models\Language\Alphabet $alphabet
 * @property-read \App\Models\Bible\BibleBook[] $books
 * @property-read BibleEquivalent[] $dbl
 * @property-read BibleEquivalent[] $dbp
 * @property-read BibleEquivalent[] $eBible
 * @property-read BibleEquivalent[] $eSword
 * @property-read BibleEquivalent[] $equivalents
 * @property-read \App\Models\Bible\BibleEquivalent $fcbh
 * @property-read BibleFile[] $files
 * @property-read BibleFileset[] $filesets
 * @property-read BibleEquivalent[] $hasType
 * @property-read \App\Models\Language\Language $language
 * @property-read BibleLink[] $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Organization\Organization[] $organizations
 * @property-read BibleTranslation[] $translations
 * @property-read Translator[] $translators
 * @property-read Video[] $videos
 * @property-read BibleFileset[] $filesetAudio
 * @property-read BibleFileset[] $filesetFilm
 * @property-read BibleFileset[] $filesetText
 *
 * @property int $priority
 * @property int $open_access
 * @property int $connection_fab
 * @property int $connection_dbs
 * @property string $id
 * @property integer $language_id
 * @property integer $date
 * @property string|null $scope
 * @property string|null $script
 * @property string|null $derived
 * @property string|null $copyright
 * @property string|null $in_progress
 * @property string|null $versification
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @method static Bible wherePriority($value)
 * @method static Bible whereConnectionDbs($value)
 * @method static Bible whereConnectionFab($value)
 * @method static Bible whereOpenAccess($value)
 * @method static Bible whereId($value)
 * @method static Bible whereLanguageId($value)
 * @method static Bible whereDate($value)
 * @method static Bible whereScope($value)
 * @method static Bible whereScript($value)
 * @method static Bible whereDerived($value)
 * @method static Bible whereCopyright($value)
 * @method static Bible whereInProgress($value)
 * @method static Bible whereVersification($value)
 * @method static Bible whereCreatedAt($value)
 * @method static Bible whereUpdatedAt($value)
 *
 * @OA\Schema (
 *     type="object",
 *     description="Bible",
 *     title="Bible",
 *     @OA\Xml(name="Bible")
 * )
 *
 */
class Bible extends Model
{
    /**
     * @var string
     */
    protected $connection = 'dbp';
    protected $keyType = 'string';

    /**
     * Hides values from json return for api
     *
     * created_at and updated at are only used for archival work. pivots contain duplicate data;
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'pivot', 'priority', 'in_progress'];


    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description="The Archivist created Bible ID string. This will be between six and twelve letters usually starting with the iso639-3 code and ending with the acronym for the Bible",
     *   minLength=6,
     *   maxLength=12,
     *   example="ENGESV"
     * )
     *
     */
    protected $id;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Language/properties/id")
     *
     */
    protected $language_id;

    /**
     *
     * @OA\Property(
     *   title="date",
     *   type="integer",
     *   description="The year the Bible was originally published",
     *   minimum=1,
     *   maximum=2030
     * )
     *
     */
    protected $date;
    /**
     *
     * @OA\Property(ref="#/components/schemas/BibleFilesetSize/properties/set_size_code")
     *
     */
    protected $scope;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Alphabet/properties/script")
     *
     */
    protected $script;

    /**
     *
     * @OA\Property(
     *   title="derived",
     *   type="string",
     *   nullable=true,
     *   description="This field indicates the `bible_id` of the Scriptures that the current Scriptures being described are derived. For example, because the NIrV (New International Reader's Version) was created from / inspired by the NIV (New International Version). If this model was describing ENGNIRV the derived field would be ENGNIV.",
     * )
     *
     */
    protected $derived;

    /**
     *
     * @OA\Property(
     *   title="copyright",
     *   type="string",
     *   description="A short copyright description for the bible text.",
     *   maxLength=191
     * )
     *
     */
    protected $copyright;

    /**
     *
     * @OA\Property(
     *   title="in_progress",
     *   type="string",
     *   description="If the Bible being described is currently in progress.",
     * )
     *
     */
    protected $in_progress;

    /**
     *
     * @OA\Property(
     *   title="versification",
     *   type="string",
     *   description="The versification system for ordering books and chapters",
     *   enum={"protestant","luther","synodal","german","kjva","vulgate","lxx","orthodox","nrsva","catholic","finnish"}
     * )
     *
     */
    protected $versification;

    /**
     *
     * @OA\Property(
     *   title="created_at",
     *   type="string",
     *   description="The timestamp at which the bible was originally created"
     * )
     *
     */
    protected $created_at;
    /**
     *
     * @OA\Property(
     *   title="updated_at",
     *   type="string",
     *   description="The timestamp at which the bible was last updated"
     * )
     *
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

    public function translations()
    {
        return $this->hasMany(BibleTranslation::class)->where('name', '!=', '');
    }

    public function translatedTitles()
    {
        return $this->hasMany(BibleTranslation::class)->where('name', '!=', '');
    }

    public function currentTranslation()
    {
        $language_id = $GLOBALS['i18n_id'] ?? Language::where('iso', 'eng')->first()->id;
        return $this->hasOne(BibleTranslation::class)->where('language_id', $language_id)->where('name', '!=', '');
    }

    public function vernacularTranslation()
    {
        return $this->hasOne(BibleTranslation::class)->where('vernacular', '=', 1)->where('name', '!=', '');
    }

    public function books()
    {
        return $this->hasMany(BibleBook::class);
    }

    public function translators()
    {
        return $this->belongsToMany(Translator::class);
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
        return $this->hasMany(BibleEquivalent::class);
    }

    public function filesetConnections()
    {
        return $this->hasMany(BibleFilesetConnection::class);
    }

    public function filesets()
    {
        return $this->hasManyThrough(BibleFileset::class, BibleFilesetConnection::class, 'bible_id', 'hash_id', 'id', 'hash_id');
    }

    public function filesetAudio()
    {
        return $this->hasMany(BibleFileset::class)->where('set_type', 'Audio');
    }

    public function filesetFilm()
    {
        return $this->hasMany(BibleFileset::class)->where('set_type', 'Film');
    }

    public function filesetText()
    {
        return $this->hasMany(BibleFileset::class)->where('set_type', 'Text');
    }

    public function files()
    {
        return $this->hasMany(BibleFile::class);
    }

    public function hasType($type = null)
    {
        return $this->hasMany(BibleEquivalent::class)->where('type', $type);
    }

    public function dbp()
    {
        return $this->hasMany(BibleEquivalent::class)->where('site', 'bible.is');
    }

    public function fcbh()
    {
        return $this->hasOne(BibleEquivalent::class)->where('site', 'bible.is');
    }

    public function dbl()
    {
        return $this->hasMany(BibleEquivalent::class)->where('site', 'Digital Bible Library');
    }

    public function eSword()
    {
        return $this->hasMany(BibleEquivalent::class)->where('type', 'eSword');
    }

    public function eBible()
    {
        return $this->hasMany(BibleEquivalent::class)->where('type', 'eBible');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'bible_organizations')->withPivot(['relationship_type']);
    }

    public function publisher()
    {
        return $this->belongsToMany(Organization::class, 'bible_organizations')->withPivot(['relationship_type'])->wherePivot('relationship_type', 'publisher');
    }

    public function links()
    {
        return $this->hasMany(BibleLink::class)->where('visible', true);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function country()
    {
        return $this->hasManyThrough(Country::class, Language::class, 'id', 'id', 'language_id', 'country_id')->select(['countries.id as country_id','countries.continent','countries.name']);
    }

    public function alphabet()
    {
        return $this->hasOne(Alphabet::class, 'script', 'script')->select(['script','name','direction','unicode','requires_font']);
    }

    public function numbers()
    {
        return $this->hasOne(NumeralSystem::class, 'number_id', 'number_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class)->orderBy('order', 'asc');
    }
}
