<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BookTranslation
 *
 * @property string $iso
 * @property string $book_id
 * @property string $name
 * @property string $name_long
 * @property string $name_short
 * @property string $name_abbreviation
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\Book $book
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BookTranslation whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BookTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BookTranslation whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BookTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BookTranslation whereNameAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BookTranslation whereNameLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BookTranslation whereNameShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BookTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BookTranslation extends Model
{
    protected $table = "book_translations";
    public $incrementing = false;
    protected $hidden = ['created_at','updated_at','book_id','description'];

    public function book()
    {
        return $this->BelongsTo(Book::class);
    }

}
