<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleSizeTranslation
 *
 * @property string $size_id
 * @property string $name
 * @property string $description
 * @property string $iso
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSizeTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSizeTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSizeTranslation whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSizeTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSizeTranslation whereSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSizeTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $set_size_code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSizeTranslation whereSetSizeCode($value)
 */
class BibleSizeTranslation extends Model
{
    //
}
