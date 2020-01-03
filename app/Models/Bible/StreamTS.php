<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class StreamTS extends Model
{
    protected $connection = 'dbp';
    protected $table = 'bible_file_stream_ts';
    protected $fillable = ['file_name','runtime'];
}
