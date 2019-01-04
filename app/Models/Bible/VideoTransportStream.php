<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class VideoTransportStream extends Model
{
    protected $connection = 'dbp';
    protected $table = 'bible_file_video_transport_stream';
    protected $fillable = ['file_name','runtime'];
}
