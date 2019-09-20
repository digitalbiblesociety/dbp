<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\APIController;
use App\Models\User\User;

class AdminDetailsController extends APIController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRoutes()
    {
        $routes = \Route::getRoutes();
        $data = ['routes' => $routes];

        return view('dashboard.route-details', $data);
    }

    /**
     * Display active users page.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeUsers()
    {
        $users = User::count();
        return view('dashboard.active-users', ['users' => $users]);
    }

    public function phpInfo()
    {
        // Generate array from php info
        ob_start();
        phpinfo(INFO_MODULES);
        $s = ob_get_contents();
        ob_end_clean();
        $s = strip_tags($s, '<h2><th><td>');
        $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', '<info>\1</info>', $s);
        $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', '<info>\1</info>', $s);
        $t = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
        $r = [];
        $count = \count($t);
        $p1 = '<info>([^<]+)<\/info>';
        $p2 = '/'.$p1.'\s*'.$p1.'\s*'.$p1.'/';
        $p3 = '/'.$p1.'\s*'.$p1.'/';
        for ($i = 1; $i < $count; $i++) {
            if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $t[$i], $matchs)) {
                $name = trim($matchs[1]);
                $vals = explode("\n", $t[$i + 1]);
                foreach ($vals as $val) {
                    if (preg_match($p2, $val, $matchs)) { // 3cols
                        $r[$name][trim($matchs[1])] = [trim($matchs[2]), trim($matchs[3])];
                    } elseif (preg_match($p3, $val, $matchs)) { // 2cols
                        $r[$name][trim($matchs[1])] = trim($matchs[2]);
                    }
                }
            }
        }
        $phpInfo = $r;

        return view('dashboard.admin.phpInfo', compact('phpInfo'));
    }
}
