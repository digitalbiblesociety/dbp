<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryCommunication
 *
 * @OA\Schema (
 *     type="object",
 *     description="The Country Communication Model stores information about the communication infrastructure of a country as reported by the CIA's World Factbook",
 *     title="Country Communication",
 *     @OA\Xml(name="CountryCommunication")
 * )
 *
 * @mixin \Eloquent
 */
class CountryCommunication extends Model
{
    protected $connection = 'dbp';
    public $incrementing = false;


    /**
     *
     * @OA\Property(ref="#/components/schemas/Country/properties/id")
     * @method static CountryCommunication whereCountryId($value)
     * @property string $country_id
     */
    protected $country_id;

    /**
     *
     * @OA\Property(
     *     title="fixed_phones_total",
     *     description="The `fixed_phones_total` field indicates the approximate number of phones utilizing landlines within a country",
     *     type="string",
     *     example="9.08 million"
     * )
     *
     * @method static CountryCommunication whereFixedPhonesTotal($value)
     * @property string $fixed_phones_total
     */
    protected $fixed_phones_total;

    /**
     *
     * @OA\Property(
     *     title="fixed_phones_subs_per_100",
     *     description="The `fixed_phones_subs_per_100` field describes the approximate number of landline phone subscriptions per 100 people. This gives a sense of the prevalence of landline phone use within a population.",
     *     type="string",
     *     example="40 (July 2015 est.)"
     * )
     *
     * @method static CountryCommunication whereFixedPhonesSubsPer10($value)
     * @property string $fixed_phones_subs_per_100
     */
    protected $fixed_phones_subs_per_100;

    /**
     *
     * @OA\Property(
     *     title="mobile_phones_total",
     *     description="The `mobile_phones_total` field describes the total approximate number of cellphones within a country",
     *     type="string",
     *     example="31.77 million"
     * )
     *
     * @method static CountryCommunication whereMobilePhonesTotal($value)
     * @property string $mobile_phones_total
     */
    protected $mobile_phones_total;

    /**
     *
     * @OA\Property(
     *     title="mobile_phones_subs_per_100",
     *     description="The `mobile_phones_subs_per_100` field describes the total approximate number of cellphone subscriptions within the country per 100 people. This gives a sense of the prevalence of cellphone use within a population.",
     *     type="string",
     *     example="310 (July 2015 est.)"
     * )
     *
     * @method static CountryCommunication whereMobilePhonesSubsPer1($value)
     * @property string $mobile_phones_subs_per_100
     */
    protected $mobile_phones_subs_per_100;

    /**
     *
     * @OA\Property(
     *     title="phone_system_general_assessment",
     *     description="The `phone_system_general_assessment` field describes the general state of the telephone system within the country.",
     *     type="string",
     *     example="Sparse system of open-wire, radiotelephone communications, and low-capacity microwave radio relays"
     * )
     *
     * @method static CountryCommunication wherePhoneSystemGeneralAss($value)
     * @property string $phone_system_general_assessment
     */
    protected $phone_system_general_assessment;

    /**
     *
     * @OA\Property(
     *     title="phone_system_international",
     *     description="The `phone_system_international` field indicates the international phone code and satellite position for a country.",
     *     type="string",
     *     example="country code - 257; satellite earth station - 1 Intelsat (Indian Ocean) (2015)",
     *     @OA\ExternalDocumentation(description="For a full list of country phone codes please refer to Wikipedia",url="https://en.wikipedia.org/wiki/List_of_country_calling_codes")
     * )
     *
     * @method static CountryCommunication wherePhoneSystemInternational($value)
     * @property string $phone_system_international
     */
    protected $phone_system_international;

    /**
     *
     * @OA\Property(
     *     title="phone_system_domestic",
     *     description="This field gives a general assessment of the status of the domestic phone system.",
     *     type="string",
     *     example="Fixed-line teledensity of only about 6 per 100 persons; mobile-cellular teledensity approaching 50 per 100 persons"
     * )
     *
     * @method static CountryCommunication wherePhoneSystemDomestic($value)
     * @property string $phone_system_domestic
     */
    protected $phone_system_domestic;

    /**
     *
     * @OA\Property(
     *     title="broadcast_media",
     *     description="This field indicates the independence and connections between the different companies that operate within the country",
     *     type="string",
     *     example="4 state-controlled national TV channels; Polish and Russian TV broadcasts are available in some areas..."
     * )
     *
     * @method static CountryCommunication whereBroadcastMedia($value)
     * @property string $broadcast_media
     */
    protected $broadcast_media;

    /**
     *
     * @OA\Property(
     *     title="internet_country_code",
     *     description="The TLD extension, Top Level Domain, for the country",
     *     type="string",
     *     format="alpha",
     *     minLength=2,
     *     maxLength=2,
     *     example="af",
     *     @OA\ExternalDocumentation(description="For a full list of top level domains",url="https://en.wikipedia.org/wiki/List_of_Internet_top-level_domains")
     * )
     *
     * @method static CountryCommunication whereInternetCountryCode($value)
     * @property string $internet_country_code
     */
    protected $internet_country_code;

    /**
     *
     * @OA\Property(
     *     title="internet_total_users",
     *     description="The number of people who have access to the internet.",
     *     type="string",
     *     example="1.259 million"
     * )
     *
     * @method static CountryCommunication whereInternetTotalUsers($value)
     * @property string $internet_total_users
     */
    protected $internet_total_users;

    /**
     *
     * @OA\Property(
     *     title="internet_population_percent",
     *     description="The percent of the populace who have access to the internet",
     *     type="number",
     *     format="float",
     *     example="93.0"
     * )
     *
     * @method static CountryCommunication whereInternetPopulationPercentage($value)
     * @property float $internet_population_percent
     */
    protected $internet_population_percent;

    /**
     *
     * @OA\Property(
     *     title="created_at",
     *     description="The created_at timestamp for the Communications Model",
     *     type="string",
     *     format="date-time",
     *     example="2018-02-12 19:35:57"
     * )
     *
     * @method static CountryCommunication whereCreatedAt($value)
     * @property string $created_at
     */
    protected $created_at;

    /**
     *
     * @OA\Property(
     *     title="updated_at",
     *     description="The updated_at timestamp for the Communications Model",
     *     type="string",
     *     format="date-time",
     *     example="2018-02-12 19:35:57"
     * )
     *
     * @method static CountryCommunication whereUpdatedAt($value)
     * @property string $updated_at
     */
    protected $updated_at;
}
