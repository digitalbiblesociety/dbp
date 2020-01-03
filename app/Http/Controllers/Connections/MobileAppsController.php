<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\APIController;

class MobileAppsController extends APIController
{
    public function redirectDeepLink()
    {
        try {
            $device = $this->isMobileDevice();
            $app    = checkParam('app') ?? env('DEEPLINKING_APP');

            $data = [];
            if ($device === 'iPhone') {
                $data['primaryRedirection']  = $app;
                $data['secndaryRedirection'] = checkParam('app-store') ?? env('DEEPLINKING_APPSTORE');
            } else {
                $redirect = checkParam('app-site') ?? env('DEEPLINKING_WEBSITE');

                return redirect($redirect);
            }

            return view('layouts.partials.deeplink-redirect', compact($data));
        } catch (\Exception $e) {
            \Log::error(__METHOD__ . ' ' . $e->getMessage());
            abort(500, $e->getMessage());
        }
        return null;
    }

    private function isMobileDevice()
    {
        $aMobileUA = [
            '/iphone/i'     => 'iPhone',
            '/ipod/i'       => 'iPod',
            '/ipad/i'       => 'iPad',
            '/android/i'    => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i'      => 'Mobile',
        ];
        //Return true if Mobile User Agent is detected
        foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
            if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
                return $sMobileOS;
            }
        }

        //Otherwise return false..
        return false;
    }
}
