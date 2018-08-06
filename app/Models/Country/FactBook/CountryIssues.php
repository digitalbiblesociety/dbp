<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryIssues
 *
 * @OA\Schema (
 *     type="object",
 *     description="CountryIssues",
 *     title="CountryIssues",
 *     @OA\Xml(name="CountryIssues")
 * )
 *
 * @mixin \Eloquent
 */
class CountryIssues extends Model
{
	protected $connection = 'dbp';
	public $incrementing = false;

/**
 *
 * @OA\Property(ref="#/components/schemas/Country/properties/id")
 * @static method CountryIssues whereCountryId($value)
 * @property string $country_id
 */
protected $country_id;
/**
 *
 * @OA\Property(
 *     title="international_disputes",
 *     description="",
 *     type="string"
 * )
 *
 * @static method CountryIssues whereInternationalDisputes($value)
 * @property string $international_disputes
 */
protected $international_disputes;
/**
 *
 * @OA\Property(
 *     title="illicit_drugs",
 *     description="",
 *     type="string"
 * )
 *
 * @static method CountryIssues whereIllicitDrugs($value)
 * @property string $illicit_drugs
 */
protected $illicit_drugs;
/**
 *
 * @OA\Property(
 *     title="refugees",
 *     description="",
 *     type="string"
 * )
 *
 * @static method CountryIssues whereRefugees($value)
 * @property string $refugees
 */
protected $refugees;
/**
 *
 * @OA\Property(
 *     title="created_at",
 *     description="",
 *     type="string"
 * )
 *
 * @static method CountryIssues whereCreatedAt($value)
 * @property \Carbon\Carbon|null $created_at
 */
protected $created_at;
/**
 *
 * @OA\Property(
 *     title="updated_at",
 *     description="",
 *     type="string"
 * )
 *
 * @static method CountryIssues whereUpdatedAt($value)
 * @property \Carbon\Carbon|null $updated_at
 */
protected $updated_at;

}
