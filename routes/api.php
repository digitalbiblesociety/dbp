<?php

use Illuminate\Http\Request;


// API Versions
Route::get('/',                             'HomeController@versions');
Route::get('/api/apiversion',               'HomeController@versions');

Route::get('bible/LanguageNames',           'BiblesController@languageNames');
Route::get('bible/{id}/{book}/{chapter}',   'BiblesController@text');
Route::get('bible/{abbr}/book/{book}',      'BiblesController@book');

Route::get('bible/{abbr}/equivalents',      'BiblesController@equivalents')->name('api_bibles.equivalents');

Route::resource('bible/films',              'BibleFilmsController',['names' => [
	'index'   => 'v4_bible_films.index',
	'edit'    => 'v4_bible_films.edit',
	'create'  => 'v4_bible_films.create',
	'show'    => 'v4_bible_films.show',
]]);

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
Route::resource('books',      'BooksController',['names' => [
	'index'   => 'v4_api_books.index',
	'edit'    => 'v4_api_books.edit',
	'create'  => 'v4_api_books.create',
	'show'    => 'v4_api_books.show',
]]);
Route::get('bible/{abbr}/books',            'BiblesController@books')->name('api_v4_books');
Route::get('sign', 'HomeController@signedUrl');

	// VERSION 2

		// Library
		Route::get('library/asset', function () {return json_decode(file_get_contents(public_path('static/library_asset.json')));})->name('v2_library_asset');
		Route::get('library/bookorder', 'BooksController@show')->name('v2_library_bookorder');
		Route::get('library/book',      'BooksController@show')->name('v2_library_book');
		Route::get('library/bookname',  'BooksController@bookNames')->name('v2_library_bookname');
		Route::get('library/chapter',   'BooksController@chapters')->name('v2_library_chapter');
		Route::get('library/language', 'LanguagesController@index')->name('v2_library_language');
		Route::get('library/verseinfo', 'VerseController@info')->name('v2_library_verseInfo');
		Route::get('library/numbers',   'NumbersController@index')->name('v2_library_numbers');
		Route::get('library/metadata', 'BiblesController@show')->name('v2_library_metadata');
		Route::get('library/volume', 'BiblesController@index')->name('v2_library_volume');
		Route::get('library/volumelanguage', 'LanguagesController@volumeLanguage')->name('v2_library_volumeLanguage');
		Route::get('library/volumelanguagefamily', 'LanguagesController@volumeLanguageFamily')->name('v2_library_volumeLanguageFamily');
		Route::get('library/volumeorganization', 'OrganizationsController@index')->name('v2_volume_organization_list');
		Route::get('library/volumehistory', 'BiblesController@history')->name('v2_volume_history');
		Route::get('library/version', function () {return json_decode(file_get_contents(public_path('static/version_listing.json')));});

		// Audio
		Route::get('audio/location', function () {return json_decode(file_get_contents(public_path('static/library_audio_location.json')));});
		Route::get('audio/path', 'AudioController@index')->name('v2_audio_path');
		Route::get('audio/versestart', 'AudioController@timestamps')->name('v2_audio_timestamps');

		// Text
		Route::get('text/font',         'TextController@fonts')->name('v2_text_font');
		Route::get('text/verse',        'TextController@index')->name('v2_text_verse');
		Route::get('text/search',       'TextController@search')->name('v2_text_search');
		Route::get('text/searchgroup',  'TextController@searchGroup')->name('v2_text_search_group');

		// Video
		Route::get('video/location',    'FilmsController@location')->name('v2_video_location');
		Route::get('video/path',        'FilmsController@videoPath')->name('v2_video_video_path');

		// Country/Language
		Route::get('country/countrylang', 'LanguagesController@CountryLang')->name('v2_country_lang');

		// API INFO
		Route::get('/api/apiversion', 'HomeController@versions')->name('v2_api_apiversion');