<?php

namespace App\Traits;

use App\Models\User\AccessGroup;
use App\Models\User\AccessType;

trait AccessControlAPI
{
    /**
     * Returns a list of filesets (represented by their hash IDs) and an underscore-separated list of access group
     * names for the authenticated user.
     *
     * @param string $api_key - The User's API key
     *
     * @return object
     */
    public function accessControl($api_key)
    {
        /** @todo: Move these lines into trait; $user_location = $this->getIp(); */
        $user_location = geoip(checkParam('ip_address'));

        if (!isset($user_location->iso_code)) {
            $user_location->iso_code   = 'unset';
        }
        if (!isset($user_location->continent)) {
            $user_location->continent = 'unset';
        }
        /** @todo end trait */

        // Defaults to type 'api' because that's the only access type; needs modification once there are multiple
        $access_type = AccessType::where('name', 'api')
            ->where(function($query) use ($user_location) {
                $query->where('country_id', $user_location->iso_code)->orWhere('country_id', '=', null);
            })
            ->where(function($query) use ($user_location) {
                $query->where('continent_id', $user_location->continent)->orWhere('continent_id', '=', null);
            })
            ->first();

        if (!$access_type) {
            return (object) ['hashes' => [], 'string' => ''];
        }

        $accessGroups = AccessGroup::with('filesets')
            ->where('name', '!=', 'RESTRICTED')
            ->whereHas('keys', function ($query) use ($api_key) {
                $query->where('key_id', $api_key);
            })->whereHas('types', function($query) use ($access_type) {
                $query->where('access_types.id', $access_type->id);
            })->get();

        return (object) [
            'hashes' => $accessGroups->flatMap->filesets->pluck('hash_id')->unique()->toArray(),
            'string' => $accessGroups->pluck('name')->implode('-'),
        ];
    }
}
