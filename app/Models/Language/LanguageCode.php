<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\LanguageCode
 *
 * @property-read \App\Models\Language\Language $language
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="Information regarding alternative language coding systems",
 *     title="Language Codes",
 *     @OA\Xml(name="LanguageCode")
 * )
 *
 */
class LanguageCode extends Model
{
    protected $connection = 'dbp';
    protected $table = 'language_codes';
    protected $fillable = ['code', 'source', 'glotto_id'];
    protected $hidden = ['language_id'];

    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="integer",
     *   description="The incrementing id of the language Code",
     *   minimum=0
     * )
     *
     * @property int $id
     * @method static LanguageCode whereId($value)
     *
     */
    protected $id;
    /**
     *
     * @OA\Property(
     *   title="language_id",
     *   type="integer",
     *   description="The foreign key pointing to the incrementing id of the language",
     *   minimum=0
     * )
     *
     * @property int $language_id
     * @method static LanguageCode whereLanguageId($value)
     *
     */
    protected $language_id;
    /**
     *
     * @OA\Property(
     *   title="source",
     *   type="string",
     *   description="The source pointing to the incrementing id of the language"
     * )
     *
     * @property string $source
     * @method static LanguageCode whereSource($value)
     *
     */
    protected $source;
    /**
     *
     * @OA\Property(
     *   title="code",
     *   type="string",
     *   description="The foreign code for the language"
     * )
     *
     * @property string $code
     * @method static LanguageCode whereCode($value)
     *
     */
    protected $code;

    /**
     *
     * @property \Carbon\Carbon $created_at
     * @method static LanguageCode whereCreatedAt($value)
     *
     */
    protected $created_at;

    /**
     *
     * @property \Carbon\Carbon $updated_at
     * @method static LanguageCode whereUpdatedAt($value)
     *
     */
    protected $updated_at;

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
