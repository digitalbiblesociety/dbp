<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

class LanguageTranslation extends Model
{
    protected $hidden = ["iso_language"];
    protected $table = 'languages_translations';

    public static function vernacularTranslation($iso)
    {
        $translation = static::where('iso_translation',$iso)->where('iso_language',$iso)->select('name')->first();
        if(isset($translation)) {
            return $translation->name;
        }
        return NULL;
    }


}
