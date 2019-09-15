<?php

namespace App\Models\Bible\Study;

use Illuminate\Database\Eloquent\Model;

class LexicalPronunciation extends Model
{
    protected $connection = 'dbp';
    protected $primaryKey = 'lexicon_id';

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['lexicon_id', 'ipa', 'ipa_mod', 'sbl', 'dic', 'dic_mod'];
}
