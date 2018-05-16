<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Bible\BibleFile
 *
 * @property-read \App\Models\Bible\Bible $bible
 * @property-read \App\Models\Bible\Book $book
 * @property-read \App\Models\Bible\BibleFileTimestamp $firstReference
 * @property-read \App\Models\Language\Language $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFileTimestamp[] $timestamps
 * @mixin \Eloquent
 * @property-read \App\Models\Bible\BibleFileset $fileset
 * @property string $hash_id
 * @property-read \App\Models\Bible\BibleFileTitle $title
 * @method static BibleFile whereHashId($value)
 * @property-read \App\Models\Bible\BibleFileTitle $currentTitle
 * @property int|null $file_size
 * @property int|null $duration
 * @property-read \App\Models\Bible\BibleFilesetConnection $connections
 *
 * @OAS\Schema (
 *     type="object",
 *     required={"filename"},
 *     description="The Bible File Model communicates information about biblical files stored in S3",
 *     title="BibleFile",
 *     @OAS\Xml(name="BibleFile")
 * )
 *
 */
class BibleFile extends Model
{
	protected $table = "bible_files";
	protected $hidden = ["created_at","updated_at"];

	/**
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   type="integer",
	 *   description="The id",
	 *   minimum=0,
	 *   example=4
	 * )
	 *
	 * @method static BibleFile whereId($value)
	 * @property $id
	 */
	protected $id;
	/**
	 *
	 * @OAS\Property(
	 *   title="hash_id",
	 *   type="string",
	 *   description="The hash_id",
	 * )
	 *
	 * @method static BibleFile whereHashId($value)
	 * @property $hash_id
	 */
	protected $hash_id;
	/**
	 *
	 * @OAS\Property(
	 *   title="book_id",
	 *   type="string",
	 *   description="The book_id",
	 * )
	 *
	 * @method static BibleFile whereBookId($value)
	 * @property $book_id
	 */
	protected $book_id;
	/**
	 *
	 * @OAS\Property(
	 *   title="chapter_start",
	 *   type="integer",
	 *   description="The chapter_start",
	 *   minimum=0,
	 *   maximum=150,
	 *   example=4
	 * )
	 *
	 * @method static BibleFile whereChapterStart($value)
	 * @property $chapter_start
	 */
	protected $chapter_start;
	/**
	 *
	 * @OAS\Property(
	 *   title="chapter_end",
	 *   type="integer",
	 *   description="If the Bible File spans multiple chapters this field indicates the last chapter of the selection",
	 *   nullable=true,
	 *   minimum=0,
	 *   maximum=150,
	 *   example=5
	 * )
	 *
	 * @method static BibleFile whereChapterEnd($value)
	 * @property $chapter_end
	 */
	protected $chapter_end;
	/**
	 *
	 * @OAS\Property(
	 *   title="verse_start",
	 *   type="integer",
	 *   description="The starting verse at which the BibleFile reference begins",
	 *   minimum=1,
	 *   maximum=176,
	 *   example=5
	 * )
	 *
	 * @method static BibleFile whereVerseStart($value)
	 * @property $verse_start
	 */
	protected $verse_start;

	/**
	 *
	 * @OAS\Property(
	 *   title="verse_end",
	 *   type="string",
	 *   description="If the Bible File spans multiple verses this value will indicate the last verse in that reference. This value is inclusive, so for the reference John 1:1-4. The value would be 4 and the reference would contain verse 4.",
	 *   nullable=true,
	 *   minimum=1,
	 *   maximum=176,
	 *   example=5
	 * )
	 *
	 * @method static BibleFile whereVerseEnd($value)
	 * @property $verse_end
	 */
	protected $verse_end;

	/**
	 *
	 * @OAS\Property(
	 *   title="verse_text",
	 *   type="string",
	 *   description="If the BibleFile model returns text instead of a file_name this field will contain it.",
	 *   example="And God said unto Abraham, And as for thee, thou shalt keep my covenant, thou, and thy seed after thee throughout their generations."
	 * )
	 *
	 * @method static BibleFile whereVerseText($value)
	 * @property $verse_text
	 */
	protected $verse_text;

	/**
	 *
	 * @OAS\Property(
	 *   title="file_name",
	 *   type="string",
	 *   description="The file_name",
	 *   maxLength=191
	 * )
	 *
	 * @method static BibleFile whereFileName($value)
	 * @property $file_name
	 */
	protected $file_name;

	/**
	 *
	 * @OAS\Property(
	 *   title="file_size",
	 *   type="integer",
	 *   description="The file size in kilobytes"
	 * )
	 *
	 * @method static BibleFile whereFileSize($value)
	 * @property $file_size
	 */
	protected $file_size;

	/**
	 *
	 * @OAS\Property(
	 *   title="duration",
	 *   type="integer",
	 *   description="If the file has a set length of time, this field indicates that time in milliseconds",
	 *   nullable=true,
	 *   minimum=0
	 * )
	 *
	 * @method static BibleFile whereDuration($value)
	 * @property $duration
	 */
	protected $duration;

	public function language()
	{
		return $this->hasOne(Language::class);
	}

	public function fileset()
	{
		return $this->BelongsTo(BibleFileset::class,'set_id');
	}

	public function connections()
	{
		return $this->BelongsTo(BibleFilesetConnection::class);
	}

	public function bible()
	{
		return $this->hasManyThrough(Bible::class,BibleFilesetConnection::class, 'hash_id','id','hash_id','bible_id');
	}

	public function book()
	{
		return $this->BelongsTo(Book::class,'book_id','id')->orderBy('book_order');
	}

	public function timestamps()
	{
		return $this->hasMany(BibleFileTimestamp::class,'file_id','id');
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
