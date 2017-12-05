<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryCommunications
 *
 * @property string $country_id
 * @property string $fixed_phones_total
 * @property string $fixed_phones_subs_per_100
 * @property string $mobile_phones_total
 * @property string $mobile_phones_subs_per_100
 * @property string $phone_system_general_assessment
 * @property string $phone_system_international
 * @property string $phone_system_domestic
 * @property string $broadcast_media
 * @property string $internet_country_code
 * @property string $internet_total_users
 * @property float $internet_population_percent
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereBroadcastMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereFixedPhonesSubsPer100($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereFixedPhonesTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereInternetCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereInternetPopulationPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereInternetTotalUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereMobilePhonesSubsPer100($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereMobilePhonesTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications wherePhoneSystemDomestic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications wherePhoneSystemGeneralAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications wherePhoneSystemInternational($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryCommunications whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CountryCommunications extends Model
{
	public $incrementing = false;

}
