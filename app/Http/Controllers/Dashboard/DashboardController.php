<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;

class DashboardController extends APIController
{

	public function index($user = null)
	{
		$user = \Auth::user() ?? $user;
		if(!$user) return redirect()->route('login');
		return view('home', compact('user'));
	}

	public function admin()
	{
		$status['updates'] = '';

		return view('dashboard.admin', compact('status'));
	}
}
