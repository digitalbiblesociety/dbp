<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class StreamBytes extends Model
{
    protected $connection = 'dbp';
    protected $table = 'bible_file_stream_bytes';

    public function timestamp()
    {
        return $this->belongsTo(BibleFileTimestamp::class);
    }
}
