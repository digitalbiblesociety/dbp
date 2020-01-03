<?php

namespace App\Http\Controllers;

class WelcomeController extends APIController
{
    public function welcome()
    {
        return view('welcome');
    }

    public function overview()
    {
        return view('about.overview');
    }

    // Legal

    public function legal()
    {
        return view('about.legal.overview');
    }

    public function license()
    {
        return view('about.legal.license');
    }

    public function privacyPolicy()
    {
        return view('about.legal.privacy_policy');
    }

    public function eula()
    {
        return view('about.legal.eula');
    }

    // about

    public function relations()
    {
        return view('about.relations');
    }

    public function join()
    {
        return view('about.joining_user');
    }

    public function partnering()
    {
        return view('about.partnering');
    }
}
