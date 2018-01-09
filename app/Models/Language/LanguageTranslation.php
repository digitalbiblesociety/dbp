<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\LanguageTranslation
 *
 * @property int $id
 * @property int $language_source
 * @property int $language_translation
 * @property string $name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageTranslation whereLanguageSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageTranslation whereLanguageTranslation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $vernacular
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageTranslation whereVernacular($value)
 */
class LanguageTranslation extends Model
{
    protected $hidden = ["iso_language"];
    protected $table = 'language_translations';

    public static function vernacularTranslation($iso)
    {
        $translation = static::where('iso_translation',$iso)->where('iso_language',$iso)->select('name')->first();
        if(isset($translation)) {
            return $translation->name;
        }
        return NULL;
    }


}
