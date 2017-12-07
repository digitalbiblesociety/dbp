<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\Video
 *
 * @property int $id
 * @property int|null $language_id
 * @property string|null $bible_id
 * @property string|null $series
 * @property string|null $episode
 * @property string|null $section
 * @property string $url
 * @property string|null $url_download
 * @property string|null $picture
 * @property int $duration
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\Bible|null $bible
 * @property-read \App\Models\Bible\Book $book
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Video[] $related
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\VideoTranslations[] $translations
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereEpisode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereSeries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Video whereUrlDownload($value)
 */
class Video extends Model
{

	protected $table = "videos";

	public function bible()
	{
		return $this->belongsTo(Bible::class);
	}

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

	public function related()
	{
		return $this->HasMany(Video::class,'episode', 'episode')->select('episode', 'url');
	}

	public function translations()
	{
		return $this->hasMany(VideoTranslations::class);
	}

}
