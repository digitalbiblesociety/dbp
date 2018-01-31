<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\LanguageBibleInfo
 *
 * @property int $language_id
 * @property int|null $bible_status
 * @property int|null $bible_translation_need
 * @property int|null $bible_year
 * @property int|null $bible_year_newTestament
 * @property int|null $bible_year_portions
 * @property string|null $bible_sample_text
 * @property string|null $bible_sample_img
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageBibleInfo whereBibleSampleImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageBibleInfo whereBibleSampleText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageBibleInfo whereBibleStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageBibleInfo whereBibleTranslationNeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageBibleInfo whereBibleYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageBibleInfo whereBibleYearNewTestament($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageBibleInfo whereBibleYearPortions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageBibleInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageBibleInfo whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\LanguageBibleInfo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LanguageBibleInfo extends Model
{
    public $incrementing = false;
    public $table = 'language_bibleInfo';

}
