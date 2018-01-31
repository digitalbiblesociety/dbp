<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Resource\ResourceLink
 *
 * @property int $resource_id
 * @property string $title
 * @property string|null $size
 * @property string $type
 * @property string $url
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceLink whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceLink whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceLink whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceLink whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceLink whereUrl($value)
 * @mixin \Eloquent
 */
class ResourceLink extends Model
{
    //
}
