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
 * @method static Translator whereBorn($value)
 * @method static Translator whereCreatedAt($value)
 * @method static Translator whereDescription($value)
 * @method static Translator whereDied($value)
 * @method static Translator whereId($value)
 * @method static Translator whereName($value)
 * @method static Translator whereUpdatedAt($value)
 */
class Translator extends Model
{
    protected $connection = 'dbp';
    protected $dates = ['created_at', 'updated_at'];
    protected $hidden = ['pivot','created_at','updated_at'];
    public $incrementing = false;


    public function bibles()
    {
        return $this->BelongsToMany(Bible::class);
    }
}
