<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BookTranslation
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Book Translation's model",
 *     title="BookTranslation",
 *     @OA\Xml(name="BookTranslation")
 * )
 *
 */
class BookTranslation extends Model
{
	protected $connection = 'dbp';
    protected $table = "book_translations";
    public $incrementing = false;
    protected $hidden = ['created_at','updated_at','book_id','description'];

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Language/properties/iso")
	 * @method static BookTranslation whereIso($value)
	 * @property string $iso
	 */
	protected $iso;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Book/properties/id")
	 * @method static BookTranslation whereBookId($value)
	 * @property string $book_id
	 */
	protected $book_id;

	/**
	 *
	 * @OA\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The translated name of the biblical book"
	 * )
	 *
	 * @method static BookTranslation whereName($value)
	 * @property string $name
	 */
	protected $name;

	/**
	 *
	 * @OA\Property(
	 *   title="name_long",
	 *   type="string",
	 *   description="The long form of the translated name"
	 * )
	 *
	 * @method static BookTranslation whereNameLong($value)
	 * @property string $name_long
	 */
	protected $name_long;

	/**
	 *
	 * @OA\Property(
	 *   title="name_short",
	 *   type="string",
	 *   description="The short form of the translated name"
	 * )
	 *
	 * @method static BookTranslation whereNameShort($value)
	 * @property string $name_short
	 */
	protected $name_short;

	/**
	 *
	 * @OA\Property(
	 *   title="name_abbreviation",
	 *   type="string",
	 *   description="The abbreviated form of the translated name"
	 * )
	 *
	 * @method static BookTranslation whereNameAbbreviation($value)
	 * @property string $name_abbreviation
	 */
	protected $name_abbreviation;

	/**
	 *
	 * @OA\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The timestamp that the translated name was originally created"
	 * )
	 *
	 * @method static BookTranslation whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 */
	protected $created_at;

	/**
	 *
	 * @OA\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The timestamp that the translated name was last updated"
	 * )
	 *
	 * @method static BookTranslation whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 */
	protected $updated_at;

	/**
	 *
	 * @property-read \App\Models\Bible\Book $book
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function book()
    {
        return $this->BelongsTo(Book::class,'book_id','id');
    }

}

/**
 *
	 * @OA\Schema (
	 *     schema="BookName",
	 *     description="The Book Name model",
	 *     title="BookName",
	 *     @OA\Xml(name="BookName"),
	 *     @OA\Property(type="string", title="Alternative", description="", property="AL", example=""),
	 *     @OA\Property(type="string", title="Old and New Testament", description="The translated string for the combined testaments of the bible", property="ON", example=""),
	 *     @OA\Property(type="string", title="Old Testament", description="The translated string for the old testament of the bible", property="OT", example="Vieux Testament"),
	 *     @OA\Property(type="string", title="New Testament", description="The translated string for the new testament of the bible", property="NT", example="Nouveau Testament"),
	 *     @OA\Property(type="string", title="Apocrypha", description="The translated string for the collected apocryphal books", property="AP", example=""),
	 *     @OA\Property(type="string", title="Vulgate", description="The translation string for the Vulgate", property="VU", example=""),
	 *     @OA\Property(type="string", title="Ethiopian Orthodox Canon/Geez Translation Additions", description="The translation string for the Ethiopian Orthodox order for books of the bible", property="ET", example=""),
	 *     @OA\Property(type="string", title="Coptic Orthodox Canon Additions", description="The translation string for the Coptic Orthodox order for books of the bible", property="CO", example=""),
	 *     @OA\Property(type="string", title="Armenian Orthodox Canon Additions", description="The translation string for the Armenian Orthodox order for books of the bible", property="AO", example=""),
	 *     @OA\Property(type="string", title="Peshitta", description="The translation string for Peshitta", property="PE", example=""),
	 *     @OA\Property(type="string", title="Codex Sinaiticus", description="The translation string for Codex Sinaiticus", property="CS", example=""),
	 *     @OA\Property(type="string", title="Genesis", property="Gen", description="The translation string for the book Genesis", example="Genèse"),
	 *     @OA\Property(type="string", title="Exodus", property="Exod", example="Exode"),
	 *     @OA\Property(type="string", title="Leviticus", property="Lev", example="Lévitique"),
	 *     @OA\Property(type="string", title="Numbers", property="Num", example="Nombres"),
	 *     @OA\Property(type="string", title="Deuteronomy", property="Deut", example="Deutéronome"),
	 *     @OA\Property(type="string", title="Joshua", property="Josh", example="Josué"),
	 *     @OA\Property(type="string", title="Judges", property="Judg", example="Juges"),
	 *     @OA\Property(type="string", title="Ruth", property="Ruth", example="Ruth"),
	 *     @OA\Property(type="string", title="1 Samuel", property="1Sam", example="Iier Samuel"),
	 *     @OA\Property(type="string", title="2 Samuel", property="2Sam", example="IIième Samuel"),
	 *     @OA\Property(type="string", title="1 Kings", property="1Kgs", example="Iier Rois"),
	 *     @OA\Property(type="string", title="2 Kings", property="2Kgs", example="IIième Rois"),
	 *     @OA\Property(type="string", title="1 Chronicles", property="1Chr", example="Iier Chroniques"),
	 *     @OA\Property(type="string", title="2 Chronicles", property="2Chr", example="IIième Chroniques"),
	 *     @OA\Property(type="string", title="Ezra", property="Ezra", example="Esdras"),
	 *     @OA\Property(type="string", title="Nehemiah", property="Neh", example="Néhémie"),
	 *     @OA\Property(type="string", title="Esther", property="Esth", example="Esther"),
	 *     @OA\Property(type="string", title="Job", property="Job", example="Job"),
	 *     @OA\Property(type="string", title="Psalm", property="Ps", example="Psaumes"),
	 *     @OA\Property(type="string", title="Proverbs", property="Prov", example="Proverbes"),
	 *     @OA\Property(type="string", title="Ecclesiastes", property="Eccl", example="Ecclésiaste"),
	 *     @OA\Property(type="string", title="Song of Solomon", property="Song", example="Cantique des Cantiques"),
	 *     @OA\Property(type="string", title="Isaiah", property="Isa", example="Esaïe"),
	 *     @OA\Property(type="string", title="Jeremiah", property="Jer", example="Jérémie"),
	 *     @OA\Property(type="string", title="Lamentations", property="Lam", example="Lamentation"),
	 *     @OA\Property(type="string", title="Ezekiel", property="Ezek", example="Ezékiel"),
	 *     @OA\Property(type="string", title="Daniel", property="Dan", example="Daniel"),
	 *     @OA\Property(type="string", title="Hosea", property="Hos", example="Osée"),
	 *     @OA\Property(type="string", title="Joel", property="Joel", example="Joël"),
	 *     @OA\Property(type="string", title="Amos", property="Amos", example="Amos"),
	 *     @OA\Property(type="string", title="Obadiah", property="Obad", example="Abdias"),
	 *     @OA\Property(type="string", title="Jonah", property="Jonah", example="Jonas"),
	 *     @OA\Property(type="string", title="Micah", property="Mic", example="Michée"),
	 *     @OA\Property(type="string", title="Nahum", property="Nah", example="Nahum"),
	 *     @OA\Property(type="string", title="Habakkuk", property="Hab", example="Habacuc"),
	 *     @OA\Property(type="string", title="Zephaniah", property="Zeph", example="Sophonie"),
	 *     @OA\Property(type="string", title="Haggai", property="Hag", example="Aggée"),
	 *     @OA\Property(type="string", title="Zechariah", property="Zech", example="Zacharie"),
	 *     @OA\Property(type="string", title="Malachi", property="Mal", example="Malachie"),
	 *     @OA\Property(type="string", title="Matthew", property="Matt", example="Matthieu"),
	 *     @OA\Property(type="string", title="Mark", property="Mark", example="Marc"),
	 *     @OA\Property(type="string", title="Luke", property="Luke", example="Luc"),
	 *     @OA\Property(type="string", title="John", property="John", example="Jean"),
	 *     @OA\Property(type="string", title="Acts", property="Acts", example="Actes"),
	 *     @OA\Property(type="string", title="Romans", property="Rom", example="Romains"),
	 *     @OA\Property(type="string", title="1 Corinthians", property="1Cor", example="Iier Corinthiens"),
	 *     @OA\Property(type="string", title="2 Corinthians", property="2Cor", example="IIième Corinthiens"),
	 *     @OA\Property(type="string", title="Galatians", property="Gal", example="Galates"),
	 *     @OA\Property(type="string", title="Ephesians", property="Eph", example="Ephésiens"),
	 *     @OA\Property(type="string", title="Philippians", property="Phil", example="Philippiens"),
	 *     @OA\Property(type="string", title="Colossians", property="Col", example="Colossiens"),
	 *     @OA\Property(type="string", title="1 Thessalonians", property="1Thess", example="Iier Thessaloniciens"),
	 *     @OA\Property(type="string", title="2 Thessalonians", property="2Thess", example="IIième Thessaloniciens"),
	 *     @OA\Property(type="string", title="1 Timothy", property="1Tim", example="Iier Timothée"),
	 *     @OA\Property(type="string", title="2 Timothy", property="2Tim", example="IIième Timothée"),
	 *     @OA\Property(type="string", title="Titus", property="Titus", example="Tite"),
	 *     @OA\Property(type="string", title="Philemon", property="Phlm", example="Philémon"),
	 *     @OA\Property(type="string", title="Hebrews", property="Heb", example="Hébreux"),
	 *     @OA\Property(type="string", title="James", property="Jas", example="Jacques"),
	 *     @OA\Property(type="string", title="1 Peter", property="1Pet", example="Iier Pierre"),
	 *     @OA\Property(type="string", title="2 Peter", property="2Pet", example="IIième Pierre"),
	 *     @OA\Property(type="string", title="1 John", property="1John", example="Iier Jean"),
	 *     @OA\Property(type="string", title="2 John", property="2John", example="IIième Jean"),
	 *     @OA\Property(type="string", title="3 John", property="3John", example="IIIième Jean"),
	 *     @OA\Property(type="string", title="Jude", property="Jude", example=""),
	 *     @OA\Property(type="string", title="Revelation", property="Rev", example="Apocalypse"),
	 *     @OA\Property(type="string", title="Tobit", property="Tob", example=""),
	 *     @OA\Property(type="string", title="Judith", property="Jdt", example=""),
	 *     @OA\Property(type="string", title="Sirach", property="Sir", example="Siracide"),
	 *     @OA\Property(type="string", title="Baruch", property="Bar", example="Baruc"),
	 *     @OA\Property(type="string", title="Prayer of Azariah", property="PrAzar", example=""),
	 *     @OA\Property(type="string", title="Susanna", property="Sus", example=""),
	 *     @OA\Property(type="string", title="Bel and the Dragon", property="Bel", example=""),
	 *     @OA\Property(type="string", title="1 Maccabees", property="1Macc", example="1 Maccabées"),
	 *     @OA\Property(type="string", title="2 Maccabees", property="2Macc", example="2 Maccabées"),
	 *     @OA\Property(type="string", title="3 Maccabees", property="3Macc", example=""),
	 *     @OA\Property(type="string", title="4 Maccabees", property="4Macc", example=""),
	 *     @OA\Property(type="string", title="Prayer of Manasseh", property="PrMan", example=""),
	 *     @OA\Property(type="string", title="1 Esdras", property="1Esd", example=""),
	 *     @OA\Property(type="string", title="2 Esdras", property="2Esd", example=""),
	 *     @OA\Property(type="string", title="Greek Daniel", property="DanGr", example="")
	 * )
 *
 */