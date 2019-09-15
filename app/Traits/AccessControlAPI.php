<?php

namespace App\Traits;

use App\Models\User\AccessGroupKey;
use App\Models\User\AccessGroupFileset;
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

            $accessGroups = AccessGroupKey::where('key_id', $key->id)
                ->get()
                ->pluck('access')
                ->where('name', '!=', 'RESTRICTED')
                ->map(function ($access) {
                    return collect($access->toArray())
                        ->only(['id', 'name'])
                        ->all();
                });

            // Use Eloquent everywhere except for this giant request
            $filesets = AccessGroupFileset::select('hash_id')
                ->whereIn('access_group_id', $accessGroups->pluck('id'))->distinct()->get()->pluck('hash_id');

            return (object) [
                'hashes' => $filesets->toArray(),
                'string' => $accessGroups->pluck('name')->implode('-'),
            ];
        });
    }

    public function blockedByAccessControl($fileset)
    {
        $access_control = $this->accessControl($this->key);
        if (!\in_array($fileset->hash_id, $access_control->hashes, true)) {
            return $this->setStatusCode(403)->replyWithError(trans('api.bible_fileset_errors_401'));
        }

        return false;
    }
}
