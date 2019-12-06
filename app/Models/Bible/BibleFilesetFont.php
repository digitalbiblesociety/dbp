<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

class BibleFilesetFont extends Model
{
    protected $connection = 'dbp';
    public $incrementing = false;
    public $keyType = 'string';
    public $primaryKey = 'hash_id';

    protected $hash_id;
    protected $font_id;
    protected $created_at;
    protected $updated_at;

    public function fileset()
    {
        return $this->belongsTo(BibleFileset::class, 'hash_id', 'hash_id');
    }

    public function font()
    {
        return $this->belongsTo(Font::class, 'id', 'font_id');
    }
}
