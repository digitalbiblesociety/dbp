<?php

namespace App\Traits;

use App\Models\User\AccessGroup;
use App\Models\User\AccessType;

trait AccessControlAPI
{
    use CaptureIpTrait;

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
        $user_location = geoip($this->getIpAddress());

        $country_code = (!isset($user_location->iso_code)) ? $user_location->iso_code : null;
        $continent = (!isset($user_location->continent)) ? $user_location->continent : null;

        $accessGroups = \DB::connection('dbp')->table('access_groups')
            ->where('access_groups.name', '!=', 'RESTRICTED')
            ->leftJoin(config('database.connections.dbp_users.database').'.access_group_keys as keys', function ($join) use ($api_key) {
                $join->on('keys.access_group_id', '=', 'access_groups.id')->where('keys.key_id', '=', $api_key);
            })
            ->leftJoin('access_group_filesets', 'access_group_filesets.access_group_id', 'access_groups.id')
            ->leftJoin('access_group_types as group_type', function ($join) {
                $join->on('group_type.access_group_id', 'access_groups.id');
            })
            ->leftJoin('access_types', function ($join) use ($continent,$country_code) {
                $join->on('group_type.access_type_id', 'access_types.id')
                    ->where('access_types.name', 'api')
                    ->where('country_id', $country_code)
                    ->where('continent_id', $continent);
            })
            ->select(['access_group_filesets.hash_id'])->orderBy('hash_id')->distinct()->get()->pluck('hash_id');

        return (object) [
            'hashes' => $accessGroups,
            'string' => md5($accessGroups)
        ];
    }
}
