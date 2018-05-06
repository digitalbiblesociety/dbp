<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Language\LanguageCode
 *
 * @property-read \App\Models\Language\Language $language
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Information regarding alternative language coding systems",
 *     title="Language Codes",
 *     @OAS\Xml(name="LanguageCode")
 * )
 *
 */
class LanguageCode extends Model
{

    protected $table = 'language_codes';
    protected $fillable = ['code', 'source', 'glotto_id'];
	protected $hidden = ['language_id'];

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @property string $code
	 * @method static LanguageCode whereCode($value)
	 *
	 */
	protected $code;

	/**
	 *
	 * @property Carbon $created_at
	 * @method static LanguageCode whereCreatedAt($value)
	 *
	 */
	protected $created_at;

	/**
	 *
	 * @property Carbon $updated_at
	 * @method static LanguageCode whereUpdatedAt($value)
	 *
	 */
	protected $updated_at;

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
