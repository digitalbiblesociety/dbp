<?php

namespace App\Models\Bible\Study;

use Illuminate\Database\Eloquent\Model;

class CommentaryTranslation extends Model
{
    protected $connection = 'dbp';

    protected $hidden = ['created_at','updated_at','id','commentary_id'];

    public function commentary()
    {
        return $this->belongsTo(Commentary::class);
    }

}
