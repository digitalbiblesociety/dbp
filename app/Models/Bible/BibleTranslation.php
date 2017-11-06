<?php

namespace App\Models\Bible;

use App\Models\Language\Language;
use App\Models\Bible\Bible;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleTranslation
 *
 * @property int $id
 * @property string $iso
 * @property string $bible_id
 * @property string|null $bible_variation_id
 * @property int $vernacular
 * @property string $name
 * @property string|null $type
 * @property string|null $features
 * @property string|null $description
 * @property string|null $notes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\Bible $bible
 * @property-read \App\Models\Language\Language $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereBibleVariationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereVernacular($value)
 * @mixin \Eloquent
 */
class BibleTranslation extends Model
{
    protected $hidden = ["created_at","updated_at","bible_id","description"];
    protected $fillable = ['name','description','bible_id','iso'];

    public function bible()
    {
        return $this->belongsTo(Bible::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class,'iso','iso');
    }

}