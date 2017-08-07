<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
class LanguageAltName extends Model
{

    protected $table = 'languages_altNames';
    protected $fillable = ['name', 'language_id'];
    public $timestamps = false;

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
