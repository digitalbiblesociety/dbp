<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;
class LanguageClassification extends Model
{

    protected $table = 'languages_classifications';
    protected $fillable = ['language_id', 'classification_id', 'order', 'name'];
    protected $hidden = ['language_id','id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
