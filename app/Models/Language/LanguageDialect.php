<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Language\LanguageDialect
 *
 * @property-read \App\Models\Language\Language|null $childLanguage
 * @property-read \App\Models\Language\Language $language
 * @property-read \App\Models\Language\Language $parentLanguage
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Information regarding alternative language dialects",
 *     title="Language Dialect",
 *     @OAS\Xml(name="LanguageDialect")
 * )
 *
 * @mixin \Eloquent
 */
class LanguageDialect extends Model
{
    public $primaryKey = 'glotto_id';
    protected $table = 'language_dialects';
	protected $hidden = ['language_id','id'];
    public $incrementing = false;

	/**
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   type="integer",
	 *   description="The incrementing id of the language",
	 *   minimum=0
	 * )
	 *
	 * @method static LanguageDialect whereId($value)
	 * @property int $id
	 *
	 */
    protected $id;
	/**
	 *
	 * @OAS\Property(
	 *   title="language_id",
	 *   type="integer",
	 *   description="The foreign key pointing at the language id, indicating the parent language",
	 *   minimum=0
	 * )
	 *
	 * @method static LanguageDialect whereLanguageId($value)
	 * @property int $language_id
	 *
	 */
    protected $language_id;
	/**
	 *
	 * @OAS\Property(
	 *   title="dialect_id",
	 *   type="integer",
	 *   description="The foreign key pointing at the language id, indicating the dialect",
	 *   minimum=0
	 * )
	 *
	 * @method static LanguageDialect whereDialectId($value)
	 * @property string|null $dialect_id
	 *
	 */
    protected $dialect_id;
	/**
	 *
	 * @OAS\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The name of the language dialect",
	 *   default="available"
	 * )
	 *
	 * @method static LanguageDialect whereName($value)
	 * @property string $name
	 *
	 */
    protected $name;

	/**
	 *
	 * @OAS\Property(
	 *   title="created_at",
	 *   type="string",
	 *   description="The timestamp for which the language created at",
	 *   default="available"
	 * )
	 *
	 * @method static LanguageDialect whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 *
	 */
    protected $created_at;

	/**
	 *
	 * @OAS\Property(
	 *   title="updated_at",
	 *   type="string",
	 *   description="The timestamp for which the language updated at",
	 *   default="available"
	 * )
	 *
	 * @method static LanguageDialect whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 *
	 */
    protected $updated_at;

	public function language()
	{
		return $this->belongsTo(Language::class);
	}

	/*
	 * Alias of language
	 */
    public function parentLanguage()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }

	public function childLanguage()
	{
		return $this->belongsTo(Language::class,'dialect_id');
	}

}
