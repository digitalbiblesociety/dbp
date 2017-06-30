<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
class LanguageClassification extends Model
{

    protected $table = 'geo.languages_classifications';
    protected $fillable = ['name', 'glotto_id'];
    public $timestamps = false;
    public $incrementing = false;

    public function language()
    {
        return $this->belongsTo(Language::class,'glotto_id','id');
    }

}
