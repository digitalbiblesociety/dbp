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
 * @OAS\Schema (
 *     type="object",
 *     description="The Book model communicates information about the canonical books of the Bible",
 *     title="Book",
 *     @OAS\Xml(name="Book")
 * )
 *
 */
class Book extends Model
{
    protected $table = "books";
    public $incrementing = false;
    public $hidden = ['description','created_at','updated_at','notes'];

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
	 *   title="book_order",
	 *   type="integer",
	 *   description="The standard book order in ascending order from Genesis to Revelations with deuterocanonical books occurring afterwards",
	 *   minimum=0
	 * )
	 *
	 * @method static Book whereBookOrder($value)
	 * @property int $book_order
	 *
	 */
	protected $book_order;

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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

	public function translation($iso = null)
	{
		return $this->HasOne(BookTranslation::class, 'book_id')->where('iso',$iso);
	}

    public function currentTranslation()
    {
        return $this->HasOne(BookTranslation::class, 'book_id')->where('iso',i18n::getCurrentLocale());
    }

    public function vernacularTranslation($iso = null)
    {
        return $this->HasOne(BookTranslation::class, 'book_id')->where('iso',$iso);
    }

    public function bible()
    {
    	return $this->belongsToMany(Bible::class,'bible_books');
    }

}
