<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\Printable
 *
 * @mixin \Eloquent
 */
class Printable extends Model
{
    protected $table = "bible_print";
    protected $primaryKey = 'bible_id';

}
