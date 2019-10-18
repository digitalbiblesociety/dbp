<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class BibleDefault extends Model
{
    protected $connection = 'dbp';
    protected $table = 'bibles_defaults';
    protected $hidden     = ['id'];

    protected $id;
    protected $type;
    protected $bible_id;
    protected $language_code;
    public $timestamps = false;
    
    public function bible()
    {
        return $this->belongsTo(Bible::class);
    }
}
