<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\CountryRegion
 *
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="Country Region",
 *     title="Country Region",
 *     @OA\Xml(name="CountryRegion")
 * )
 *
 */
class CountryRegion extends Model
{
	protected $connection = 'dbp';
	protected $table = 'country_regions';
	public $timestamps = false;

	/**
	 *
	 * @OA\Property(
	 *   title="country_id",
	 *   type="string",
	 *   description="The ID of the ",
	 * )
	 *
	 * @method static CountryRegion whereCountryId($value)
	 * @property string $country_id
	 */
	protected $country_id;
	/**
	 *
	 * @method static CountryRegion whereLanguageId($value)
	 * @property int $language_id
	 */
	protected $language_id;
	/**
	 *
	 * @method static CountryRegion whereName($value)
	 * @property string $name
	 */
	protected $name;
	/**
	 *
	 * @method static CountryRegion whereCreatedAt($value)
	 * @property string|null $created_at
	 */
	protected $created_at;
	/**
	 *
	 * @method static CountryRegion whereUpdatedAt($value)
	 * @property string|null $updated_at
	 */
	protected $updated_at;

}
