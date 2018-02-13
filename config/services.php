<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'github' => [
	    'client_id'     => env('GITHUB_CLIENT_ID'),
	    'client_secret' => env('GITHUB_CLIENT_SECRET'),
	    'redirect'      => env('APP_URL').'/login/github/callback',
    ],

	'facebook' => [
		'client_id'     => env('FACEBOOK_CLIENT_ID'),
		'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
		'redirect'      => env('APP_URL').'/login/facebook/callback',
	],

    'twitter' => [
	    'client_id'     => env('TWITTER_CLIENT_ID') ?? env('DEV_TWITTER_CLIENT_ID'),
	    'client_secret' => env('TWITTER_CLIENT_SECRET') ?? env('DEV_TWITTER_CLIENT_SECRET'),
	    'redirect'      => env('APP_URL').'/login/twitter/callback',
    ],

    'google' => [
	    'client_id'     => env('GOOGLE_CLIENT_ID') ?? env('DEV_GOOGLE_CLIENT_ID'),
	    'client_secret' => env('GOOGLE_CLIENT_SECRET') ?? env('DEV_GOOGLE_CLIENT_SECRET'),
	    'redirect'      => env('APP_URL').'/login/google/callback',
    ],

    'reddit' => [
	    'client_id'     => env('REDDIT_CLIENT_ID'),
	    'client_secret' => env('REDDIT_CLIENT_SECRET'),
	    'redirect'      => env('APP_URL').'/login/reddit/callback',
    ],


];
