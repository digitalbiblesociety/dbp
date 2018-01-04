<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Language\LanguageClassification
 *
 * @property int $id
 * @property int $language_id
 * @property string $classification_id
 * @property int $order
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Language\Language $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageClassification whereClassificationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageClassification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageClassification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageClassification whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageClassification whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageClassification whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageClassification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LanguageClassification extends Model
{

    protected $table = 'language_classifications';
    protected $fillable = ['language_id', 'classification_id', 'order', 'name'];
    protected $hidden = ['language_id','id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
