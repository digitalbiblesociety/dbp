<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Language\LanguageAltName
 *
 * @property int $id
 * @property int $language_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Language\Language $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageAltName whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageAltName whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageAltName whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageAltName whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageAltName whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LanguageAltName extends Model
{

    protected $table = 'language_altNames';
    protected $fillable = ['name', 'language_id'];
	protected $hidden = ['language_id','id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
