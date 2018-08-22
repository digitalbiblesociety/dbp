<?php

namespace App\Http\Controllers;

use App\Traits\CallsBucketsTrait;

class WelcomeController extends APIController
{

	use CallsBucketsTrait;

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

    public function privacy_policy()
    {
    	return view('about.legal.privacy_policy');
    }

    public function eula()
    {
    	return view('about.legal.eula');
    }

}
