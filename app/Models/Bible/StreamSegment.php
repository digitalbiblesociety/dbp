<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class StreamSegment extends Model
{
    protected $connection = 'dbp';
    protected $table = 'bible_file_stream_segments';
    protected $fillable = ['file_name','runtime'];

    public function timestamp()
    {
        return $this->belongsTo(BibleFileTimestamp::class);
    }
}
