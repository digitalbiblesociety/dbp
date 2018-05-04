<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\VideoTags
 *
 * @mixin \Eloquent
 * @method static VideoTags whereBookId($value)
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The VideoTags model holds miscellaneous information about the video model.",
 *     title="VideoTags",
 *     @OAS\Xml(name="VideoTags")
 * )
 *
 */
class VideoTags extends Model
{

	/**
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   type="integer",
	 *   description="The incrementing id of the video tag"
	 * )
	 *
	 * @method static VideoTags whereId($value)
	 * @property int $id
	 */
	protected $id;

	/**
	 *
	 * @OAS\Property(
	 *   title="video_id",
	 *   type="integer",
	 *   description="The video id"
	 * )
	 *
	 * @method static VideoTags whereVideoId($value)
	 * @property int|null $video_id
	 */
	protected $video_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="category",
	 *   type="integer",
	 *   description="The category"
	 * )
	 *
	 * @method static VideoTags whereCategory($value)
	 * @property string $category
	 */
	protected $category;

	/**
	 *
	 * @OAS\Property(
	 *   title="category",
	 *   type="integer",
	 *   description="The category"
	 * )
	 *
	 * @method static VideoTags whereTagType($value)
	 * @property string $tag_type
	 */
	protected $tag_type;

	/**
	 *
	 * @OAS\Property(
	 *   title="tag",
	 *   type="string",
	 *   description="The tag"
	 * )
	 *
	 * @method static VideoTags whereTag($value)
	 * @property string $tag
	 */
	protected $tag;

	/**
	 *
	 * @OAS\Property(
	 *   title="tag",
	 *   type="integer",
	 *   description="The language_id",
	 *   nullable=true
	 * )
	 *
	 * @method static VideoTags whereLanguageId($value)
	 * @property int|null $language_id
	 */
	protected $language_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="organization_id",
	 *   type="integer",
	 *   description="The language_id",
	 *   nullable=true
	 * )
	 *
	 * @method static VideoTags whereOrganizationId($value)
	 * @property int|null $organization_id
	 */
	protected $organization_id;

	/**
     *
	 * @OAS\Property(
	 *   title="book_id",
	 *   type="string",
	 *   description="The book id",
	 *   nullable=true
	 * )
	 *
     * @method static VideoTags whereBookId($value)
     * @property string|null $book_id
    */
	protected $book_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="chapter",
	 *   type="integer",
	 *   description="The chapter",
	 *   nullable=true
	 * )
	 *
	 * @method static VideoTags whereChapter($value)
	 * @property int|null $chapter
	 */
	protected $chapter;

	/**
	 *
	 * @OAS\Property(
	 *   title="verse_start",
	 *   type="integer",
	 *   description="The verse_start",
	 *   nullable=true
	 * )
	 *
	 * @method static VideoTags whereVerseStart($value)
	 * @property int|null $verse_start
	 */
	protected $verse_start;

	/**
	 *
	 * @OAS\Property(
	 *   title="verse_end",
	 *   type="integer",
	 *   description="The verse_end",
	 *   nullable=true
	 * )
	 *
	 * @method static VideoTags whereVerseEnd($value)
	 * @property int|null $verse_end
	 */
	protected $verse_end;

	/**
	 *
	 * @OAS\Property(
	 *   title="time_begin",
	 *   type="integer",
	 *   description="The time_begin",
	 *   nullable=true
	 * )
	 *
	 * @method static VideoTags whereTimeBegin($value)
	 * @property float|null $time_begin
	 */
	protected $time_begin;

	/**
	 *
	 * @OAS\Property(
	 *   title="time_end",
	 *   type="integer",
	 *   description="The time_end",
	 *   nullable=true
	 * )
	 *
	 * @method static VideoTags whereTimeEnd($value)
	 * @property float|null $time_end
	 */
	protected $time_end;

	/**
	 *
	 * @OAS\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The created_at",
	 *   nullable=true
	 * )
	 *
	 * @method static VideoTags whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 */
	protected $created_at;

	/**
	 *
	 * @OAS\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The updated_at",
	 *   nullable=true
	 * )
	 *
	 * @method static VideoTags whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 */
	protected $updated_at;


}
