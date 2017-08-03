<?php

namespace App\Models\Bible;

use App\Models\Language\Language;
use App\Models\Bible\Bible;
use Illuminate\Database\Eloquent\Model;

class BibleTranslation extends Model
{
    protected $hidden = ["created_at","updated_at","bible_id","description"];
    protected $fillable = ['name','glotto_id','description'];

    public function bible()
    {
        return $this->belongsTo(Bible::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'iso');
    }

}