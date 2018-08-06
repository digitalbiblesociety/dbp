<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryTransportation
 *
 * @OA\Schema (
 *     type="object",
 *     description="CountryTransportation",
 *     title="CountryTransportation",
 *     @OA\Xml(name="CountryTransportation")
 * )
 *
 * @mixin \Eloquent
 */
class CountryTransportation extends Model
{
	protected $connection = 'dbp';
	public $incrementing = false;
	public $table = "country_transportation";


	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Country/properties/id")
	 * @method static CountryTransportation whereCountryId($value)
	 * @property string $country_id
	 */
	protected $country_id;
	/**
	 *
	 * @OA\Property(
     *     title="air_carriers",
     *     description="National air transport system; number of registered air carriers",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereAirCarriers($value)
	 * @property string $air_carriers
	 */
	protected $air_carriers;
	/**
	 *
	 * @OA\Property(
     *     title="aircraft",
     *     description="National air transport system; inventory of registered aircraft operated by air carriers",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereAircraft($value)
	 * @property string $aircraft
	 */
	protected $aircraft;
	/**
	 *
	 * @OA\Property(
     *     title="aircraft_passengers",
     *     description="annual passenger traffic on registered air carriers",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereAircraftPassengers($value)
	 * @property string $aircraft_passengers
	 */
	protected $aircraft_passengers;
	/**
	 *
	 * @OA\Property(
     *     title="aircraft_freight",
     *     description="annual freight traffic on registered air carriers",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereAircraftFreight($value)
	 * @property string $aircraft_freight
	 */
	protected $aircraft_freight;
	/**
	 *
	 * @OA\Property(
     *     title="aircraft_code_prefix",
     *     description="Civil aircraft registration country code prefix",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereAircraftCodePrefix($value)
	 * @property string $aircraft_code_prefix
	 */
	protected $aircraft_code_prefix;
	/**
	 *
	 * @OA\Property(
     *     title="airports",
     *     description="Airports",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereAirports($value)
	 * @property string $airports
	 */
	protected $airports;
	/**
	 *
	 * @OA\Property(
     *     title="airports_paved",
     *     description="Airports - with paved runways; total",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereAirportsPaved($value)
	 * @property string $airports_paved
	 */
	protected $airports_paved;
	/**
	 *
	 * @OA\Property(
     *     title="airports_info_date",
     *     description="Airports; sub_field",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereAirportsInfoDate($value)
	 * @property string $airports_info_date
	 */
	protected $airports_info_date;
	/**
	 *
	 * @OA\Property(
     *     title="major_seaports",
     *     description="Ports and terminals",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereMajorSeaports($value)
	 * @property string $major_seaports
	 */
	protected $major_seaports;
	/**
	 *
	 * @OA\Property(
     *     title="oil_terminals",
     *     description="oil terminal(s)",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereOilTerminals($value)
	 * @property string $oil_terminals
	 */
	protected $oil_terminals;
	/**
	 *
	 * @OA\Property(
     *     title="cruise_ports",
     *     description="cruise port(s)",
     *     type="string"
     * )
	 *
	 * @method static CountryTransportation whereCruisePorts($value)
	 * @property string $cruise_ports
	 */
	protected $cruise_ports;

}
