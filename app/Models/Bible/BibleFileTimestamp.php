<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFileTimestamp
 *
 * @property int $id
 * @property string $bible_fileset_id
 * @property string $bible_file_id
 * @property string $book_id
 * @property int|null $chapter_start
 * @property int|null $chapter_end
 * @property int|null $verse_start
 * @property int|null $verse_end
 * @property float $timestamp
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\Bible\Book $book
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereBibleFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereBibleFilesetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereChapterEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereChapterStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereVerseEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereVerseStart($value)
 * @property string $set_id
 * @property int $file_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTimestamp whereSetId($value)
 */
class BibleFileTimestamp extends Model
{
	protected $table = 'bible_file_timestamps';
	public $primaryKey = 'bible_file_id';

	public $incrementing = false;

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

}
