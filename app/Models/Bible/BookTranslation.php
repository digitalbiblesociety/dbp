<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BookTranslation
 * @mixin \Eloquent
 *
 * @method static BookTranslation whereIso($value)
 * @method static BookTranslation whereBookId($value)
 * @method static BookTranslation whereName($value)
 * @method static BookTranslation whereNameLong($value)
 * @method static BookTranslation whereNameShort($value)
 * @method static BookTranslation whereNameAbbreviation($value)
 * @method static BookTranslation whereCreatedAt($value)
 * @method static BookTranslation whereUpdatedAt($value)
 *
 * @property string $iso
 * @property string $book_id
 * @property string $name
 * @property string $name_long
 * @property string $name_short
 * @property string $name_abbreviation
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
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
    protected $table = 'book_translations';
    public $incrementing = false;
    protected $hidden = ['created_at','updated_at','book_id','description'];

    /**
     *
     * @OA\Property(ref="#/components/schemas/Language/properties/iso")
     */
    protected $iso;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Book/properties/id")
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
     */
    protected $updated_at;

    /**
     *
     * @property-read \App\Models\Bible\Book $book
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
}
