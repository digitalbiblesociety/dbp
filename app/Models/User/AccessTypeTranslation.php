<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Account
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="The AccessTypeTranslation",
 *     title="AccessTypeTranslation",
 *     @OA\Xml(name="AccessTypeTranslation")
 * )
 *
 */
class AccessTypeTranslation extends Model
{
	protected $connection = 'dbp';
	public $table = 'access_type_translations';

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/AccessType/properties/id")
	 *
	 * @method static AccessTypeTranslation whereAccessFunctionTranslationId($value)
	 * @property string $access_function_translation_id
	 */
	protected $access_function_translation_id;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Language/properties/iso")
	 *
	 * @method static AccessTypeTranslation whereIso($value)
	 * @property string $iso
	 */
	protected $iso;

	/**
	 *
	 * @OA\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The translated name for each access type"
	 * )
	 *
	 * @method static AccessTypeTranslation whereName($value)
	 * @property string $name
	 */
	protected $name;

	/**
	 *
	 * @OA\Property(
	 *   title="description",
	 *   type="string",
	 *   description="The translated description for each access type"
	 * )
	 *
	 * @method static AccessTypeTranslation whereName($value)
	 * @property string $name
	 */
	protected $description;

	public function access()
	{
		return $this->belongsTo(AccessGroup::class);
	}

}
