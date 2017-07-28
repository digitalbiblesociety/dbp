<?php

use Illuminate\Http\Request;

Route::get('/',                             'HomeController@versions');
Route::get('bible/{id}/{book}/{chapter}',   'BiblesController@text');
Route::get('bible/{abbr}/book/{book}',      'BiblesController@book');
Route::get('bible/{abbr}/books',            'BiblesController@books');
Route::get('bible/{abbr}/equivalents',      'BiblesController@equivalents')->name('api_bibles.equivalents');
Route::resource('bibles',                   'BiblesController',['names' => [
    'index'   => 'api_bibles.index',
    'edit'    => 'api_bibles.edit',
    'create'  => 'api_bibles.create',
    'show'    => 'api_bibles.show',
]]);
Route::resource('languages',                'LanguagesController',['names' => [
	'index'   => 'api_languages.index',
	'edit'    => 'api_languages.edit',
	'create'  => 'api_languages.create',
	'show'    => 'api_languages.show',
]]);
Route::resource('alphabets',                'AlphabetsController',['names' => [
	'index'   => 'api_alphabets.index',
	'edit'    => 'api_alphabets.edit',
	'create'  => 'api_alphabets.create',
	'show'    => 'api_alphabets.show',
]]);
Route::resource('countries',                'CountriesController',['names' => [
	'index'   => 'api_alphabets.index',
	'edit'    => 'api_alphabets.edit',
	'create'  => 'api_alphabets.create',
	'show'    => 'api_alphabets.show',
]]);
Route::get('library/organization',          'OrganizationsController@index');
Route::resource('organizations',            'OrganizationsController',['names' => [
	'index'   => 'api_organizations.index',
	'edit'    => 'api_organizations.edit',
	'create'  => 'api_organizations.create',
	'show'    => 'api_organizations.show',
]]);
Route::resource('users',            'UsersController',['names' => [
	'index'   => 'api_users.index',
	'edit'    => 'api_users.edit',
	'create'  => 'api_users.create',
	'show'    => 'api_users.show',
]]);


// Version 2 Specific Routes
Route::get('library/book', 'BooksController@show');