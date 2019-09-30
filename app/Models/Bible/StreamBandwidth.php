<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class StreamBandwidth extends Model
{
    protected $connection = 'dbp';
    protected $table = 'bible_file_stream_bandwidths';
    protected $fillable = ['file_id','file_name','bandwidth','resolution_width','resolution_height','codec','stream'];

    public function file()
    {
        return $this->belongsTo(BibleFile::class, 'bible_file_id', 'id');
    }

    public function transportStream()
    {
        return $this->hasMany(StreamSegment::class);
    }
}
