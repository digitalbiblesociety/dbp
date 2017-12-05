<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryTransportation
 *
 * @property string $country_id
 * @property int|null $air_carriers
 * @property int|null $aircraft
 * @property int|null $aircraft_passengers
 * @property int|null $aircraft_freight
 * @property string|null $aircraft_code_prefix
 * @property string|null $airports
 * @property string|null $airports_paved
 * @property int|null $airports_info_date
 * @property string|null $major_seaports
 * @property string|null $oil_terminals
 * @property string|null $cruise_ports
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereAirCarriers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereAircraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereAircraftCodePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereAircraftFreight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereAircraftPassengers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereAirports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereAirportsInfoDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereAirportsPaved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereCruisePorts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereMajorSeaports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereOilTerminals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryTransportation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CountryTransportation extends Model
{
	public $incrementing = false;
	public $table = "country_transportation";
}
