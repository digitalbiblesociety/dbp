<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use i18n;

/**
 * App\Models\Bible\Book
 * @mixin \Eloquent
 *
 * @property-read BookTranslation $currentTranslation
 * @property-read BookTranslation[] $translations
 * @property-read BookTranslation $vernacularTranslation
 * @property-read BookTranslation $translation
 * @method static Book whereVerses($value)
 * @method static Book whereTestamentOrder($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Bible[] $bible
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Book model communicates information about the canonical books of the Bible",
 *     title="Book",
 *     @OA\Xml(name="Book")
 * )
 *
 */
class Book extends Model
{
	protected $connection = 'dbp';
    protected $table = "books";
    public $incrementing = false;
    public $hidden = ['description','created_at','updated_at','notes'];

	/**
	 *
	 * @OA\Property(
	 *   title="id",
	 *   type="string",
	 *   description="The USFM 2.4 id for the books of the Bible",
	 *   minLength=3,
	 *   maxLength=3
	 * )
	 *
	 * @method static Book whereId($value)
	 * @property string $id
	 *
	 */
	protected $id;

	/**
	 *
	 * @OA\Property(
	 *   title="id_usfx",
	 *   type="string",
	 *   description="The usfx id for the books of the Bible",
	 *   minLength=2,
	 *   maxLength=2
	 * )
	 *
	 * @method static Book whereIdUsfx($value)
	 * @property string $id_usfx
	 *
	 */
	protected $id_usfx;

	/**
	 *
	 * @OA\Property(
	 *   title="id_osis",
	 *   type="string",
	 *   description="The OSIS id for the books of the Bible",
	 *   minLength=2,
	 *   maxLength=2
	 * )
	 *
	 * @method static Book whereIdOsis($value)
	 * @property string $id_osis
	 *
	 */
	protected $id_osis;

	/**
	 *
	 * @OA\Property(
	 *   title="protestant_order",
	 *   type="integer",
	 *   description="The standard book order for the `protestant_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereProtestantOrder($value)
	 * @property int $protestant_order
	 *
	 */
	protected $protestant_order;

	/**
	 *
	 * @OA\Property(
	 *   title="luther_order",
	 *   type="integer",
	 *   description="The standard book order for the `luther_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereLutherOrder($value)
	 * @property int $luther_order
	 *
	 */
	protected $luther_order;

	/**
	 *
	 * @OA\Property(
	 *   title="synodal_order",
	 *   type="integer",
	 *   description="The standard book order for the `synodal_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereSynodalOrder($value)
	 * @property int $synodal_order
	 *
	 */
	protected $synodal_order;

	/**
	 *
	 * @OA\Property(
	 *   title="german_order",
	 *   type="integer",
	 *   description="The standard book order for the `german_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereGermanOrder($value)
	 * @property int $german_order
	 *
	 */
	protected $german_order;

	/**
	 *
	 * @OA\Property(
	 *   title="kjva_order",
	 *   type="integer",
	 *   description="The standard book order for the `kjva_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereKjvaOrder($value)
	 * @property int $kjva_order
	 *
	 */
	protected $kjva_order;

	/**
	 *
	 * @OA\Property(
	 *   title="vulgate_order",
	 *   type="integer",
	 *   description="The standard book order for the `vulgate_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereVulgateOrder($value)
	 * @property int $vulgate_order
	 *
	 */
	protected $vulgate_order;

	/**
	 *
	 * @OA\Property(
	 *   title="lxx_order",
	 *   type="integer",
	 *   description="The standard book order for the `lxx_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereLxxOrder($value)
	 * @property int $lxx_order
	 *
	 */
	protected $lxx_order;

	/**
	 *
	 * @OA\Property(
	 *   title="orthodox_order",
	 *   type="integer",
	 *   description="The standard book order for the `orthodox_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereOrthodoxOrder($value)
	 * @property int $orthodox_order
	 *
	 */
	protected $orthodox_order;

	/**
	 *
	 * @OA\Property(
	 *   title="nrsva_order",
	 *   type="integer",
	 *   description="The standard book order for the `nrsva_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereNrsvaOrder($value)
	 * @property int $nrsva_order
	 *
	 */
	protected $nrsva_order;

	/**
	 *
	 * @OA\Property(
	 *   title="catholic_order",
	 *   type="integer",
	 *   description="The standard book order for the `catholic_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereCatholicOrder($value)
	 * @property int $catholic_order
	 *
	 */
	protected $catholic_order;

	/**
	 *
	 * @OA\Property(
	 *   title="finnish_order",
	 *   type="integer",
	 *   description="The standard book order for the `finnish_order` in ascending order from Genesis onwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereFinnishOrder($value)
	 * @property int $finnish_order
	 *
	 */
	protected $finnish_order;

	/**
	 *
	 * @OA\Property(
	 *   title="testament_order",
	 *   type="integer",
	 *   description="The standard book order within a testament in ascending order from Genesis to Malachi, and Matthew to Revelations",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereBookOrder($value)
	 * @property int $testament_order
	 *
	 */
	protected $testament_order;

	/**
	 *
	 * @OA\Property(
	 *   title="book_testament",
	 *   type="string",
	 *   description="A short code identifying the testament containing the book",
	 *   enum={"OT","NT","AP"},
	 *   minLength=2,
	 *   maxLength=2
	 * )
	 *
	 * @method static Book whereBookTestament($value)
	 * @property string $book_testament
	 *
	 */
	protected $book_testament;

	/**
	 *
	 * @OA\Property(
	 *   title="book_group",
	 *   type="string",
	 *   description="An english name for the section of books that current book can be categorized in",
	 *   enum={"Historical Books","Pauline Epistles","General Epistles","Apostolic History","Minor Prophets","Major Prophets","The Law","Wisdom Books","Gospels","Apocalypse"}
	 * )
	 *
	 * @method static Book whereBookGroup($value)
	 * @property string $book_group
	 *
	 */
	protected $book_group;

	/**
	 *
	 * @OA\Property(
	 *   title="chapters",
	 *   type="integer",
	 *   nullable=true,
	 *   description="The book's number of chapters"
	 * )
	 *
	 * @method static Book whereChapters($value)
	 * @property int|null $chapters
	 *
	 */
	protected $chapters;

	/**
	 *
	 * @OA\Property(
	 *   title="verses",
	 *   type="integer",
	 *   nullable=true,
	 *   description="The book's number of verses"
	 * )
	 *
	 * @method static Book whereVerses($value)
	 * @property int|null $verses
	 *
	 */
	protected $verses;

	/**
	 *
	 * @OA\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The English name of the book"
	 * )
	 *
	 * @method static Book whereName($value)
	 * @property string $name
	 *
	 */
	protected $name;

	/**
	 *
	 * @OA\Property(
	 *   title="notes",
	 *   type="string",
	 *   description="Any archivist notes about the book"
	 * )
	 *
	 * @method static Book whereNotes($value)
	 * @property string $notes
	 *
	 */
	protected $notes;

	/**
	 *
	 * @OA\Property(
	 *   title="description",
	 *   type="string",
	 *   description="The book's description"
	 * )
	 *
	 * @method static Book whereDescription($value)
	 * @property string $description
	 *
	 */
	protected $description;

	/**
	 *
	 * @OA\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The timestamp for the books creation"
	 * )
	 *
	 * @method static Book whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 *
	 */
	protected $created_at;

	/**
	 *
	 * @OA\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The timestamp for the last update of the book"
	 * )
	 *
	 * @method static Book whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 *
	 */
	protected $updated_at;

    public function translations()
    {
        return $this->HasMany(BookTranslation::class, 'book_id');
    }

	public function translation($language_id)
	{
		return $this->HasMany(BookTranslation::class, 'book_id')->where('language_id',$language_id);
	}

    public function currentTranslation()
    {
        return $this->HasOne(BookTranslation::class, 'book_id')->where('language_id',$GLOBALS['i18n_id']);
    }

    public function vernacularTranslation()
    {
        return $this->HasOne(BookTranslation::class, 'book_id')->where('language_id',$this->language_id);
    }

    public function bible()
    {
    	return $this->belongsToMany(Bible::class,'bible_books');
    }

    public function bibleBooks()
    {
	    return $this->HasMany(BibleBook::class);
    }

}
