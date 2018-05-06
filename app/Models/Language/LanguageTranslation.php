<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\LanguageTranslation
 *
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Information regarding language translations",
 *     title="Language Translation",
 *     @OAS\Xml(name="LanguageTranslation")
 * )
 *
 */
class LanguageTranslation extends Model
{
    protected $hidden = ["iso_language"];
    protected $table = 'language_translations';

	/**
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   type="integer",
	 *   description="The incrementing id of the language",
	 *   minimum=0
	 * )
	 *
	 * @method static LanguageTranslation whereId($value)
	 * @property int $id
	 */
    protected $id;
	/**
	 *
	 * @OAS\Property(
	 *   title="language_source",
	 *   type="integer",
	 *   description="The incrementing id of the language_source",
	 *   minimum=0
	 * )
	 *
	 * @method static LanguageTranslation whereLanguageSource($value)
	 * @property int $language_source
	 */
    protected $language_source;
	/**
	 *
	 * @OAS\Property(
	 *   title="language_translation",
	 *   type="integer",
	 *   description="The incrementing id of the language_translation",
	 *   minimum=0
	 * )
	 *
	 * @method static LanguageTranslation whereLanguageTranslation($value)
	 * @property int $language_translation
	 */
    protected $language_translation;
	/**
	 *
	 * @OAS\Property(
	 *   title="name",
	 *   type="integer",
	 *   description="The incrementing id of the name",
	 *   minimum=0
	 * )
	 *
	 * @method static LanguageTranslation whereName($value)
	 * @property string $name
	 */
    protected $name;

	/**
	 *
	 * @OAS\Property(
	 *   title="vernacular",
	 *   type="integer",
	 *   description="The vernacular",
	 *   minimum=0
	 * )
	 *
	 * @method static LanguageTranslation whereVernacular($value)
	 * @property string $vernacular
	 *
	 */
	protected $vernacular;

	/**
	 *
	 * @OAS\Property(
	 *   title="description",
	 *   type="string",
	 *   description="The description of the language translation",
	 *   default="available"
	 * )
	 *
	 * @method static LanguageTranslation whereDescription($value)
	 * @property string|null $description
	 */
    protected $description;

	/**
	 *
	 * @OAS\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The timestamp at which the translation was created at",
	 *   default="available"
	 * )
	 *
	 * @method static LanguageTranslation whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 */
    protected $created_at;
	/**
	 *
	 * @OAS\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The timestamp at which the translation was last updated at",
	 *   default="available"
	 * )
	 *
	 * @method static LanguageTranslation whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 */
    protected $updated_at;

    public static function vernacularTranslation($iso)
    {
        $translation = static::where('iso_translation',$iso)->where('iso_language',$iso)->select('name')->first();
        if(isset($translation)) {
            return $translation->name;
        }
        return NULL;
    }


}
