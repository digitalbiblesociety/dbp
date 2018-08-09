<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\APIController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class AdminDetailsController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRoutes()
    {
        $routes = Route::getRoutes();
        $data = [
            'routes' => $routes,
        ];

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
}
