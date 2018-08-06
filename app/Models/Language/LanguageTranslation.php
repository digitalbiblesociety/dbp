<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\LanguageTranslation
 *
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="Information regarding language translations",
 *     title="Language Translation",
 *     @OA\Xml(name="LanguageTranslation")
 * )
 *
 */
class LanguageTranslation extends Model
{
	protected $connection = 'dbp';
    protected $hidden = ["language_source_id","created_at","updated_at","priority","description","id"];
    protected $table = 'language_translations';

	/**
	 *
	 * @OA\Property(
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
	 * @OA\Property(
	 *   title="language_source_id",
	 *   type="integer",
	 *   description="The incrementing id of the language_source",
	 *   minimum=0
	 * )
	 *
	 * @method static LanguageTranslation whereLanguageSourceId($value)
	 * @property int $language_source_id
	 */
    protected $language_source_id;
	/**
	 *
	 * @OA\Property(
	 *   title="language_translation_id",
	 *   type="integer",
	 *   description="The incrementing id of the language_translation",
	 *   minimum=0
	 * )
	 *
	 * @method static LanguageTranslation whereLanguageTranslationId($value)
	 * @property int $language_translation_id
	 */
    protected $language_translation_id;
	/**
	 *
	 * @OA\Property(
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
	 * @OA\Property(
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
	 * @OA\Property(
	 *     title="Priority",
	 *     description="The priority of the language translation",
	 *     type="integer",
	 *     format="int32",
	 *     minimum=0,
	 *     maximum=255
	 * )
	 *
	 * @property string $description
	 * @method static whereDescription($value)
	 */
	protected $priority;

	/**
	 *
	 * @OA\Property(
	 *   title="description",
	 *   type="string",
	 *   description="The description of the language translation"
	 * )
	 *
	 * @method static LanguageTranslation whereDescription($value)
	 * @property string|null $description
	 */
    protected $description;

	/**
	 *
	 * @OA\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The timestamp at which the translation was created at"
	 * )
	 *
	 * @method static LanguageTranslation whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 */
    protected $created_at;
	/**
	 *
	 * @OA\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The timestamp at which the translation was last updated at"
	 * )
	 *
	 * @method static LanguageTranslation whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 */
    protected $updated_at;

    /**
     * Get translation iso
     *
     * @return string
     */
    public function getIsoTranslationAttribute()
    {
        return $this->translation_iso->iso ?? "";
    }

    /**
     * Get source iso
     *
     * @return string
     */
    public function getIsoSourceAttribute()
    {
        return $this->source_iso->iso ?? "";
    }


    public static function vernacularTranslation($iso)
    {
        $translation = static::where('iso_translation',$iso)->where('iso_language',$iso)->select('name')->first();
        if(isset($translation)) {
            return $translation->name;
        }
        return NULL;
    }

    public function translation_iso()
    {
    	return $this->BelongsTo(Language::class,'language_translation_id','id')->select(['iso','id']);
    }

    public function source_iso()
    {
        return $this->BelongsTo(Language::class,'language_source_id','id')->select(['iso','id']);
    }


}
