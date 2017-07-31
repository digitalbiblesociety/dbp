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
// Sign URL
Route::get('sign', 'HomeController@signedUrl');


// Version 2 Specific Routes

	// LANGUAGES

	Route::get('library/language', 'LanguagesController@index');
	// TODO: Languages Create
	// TODO: Languages Update

	// VERSIONS

	// [static]  Version Listing
	// [omitted] Version Create
	// [omitted] Version Update
	Route::get('library/version', function () {return json_decode(file_get_contents(public_path('static/version_listing.json')));});
	// TODO: Version Create
	// TODO: Version Update

	// VOLUMES

	// [supported] Volume Listing
	// [omitted] Volume Create
	// [omitted] Volume Update
	// [static] Volume Language List
	// [static] Volume Language Family List
	// [supported] Volume Organization Listing
	// [static] Volume History List
	Route::get('library/volume', 'BiblesController@index');
	// TODO: Volume Create
	// TODO: Volume Update
	Route::get('library/volumelanguage', function () {return json_decode(file_get_contents(public_path('static/volume_language_list.json')));});
	Route::get('library/volumelanguagefamily', function () {return json_decode(file_get_contents(public_path('static/volume_language_family.json')));});
	Route::get('library/volumeorganization', 'OrganizationsController@index')->name('v2_volume_organization_list');
	Route::get('library/volumehistory', function () {return json_decode(file_get_contents(public_path('static/library_volume_history.json')));});



	// BOOKS

	// [supported] Book Order Listing
	// [omitted] Book Order Create
	// [omitted] Book Order Update
	// [omitted] Book Order Delete
	// [supported] Book Listing
	// [] Book Name Listing
	// [omitted] Book Name Create
	// [omitted] Book Name Update
	Route::get('library/bookorder', 'BooksController@show')->name('v2_library_bookorder');
	// TODO: Book Order Create
	// TODO: Book Order Update
	// TODO: Book Order Delete
	Route::get('library/book',     'BooksController@show')->name('v2_library_book');
	Route::get('library/bookname', 'BooksController@bookNames')->name('v2_library_bookname');
	Route::get('library/chapter',  'BooksController@chapters')->name('v2_library_chapter');
	// [] Chapter Listing
	// [] Verse Info Listing
	// [] Numbers Listing
	// [] Numbers Create
	// [] Numbers Update

	// Metadata
	// [] Metadata Listing
	// [] Metadata Create
	// [] Metadata Update

	// Organizations
	// [] Volume Asset Location
	// [] Organization Listing
	// [] Organization Create
	// [] Organization Modify