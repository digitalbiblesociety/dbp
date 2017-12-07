<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Bible;

/**
 * App\Models\Bible\Translator
 *
 * @property string $id
 * @property string $name
 * @property string|null $born
 * @property string|null $died
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Bible[] $bibles
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Translator whereBorn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Translator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Translator whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Translator whereDied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Translator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Translator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\Translator whereUpdatedAt($value)
 */
class Translator extends Model
{
    protected $dates = ['created_at', 'updated_at'];
    protected $hidden = ['pivot','created_at','updated_at'];
    public $incrementing = false;


    public function bibles()
    {
        return $this->BelongsToMany(Bible::class);
    }


}