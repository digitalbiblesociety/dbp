<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Resource\ResourceTranslation
 *
 * @property string $iso
 * @property int $resource_id
 * @property int $vernacular
 * @property int $tag
 * @property string $title
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceTranslation whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceTranslation whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceTranslation whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceTranslation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\ResourceTranslation whereVernacular($value)
 * @mixin \Eloquent
 */
class ResourceTranslation extends Model
{
    //
}
