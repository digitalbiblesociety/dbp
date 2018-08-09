<?php

namespace App\Http\Controllers;

class WelcomeController extends APIController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('welcome');
    }
}
