<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BookTranslation
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Book Translation's model",
 *     title="BookTranslation",
 *     @OAS\Xml(name="BookTranslation")
 * )
 *
 */
class BookTranslation extends Model
{
    protected $table = "book_translations";
    public $incrementing = false;
    protected $hidden = ['created_at','updated_at','book_id','description'];

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Language/properties/iso")
	 * @method static BookTranslation whereIso($value)
	 * @property string $iso
	 */
	protected $iso;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Book/properties/id")
	 * @method static BookTranslation whereBookId($value)
	 * @property string $book_id
	 */
	protected $book_id;

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
        return $this->BelongsTo(Book::class);
    }

}
