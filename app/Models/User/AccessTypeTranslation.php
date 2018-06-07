<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Account
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The AccessTypeTranslation",
 *     title="AccessTypeTranslation",
 *     @OAS\Xml(name="AccessTypeTranslation")
 * )
 *
 */
class AccessTypeTranslation extends Model
{
	public $table = 'access_type_translations';

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/AccessType/properties/id")
	 *
	 * @method static AccessTypeTranslation whereAccessFunctionTranslationId($value)
	 * @property string $access_function_translation_id
	 */
	protected $access_function_translation_id;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Language/properties/iso")
	 *
	 * @method static AccessTypeTranslation whereIso($value)
	 * @property string $iso
	 */
	protected $iso;

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(
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
