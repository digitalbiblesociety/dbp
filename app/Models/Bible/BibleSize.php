<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleSize
 *
 * @property string $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSize whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleSize whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BibleSize extends Model
{
    public $table = "bible_sizes";
    public $incrementing = false;
    public $keyType = 'string';
}
