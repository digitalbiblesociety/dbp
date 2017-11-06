<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\VideoTags
 *
 * @property int $id
 * @property int|null $video_id
 * @property string $category
 * @property string $tag_type
 * @property string $tag
 * @property int|null $language_id
 * @property int|null $organization_id
 * @property string|null $book_id
 * @property int|null $chapter
 * @property int|null $verse_start
 * @property int|null $verse_end
 * @property float|null $time_begin
 * @property float|null $time_end
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereChapter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereTagType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereTimeBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereVerseEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereVerseStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTags whereVideoId($value)
 * @mixin \Eloquent
 */
class VideoTags extends Model
{
    //
}
