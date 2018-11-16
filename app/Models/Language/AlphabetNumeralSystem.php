<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

class AlphabetNumeralSystem extends Model
{
    protected $table = 'alphabet_numeral_systems';
    protected $connection = 'dbp';
    public $incrementing = false;
}
