<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\VideoTranslations
 *
 * @property int $language_id
 * @property int $video_id
 * @property string $title
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTranslations whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTranslations whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTranslations whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTranslations whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTranslations whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\VideoTranslations whereVideoId($value)
 * @mixin \Eloquent
 */
class VideoTranslations extends Model
{
    //
}
