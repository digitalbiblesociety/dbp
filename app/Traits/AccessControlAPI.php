<?php

namespace App\Traits;

use App\Models\User\AccessGroup;
use App\Models\User\AccessType;

trait AccessControlAPI
{
    use CaptureIpTrait;

    /**
     * Returns a list of filesets (represented by their hash IDs) and an dash-separated list of access group
     * names for the authenticated user.
     *
     * @param string $api_key - The User's API key
     *
     * @return object
     */
    public function accessControl($api_key)
    {
        return \Cache::remember($api_key.'_access_control', 2400, function () use($api_key) {
            $user_location = geoip($this->getIpAddress());
            $country_code = (!isset($user_location->iso_code)) ? $user_location->iso_code : null;
            $continent = (!isset($user_location->continent)) ? $user_location->continent : null;

            // Defaults to type 'api' because that's the only access type; needs modification once there are multiple
            $access_type = AccessType::where('name', 'api')
                ->where(function ($query) use ($country_code) {
                    $query->where('country_id', $country_code);
                })
                ->where(function ($query) use ($continent) {
                    $query->where('continent_id', $continent);
                })
                ->first();

            if (!$access_type) {
                return (object) ['hashes' => [], 'string' => ''];
            }

            $accessGroups = AccessGroup::where('name', '!=', 'RESTRICTED')
                ->whereHas('keys', function ($query) use ($api_key) {
                    $query->where('key_id', $api_key);
                })->whereHas('types', function ($query) use ($access_type) {
                    $query->where('access_types.id', $access_type->id);
                })->select(['name','id'])->getQuery()->get();

            // Use Eloquent everywhere except for this giant request
            $filesets = \DB::connection('dbp')->table('access_group_filesets')->select('hash_id')
                ->whereIn('access_group_id', $accessGroups->pluck('id'))->distinct()->get()->pluck('hash_id');

            return (object) [
                'hashes' => $filesets->toArray(),
                'string' => $accessGroups->pluck('name')->implode('-'),
            ];
        });

    }
}
