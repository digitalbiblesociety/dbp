<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', 'HomeController@versions');

Route::group(['prefix' => 'v4'], function () {
	Route::get('bible/{id}/{book}/{chapter}',   'BiblesController@text');
	Route::get('bible/{abbr}/book/{book}',      'BiblesController@book');
	Route::get('bible/{abbr}/books',            'BiblesController@books');
	Route::resource('bibles',                   'BiblesController');
	Route::resource('languages',                'LanguagesController');
	Route::resource('alphabets',                'AlphabetsController');
	Route::resource('countries',                'CountriesController');
});