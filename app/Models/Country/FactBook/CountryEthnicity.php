<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryEthnicity
 *
 * @OAS\Schema (
 *     type="object",
 *     description="",
 *     title="Country Ethnicity",
 *     @OAS\Xml(name="CountryEthnicity")
 * )
 *
 * @mixin \Eloquent
 */
class CountryEthnicity extends Model
{
	public $table = "country_people_ethnicities";
	public $incrementing = false;


	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Country/properties/id")
	 * @method static CountryGeography whereCountryId($value)
	 * @property string $country_id
	 */
	protected $country_id;

	/**
	 *
	 * @OAS\Property(
	 *     title="name",
	 *     description="The name of the people group within the country",
	 *     type="string"
	 * )
	 * @method static CountryGeography whereName($value)
	 * @property string $name
	 */
	protected $name;

	/**
	 *
	 * @OAS\Property(
	 *     title="population_percentage",
	 *     description="The percentage of the people group compared to the total population of the country",
	 *     type="number",
	 *     format="float",
	 *     example="19.10"
	 * )
	 * @method static CountryGeography wherePopulationPercentage($value)
	 * @property float $population_percentage
	 */
	protected $population_percentage;

	/**
	 *
	 * @OAS\Property(
	 *     title="date",
	 *     description="The date the data was archived",
	 *     type="integer",
	 *     nullable=true
	 * )
	 * @method static CountryGeography whereDate($value)
	 * @property int|null $date
	 */
	protected $date;

}
