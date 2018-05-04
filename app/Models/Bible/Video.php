<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\Video
 *
 * @property-read \App\Models\Bible\Bible|null $bible
 * @property-read \App\Models\Bible\Book $book
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Video[] $related
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\VideoTranslations[] $translations
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Video model holds information about biblical films. It serves as a wrapper for all Video{Value} Models including VideoTags and VideoTranslations.",
 *     title="VideoTranslations",
 *     @OAS\Xml(name="VideoTranslations")
 * )
 *
 */
class Video extends Model
{

	protected $table = "videos";

	/**
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereId($value)
	 * @property int $id
	 *
	*/
	protected $id;
	/**
	 *
	 * @OAS\Property(
	 *   title="language_id",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereLanguageId($value)
	 * @property int|null $language_id
	 *
	*/
	protected $language_id;
	/**
	 *
	 * @OAS\Property(
	 *   title="bible_id",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereBibleId($value)
	 * @property string|null $bible_id
	 *
	*/
	protected $bible_id;
	/**
	 *
	 * @OAS\Property(
	 *   title="series",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereSeries($value)
	 * @property string|null $series
	 *
	*/
	protected $series;
	/**
	 *
	 * @OAS\Property(
	 *   title="episode",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereEpisode($value)
	 * @property string|null $episode
	 *
	*/
	protected $episode;
	/**
	 *
	 * @OAS\Property(
	 *   title="section",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereSection($value)
	 * @property string|null $section
	 *
	*/
	protected $section;
	/**
	 *
	 * @OAS\Property(
	 *   title="url",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereUrl($value)
	 * @property string $url
	 *
	*/
	protected $url;
	/**
	 *
	 * @OAS\Property(
	 *   title="url_download",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereUrlDownload($value)
	 * @property string|null $url_download
	 *
	*/
	protected $url_download;
	/**
	 *
	 * @OAS\Property(
	 *   title="picture",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video wherePicture($value)
	 * @property string|null $picture
	 *
	*/
	protected $picture;
	/**
	 *
	 * @OAS\Property(
	 *   title="duration",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereDuration($value)
	 * @property int $duration
	 *
	*/
	protected $duration;
	/**
	 *
	 * @OAS\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 *
	*/
	protected $created_at;
	/**
	 *
	 * @OAS\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static Video whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 *
	*/
	protected $updated_at;

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
