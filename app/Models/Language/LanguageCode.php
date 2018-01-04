<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Language\LanguageCode
 *
 * @property int $id
 * @property int $language_id
 * @property string $source
 * @property string $code
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Language\Language $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageCode whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageCode whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageCode whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LanguageCode extends Model
{

    protected $table = 'language_codes';
    protected $fillable = ['code', 'source', 'glotto_id'];
	protected $hidden = ['language_id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
