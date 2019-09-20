<?php

namespace App\Models\Bible\Study;

use Illuminate\Database\Eloquent\Model;

class LexicalDefinition extends Model
{
    protected $connection = 'dbp';
    protected $fillable = ['definition', 'lexicon_id', 'literal'];
}
