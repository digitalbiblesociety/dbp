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
 * @method static Builder|BibleSizeTranslation whereCreatedAt($value)
 * @method static Builder|BibleSizeTranslation whereDescription($value)
 * @method static Builder|BibleSizeTranslation whereIso($value)
 * @method static Builder|BibleSizeTranslation whereName($value)
 * @method static Builder|BibleSizeTranslation whereSizeId($value)
 * @method static Builder|BibleSizeTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $set_size_code
 * @method static Builder|BibleSizeTranslation whereSetSizeCode($value)
 */
class BibleSizeTranslation extends Model
{
    protected $connection = 'dbp';
    protected $table = 'bible_size_translations';

    protected $set_size_code;
}
