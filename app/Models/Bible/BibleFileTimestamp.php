<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFileTimestamp
 *
 * @property-read \App\Models\Bible\Book $book
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Bible File Timestamp tag model partitions the file into verse by verse sections",
 *     title="Bible File Timestamp",
 *     @OAS\Xml(name="BibleFileTimestamp")
 * )
 *
 */

class BibleFileTimestamp extends Model
{
	protected $table = 'bible_file_timestamps';
	public $primaryKey = 'bible_file_id';


	/**
	 *
	 * @OAS\Property(
	 *   title="file_id",
	 *   type="integer",
	 *   description="The incrementing id of the file timestamp",
	 *   minimum=1
	 * )
	 *
     * @method static BibleFileTimestamp whereFileId($value)
	 * @property int $id
	 */
	protected $id;

	/**
	 *
	 * @OAS\Property(
	 *   title="verse_start",
	 *   type="integer",
	 *   description="The starting verse for the file timestamp",
	 *   minimum=1
	 * )
	 *
	 * @method static BibleFileTimestamp whereVerseStart($value)
	 * @property int|null $verse_start
	 *
	 */
	protected $verse_start;

	/**
	 *
	 * @OAS\Property(
	 *   title="verse_end",
	 *   type="integer",
	 *   description="The ending verse for the file timestamp",
	 *   minimum=1
	 * )
	 *
	 * @method static BibleFileTimestamp whereVerseEnd($value)
	 * @property int|null $verse_end
	 *
	 */
	protected $verse_end;

	/**
	 *
	 * @OAS\Property(
	 *   title="timestamp",
	 *   type="integer",
	 *   description="The ending verse for the file timestamp",
	 *   minimum=1
	 * )
	 *
     * @method static BibleFileTimestamp whereTimestamp($value)
	 * @property float $timestamp
	 *
	 */
	protected $timestamp;



	public $incrementing = false;

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

}
