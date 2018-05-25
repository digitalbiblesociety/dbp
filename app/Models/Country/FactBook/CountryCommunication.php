<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryCommunication
 *
 * @OAS\Schema (
 *     type="object",
 *     description="CountryCommunication",
 *     title="CountryCommunication",
 *     @OAS\Xml(name="CountryCommunication")
 * )
 *
 * @mixin \Eloquent
 */
class CountryCommunication extends Model
{
	public $incrementing = false;


	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Country/properties/id")
	 * @method static CountryCommunication whereCountryId($value)
	 * @property string $country_id
	 */
	protected $country_id;

	/**
	 *
	 * @OAS\Property(
	 *     title="fixed_phones_total",
	 *     description="The fixed_phones_total for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication whereFixedPhonesTotal($value)
	 * @property string $fixed_phones_total
	 */
	protected $fixed_phones_total;

	/**
	 *
	 * @OAS\Property(
	 *     title="fixed_phones_subs_per_100",
	 *     description="The fixed_phones_subs_per_100 for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication whereFixedPhonesSubsPer10($value)
	 * @property string $fixed_phones_subs_per_100
	 */
	protected $fixed_phones_subs_per_100;

	/**
	 *
	 * @OAS\Property(
	 *     title="mobile_phones_total",
	 *     description="The mobile_phones_total for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication whereMobilePhonesTotal($value)
	 * @property string $mobile_phones_total
	 */
	protected $mobile_phones_total;

	/**
	 *
	 * @OAS\Property(
	 *     title="mobile_phones_subs_per_100",
	 *     description="The mobile_phones_subs_per_100 for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication whereMobilePhonesSubsPer1($value)
	 * @property string $mobile_phones_subs_per_100
	 */
	protected $mobile_phones_subs_per_100;

	/**
	 *
	 * @OAS\Property(
	 *     title="phone_system_general_assessment",
	 *     description="The phone_system_general_assessment for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication wherePhoneSystemGeneralAss($value)
	 * @property string $phone_system_general_assessment
	 */
	protected $phone_system_general_assessment;

	/**
	 *
	 * @OAS\Property(
	 *     title="phone_system_international",
	 *     description="The phone_system_international for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication wherePhoneSystemInternational($value)
	 * @property string $phone_system_international
	 */
	protected $phone_system_international;

	/**
	 *
	 * @OAS\Property(
	 *     title="phone_system_domestic",
	 *     description="The phone_system_domestic for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication wherePhoneSystemDomestic($value)
	 * @property string $phone_system_domestic
	 */
	protected $phone_system_domestic;

	/**
	 *
	 * @OAS\Property(
	 *     title="broadcast_media",
	 *     description="The broadcast_media for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication whereBroadcastMedia($value)
	 * @property string $broadcast_media
	 */
	protected $broadcast_media;

	/**
	 *
	 * @OAS\Property(
	 *     title="internet_country_code",
	 *     description="The internet_country_code for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication whereInternetCountryCode($value)
	 * @property string $internet_country_code
	 */
	protected $internet_country_code;

	/**
	 *
	 * @OAS\Property(
	 *     title="internet_total_users",
	 *     description="The internet_total_users for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication whereInternetTotalUsers($value)
	 * @property string $internet_total_users
	 */
	protected $internet_total_users;

	/**
	 *
	 * @OAS\Property(
	 *     title="internet_population_percent",
	 *     description="The internet_population_percent for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication whereInternetPopulationPercentage($value)
	 * @property float $internet_population_percent
	 */
	protected $internet_population_percent;

	/**
	 *
	 * @OAS\Property(
	 *     title="created_at",
	 *     description="The created_at for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication whereCreatedAt($value)
	 * @property string $created_at
	 */
	protected $created_at;

	/**
	 *
	 * @OAS\Property(
	 *     title="updated_at",
	 *     description="The updated_at for the Communications Model",
	 *     type="string",
	 *     minLength=2,
	 *     maxLength=2,
	 *     example="EU"
	 * )
	 *
	 * @method static CountryCommunication whereUpdatedAt($value)
	 * @property string $updated_at
	 */
	protected $updated_at;

}
