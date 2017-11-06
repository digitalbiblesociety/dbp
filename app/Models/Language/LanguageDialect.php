<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Language\LanguageDialect
 *
 * @property int $id
 * @property int $language_id
 * @property string|null $dialect_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Language\Language|null $childLanguage
 * @property-read \App\Models\Language\Language $language
 * @property-read \App\Models\Language\Language $parentLanguage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageDialect whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageDialect whereDialectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageDialect whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageDialect whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageDialect whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageDialect whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LanguageDialect extends Model
{
    public $primaryKey = 'glotto_id';
    protected $table = 'languages_dialects';
	protected $hidden = ['language_id','id'];
    public $incrementing = false;

	public function language()
	{
		return $this->belongsTo(Language::class);
	}

	/*
	 * Alias of language
	 */
    public function parentLanguage()
    {
        return $this->belongsTo(Language::class);
    }

	public function childLanguage()
	{
		return $this->belongsTo(Language::class,'dialect_id');
	}

}
