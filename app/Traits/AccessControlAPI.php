<?php

namespace App\Traits;

use App\Models\User\AccessGroup;
use App\Models\User\AccessType;
use App\Models\User\Key;

trait AccessControlAPI
{
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
        return \Cache::remember('access_control:'.$api_key, 2400, function () use ($api_key) {
            $user_location = geoip(request()->ip());
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

            $key = Key::select('id')->where('key', $api_key)->first();
            $dbp_connection = config('database.connections.dbp.database');
            $dbp_users_connection = config('database.connections.dbp_users.database');
            $accessGroups = \DB::connection('dbp')
               ->table('access_groups')
               ->where('name', '!=', 'RESTRICTED')
                ->join($dbp_users_connection.'.access_group_api_keys as keys', function ($join) use ($key) {
                    $join->on('keys.access_group_id', 'access_groups.id')->where('key_id', $key->id);
                })
               ->join($dbp_connection.'.access_group_types as types', function ($join) use ($key) {
                   $join->on('types.access_group_id', 'access_groups.id')->where('key_id', $key->id);
               })->select(['access_groups.name','access_groups.id'])->get();

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
