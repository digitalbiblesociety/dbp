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
 * @method static CountryCommunications whereBroadcastMedia($value)
 * @method static CountryCommunications whereCountryId($value)
 * @method static CountryCommunications whereCreatedAt($value)
 * @method static CountryCommunications whereFixedPhonesSubsPer100($value)
 * @method static CountryCommunications whereFixedPhonesTotal($value)
 * @method static CountryCommunications whereInternetCountryCode($value)
 * @method static CountryCommunications whereInternetPopulationPercent($value)
 * @method static CountryCommunications whereInternetTotalUsers($value)
 * @method static CountryCommunications whereMobilePhonesSubsPer100($value)
 * @method static CountryCommunications whereMobilePhonesTotal($value)
 * @method static CountryCommunications wherePhoneSystemDomestic($value)
 * @method static CountryCommunications wherePhoneSystemGeneralAssessment($value)
 * @method static CountryCommunications wherePhoneSystemInternational($value)
 * @method static CountryCommunications whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CountryCommunications extends Model
{
	public $incrementing = false;

}
