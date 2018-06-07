<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Account
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Access Type",
 *     title="AccessType",
 *     @OAS\Xml(name="AccessType")
 * )
 *
 */
class AccessType extends Model
{
    public $table = 'access_types';

	/**
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   type="integer",
	 *   description="The incrementing id for each Access Type"
	 * )
	 *
	 * @method static AccessType whereId($value)
	 * @property integer $name
	 */
	protected $id;

	/**
	 *
	 * @OAS\Property(
	 *   title="name",
	 *   type="string",
	 *   description="The name for each access type"
	 * )
	 *
	 * @method static AccessType whereName($value)
	 * @property string $name
	 */
    protected $name;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Country/properties/id")
	 * @method static AccessType whereCountryId($value)
	 * @property string $country_id
	 */
    protected $country_id;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Country/properties/continent")
	 * @method static AccessType whereContinent($value)
	 * @property string $continent
	 */
    protected $continent;

	/**
	 *
	 * @OAS\Property(
	 *   title="allowed",
	 *   type="boolean",
	 *   description="If set to false, allowed will change the permission function from a whitelist to a blacklist.",
	 *   minimum=0
	 * )
	 *
	 * @method static AccessType whereAllowed($value)
	 * @property boolean $allowed
	 */
    protected $allowed;

	public function access()
	{
		return $this->belongsTo(AccessGroup::class);
	}

}
