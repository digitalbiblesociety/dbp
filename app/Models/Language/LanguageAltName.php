<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
class LanguageAltName extends Model
{

    protected $table = 'geo.languages_altNames';
    protected $fillable = ['name', 'glotto_id'];
    public $timestamps = false;
    public $incrementing = false;

    public function language()
    {
        return $this->belongsTo(Language::class,'glotto_id','id');
    }

}
