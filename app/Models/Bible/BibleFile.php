<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Bible\BibleFile
 *
 * @property string $id
 * @property string $set_id
 * @property string $book_id
 * @property int|null $chapter_start
 * @property int|null $chapter_end
 * @property int|null $verse_start
 * @property int|null $verse_end
 * @property string $file_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\Bible $bible
 * @property-read \App\Models\Bible\Book $book
 * @property-read \App\Models\Bible\BibleFileTimestamp $firstReference
 * @property-read \App\Models\Language\Language $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileTimestamp[] $timestamps
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereChapterEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereChapterStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereVerseEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereVerseStart($value)
 * @property-read \App\Models\Bible\BibleFileset $fileset
 * @property string $hash_id
 * @property-read \App\Models\Bible\BibleFileTitle $title
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFile whereHashId($value)
 */
class BibleFile extends Model
{
	protected $table = "bible_files";
	protected $hidden = ["created_at","updated_at"];

	public function language()
	{
		return $this->hasOne(Language::class);
	}

	public function fileset()
	{
		return $this->BelongsTo(BibleFileset::class,'set_id');
	}

	public function bible()
	{
		return $this->BelongsTo(Bible::class);
	}

	public function book()
	{
		return $this->BelongsTo(Book::class,'book_id','id')->orderBy('book_order');;
	}

	public function timestamps()
	{
		return $this->hasMany(BibleFileTimestamp::class);
	}

	public function firstReference()
	{
		return $this->hasOne(BibleFileTimestamp::class);
	}

	public function currentTitle()
	{
		return $this->hasOne(BibleFileTitle::class,'file_id', 'id');
	}

}
