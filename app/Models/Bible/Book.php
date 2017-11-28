<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use i18n;

/**
 * App\Models\Bible\Book
 *
 * @property string $id
 * @property string $id_usfx
 * @property string $id_osis
 * @property int $book_order
 * @property string $book_testament
 * @property string $book_group
 * @property int|null $chapters
 * @property int|null $verses
 * @property string $name
 * @property string $notes
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\BookTranslation $currentTranslation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Text[] $text
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BookTranslation[] $translations
 * @property-read \App\Models\Bible\BookTranslation $vernacularTranslation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereBookGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereBookOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereBookTestament($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereChapters($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereIdOsis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereIdUsfx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Book whereVerses($value)
 * @mixin \Eloquent
 */
class Book extends Model
{
    protected $table = "books";
    public $incrementing = false;
    public $hidden = ['description','created_at','updated_at','notes'];

    public function text()
    {
        return $this->HasMany(Text::class);
    }

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

}
