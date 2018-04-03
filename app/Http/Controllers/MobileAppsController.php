<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MobileAppsController extends APIController
{

	public function redirectDeepLink(Request $request) {
		try {
			$device = $this->isMobileDevice();
			$app = checkParam('app', null, 'optional') ?? env('DEEPLINKING_APP');

			$data = [];
			if ($device == 'iPhone') {
				$data['primaryRedirection'] = $app;
				$data['secndaryRedirection'] = checkParam('app-store', null, 'optional') ?? env('DEEPLINKING_APPSTORE');
			} else {
				$redirect = checkParam('app-site', null, 'optional') ?? env('DEEPLINKING_WEBSITE');
				return redirect($redirect);
			}
			return view('layouts.partials.deeplink-redirect', $data);
		} catch (Exception $e) {
			Log::error(__METHOD__ . ' ' . $e->getMessage());
			abort(500,$e->getMessage());
		}
	}

	private function isMobileDevice() {
		$aMobileUA = [
			'/iphone/i'     => 'iPhone',
			'/ipod/i'       => 'iPod',
			'/ipad/i'       => 'iPad',
			'/android/i'    => 'Android',
			'/blackberry/i' => 'BlackBerry',
			'/webos/i'      => 'Mobile'
		];
		//Return true if Mobile User Agent is detected
		foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
			if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) return $sMobileOS;
		}
		//Otherwise return false..
		return false;
	}

}
