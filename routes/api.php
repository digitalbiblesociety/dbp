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
	// =========================

	// LANGUAGES
	// =========

	Route::get('library/language', 'LanguagesController@index');
	// TODO: Languages Create
	// TODO: Languages Update

	// VERSIONS
	// ===================================

	// [static]  Version Listing
	Route::get('library/version', function () {return json_decode(file_get_contents(public_path('static/version_listing.json')));});
	// TODO: Version Create
	// TODO: Version Update

	// VOLUMES
	// ===================================

	// [supported] Volume Listing
	Route::get('library/volume', 'BiblesController@index');
	// TODO: Volume CRUD
	// [static] Volume Language List
	Route::get('library/volumelanguage', function () {return json_decode(file_get_contents(public_path('static/volume_language_list.json')));});
	// [static] Volume Language Family List
	Route::get('library/volumelanguagefamily', function () {return json_decode(file_get_contents(public_path('static/volume_language_family.json')));});
	// [supported] Volume Organization Listing
	Route::get('library/volumeorganization', 'OrganizationsController@index')->name('v2_volume_organization_list');
	// [static] Volume History List
	Route::get('library/volumehistory', function () {return json_decode(file_get_contents(public_path('static/library_volume_history.json')));});

	// BOOKS
	// ===================================

	// [supported] Book Order Listing
	Route::get('library/bookorder', 'BooksController@show')->name('v2_library_bookorder');
	// TODO: Book CRUD
	// [supported] Book Listing
	Route::get('library/book',      'BooksController@show')->name('v2_library_book');
	// [supported] Book Name Listing
	Route::get('library/bookname', 'BooksController@bookNames')->name('v2_library_bookname');
	// TODO: Book Order CRUD
	// [supported] Chapter Listing
	Route::get('library/chapter',  'BooksController@chapters')->name('v2_library_chapter');
	// [] Verse Info Listing
	Route::get('library/verseinfo', 'VerseController@info')->name('v2_library_verseInfo');
	// [] Numbers Listing
	Route::get('library/numbers', 'NumbersController@index');
	// TODO: Numbers CRUD

	// Metadata
	Route::get('library/metadata', 'BiblesController@show')->name('v2_library_metadata');
	// [] Metadata Listing
	// [] Metadata Create
	// [] Metadata Update

	// Library Audio
	// [static] Location
	// [supported] Path
	// [supported] Verse Audio Timecodes List
	Route::get('audio/location', function () {return json_decode(file_get_contents(public_path('static/library_audio_location.json')));});
	Route::get('audio/path', 'AudioController@index')->name('v2_audio_path');
	Route::get('audio/versestart', 'AudioController@timestamps')->name('v2_audio_timestamps');