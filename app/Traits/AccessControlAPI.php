<?php

namespace App\Traits;

use App\Models\User\AccessGroup;

trait AccessControlAPI {

	/**
	 *
	 * Filters out filesets by the access control tables
	 *
	 * @param $api_key - The User's API key
	 * @param string $type
	 *
	 * @return object
	 */
	public function accessControl($api_key,$type = "api") {

		$user_location = checkParam('ip_address', null, 'optional');
		$user_location = geoip($user_location);
		if(!isset($user_location->iso_code)) $user_location->iso_code = "unset";
		if(!isset($user_location->continent)) $user_location->continent = "unset";

		$access = [];
		$accessGroups = AccessGroup::with('filesets')
			->whereHas('types', function ($query) use ($user_location,$type) {
				$query->where(function($query) use ($user_location) {
					$query->where('country_id', $user_location->iso_code)->orWhere('country_id', '=', null);
				})->where(function($query) use ($user_location) {
					$query->where('country_id', $user_location->continent)->orWhere('continent_id', '=', null);
				})->where('name',$type);
			})->whereHas('keys', function ($query) use ($api_key) {
				$query->where('key_id', $api_key);
			})->get();

		$access['hashes'] = $accessGroups->map(function ($item, $key) use($user_location) {
			return collect($item->filesets)->pluck('hash_id');
		})->unique()->flatten()->toArray();
		$access['string'] = $accessGroups->pluck('name')->implode('_');
		return (object) $access;
	}

}
