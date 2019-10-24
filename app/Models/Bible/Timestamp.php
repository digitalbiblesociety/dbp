<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class Timestamp extends Model
{
    protected $connection = 'dbp';
    protected $table = 'bible_file_timestamps';

    public function bibleFile()
    {
        return $this->belongsTo(BibleFile::class);
    }
}
