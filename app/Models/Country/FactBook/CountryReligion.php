<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryReligions
 *
 * @OAS\Schema (
 *     type="object",
 *     description="CountryReligion",
 *     title="CountryReligion",
 *     @OAS\Xml(name="CountryReligion")
 * )
 *
 * @mixin \Eloquent
 */
class CountryReligion extends Model
{
	public $incrementing = false;
	public $table = "country_religions";

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Country/properties/id")
	 * @method static CountryReligion whereCountryId($value)
	 * @property string $country_id
	*/
	protected $country_id;
	/**
	 *
	 * @OAS\Property(
     *     title="name",
     *     description="",
     *     type="string"
     * )
	 *
	 * @method static CountryReligion whereName($value)
	 * @property string $name
	*/
	protected $name;
	/**
	 *
	 * @OAS\Property(
     *     title="population_percentage",
     *     description="",
     *     type="string"
     * )
	 *
	 * @method static CountryReligion wherePopulationPercentage($value)
	 * @property float $population_percentage
	*/
	protected $population_percentage;
	/**
	 *
	 * @OAS\Property(
     *     title="date",
     *     description="",
     *     type="string"
     * )
	 *
	 * @method static CountryReligion whereDate($value)
	 * @property int $date
	*/
	protected $date;
	/**
	 *
	 * @OAS\Property(
     *     title="created_at",
     *     description="",
     *     type="string"
     * )
	 *
	 * @method static CountryReligion whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	*/
	protected $created_at;
	/**
	 *
	 * @OAS\Property(
     *     title="updated_at",
     *     description="",
     *     type="string"
     * )
	 *
	 * @method static CountryReligion whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	*/
	protected $updated_at;

}
