<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Bible\VideoTranslations
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Video Translations model communicates information regarding different translation files for the videos",
 *     title="VideoTranslations",
 *     @OAS\Xml(name="VideoTranslations")
 * )
 *
 */
class VideoTranslations extends Model
{

	protected $table = "video_translations";
	protected $primaryKey = "video_key";
	public $incrementing = false;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Language/properties/id")
	 * @method static VideoTranslations whereLanguageId($value)
	 * @property int $language_id
	*/
    protected $language_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="video_id",
	 *   type="integer",
	 *   description="The incrementing id of the video",
	 *   minimum=1
	 * )
	 *
	 * @method static VideoTranslations whereVideoId($value)
	 * @property int $video_id
	*/
	protected $video_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="file_id",
	 *   type="integer",
	 *   description="The incrementing id of the file timestamp"
	 * )
	 *
	 * @method static VideoTranslations whereTitle($value)
	 * @property string $title
	*/
	protected $title;

    /**
     *
     * @OAS\Property(
     *   title="file_id",
     *   type="integer",
     *   description="The incrementing id of the file timestamp"
     * )
     *
     * @method static VideoTranslations whereDescription($value)
     * @property string $description
    */
	protected $description;


	public function video()
	{
		return BelongsTo::class();
	}

}
