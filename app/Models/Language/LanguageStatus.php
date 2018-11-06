<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\LanguageStatus
 *
 * @property-read \App\Models\Language\LanguageStatus $language_status
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="",
 *     title="Language Status",
 *     @OA\Xml(name="LanguageStatus")
 * )
 *
 */
class LanguageStatus extends Model
{
	protected $connection = 'dbp';
	protected $table = 'language_status';
	protected $fillable = ['id','title','description'];
	protected $keyType = 'string';
	public $incrementing = false;

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
	 *   title="title",
	 *   type="string",
	 *   description="",
	 *   minimum=0
	 * )
	 *
	 * @property $title
	 * @method static LanguageCode whereTitle($value)
	 *
	 */
	protected $title;

	/**
	 *
	 * @OA\Property(
	 *   title="description",
	 *   type="string",
	 *   description="",
	 *   minimum=0
	 * )
	 *
	 * @property $description
	 * @method static LanguageCode whereDescription($value)
	 *
	 */
	protected $description;

	public function language()
	{
		return $this->belongsTo(Language::class);
	}

}
