<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\User;
use Auth;

class UserController extends APIController
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $user = User::with('accounts','projects')->where('id',$user->id)->first();

        if ($user->isAdmin()) return view('dashboard.admin',compact('user'));
        return view('dashboard.home',compact('user'));
    }
}
