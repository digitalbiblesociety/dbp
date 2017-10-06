<?php

use Illuminate\Http\Request;

		// VERSION 2

		// Library
		Route::get('library/asset',                'HomeController@libraryAsset')->name('v2_library_asset');
		Route::get('library/version',              'BiblesController@libraryVersion')->name('v2_library_version');
		Route::get('library/bookorder',            'BooksController@show')->name('v2_library_bookOrder');
		Route::get('library/book',                 'BooksController@show')->name('v2_library_book');
		Route::get('library/bookname',             'BooksController@bookNames')->name('v2_library_bookName');
		Route::get('library/chapter',              'BooksController@chapters')->name('v2_library_chapter');
		Route::get('library/language',             'LanguagesController@index')->name('v2_library_language');
		Route::get('library/verseinfo',            'VerseController@info')->name('v2_library_verseInfo');
		Route::get('library/numbers',              'NumbersController@customRange')->name('v2_library_numbers');
		Route::get('library/metadata',             'BiblesController@libraryMetadata')->name('v2_library_metadata');
		Route::get('library/volume',               'BiblesController@index')->name('v2_library_volume');
		Route::get('library/volumelanguage',       'LanguagesController@volumeLanguage')->name('v2_library_volumeLanguage');
		Route::get('library/volumelanguagefamily', 'LanguagesController@volumeLanguageFamily')->name('v2_library_volumeLanguageFamily');
		Route::get('library/volumeorganization',   'OrganizationsController@index')->name('v2_volume_organization_list');
		Route::get('library/volumehistory',        'BiblesController@history')->name('v2_volume_history');
		Route::get('library/organization',          'OrganizationsController@index');

		// Audio
		Route::get('audio/location',               'AudioController@location')->name('v2_audio_location');
		Route::get('audio/path',                   'AudioController@index')->name('v2_audio_path');
		Route::get('audio/versestart',             'AudioController@timestamps')->name('v2_audio_timestamps');

		// Text
		Route::get('text/font',                    'TextController@fonts')->name('v2_text_font');
		Route::get('text/verse',                   'TextController@index')->name('v2_text_verse');
		Route::get('text/search',                  'TextController@search')->name('v2_text_search');
		Route::get('text/searchgroup',             'TextController@searchGroup')->name('v2_text_search_group');

		// Video
		Route::get('video/location',               'FilmsController@location')->name('v2_video_location');
		Route::get('video/path',                   'FilmsController@videoPath')->name('v2_video_video_path');

		// Country/Language
		Route::get('country/countrylang',          'LanguagesController@CountryLang')->name('v2_country_lang');

		// API INFO
		Route::get('/api/apiversion',              'HomeController@versionLatest')->name('v2_api_versionLatest');
		Route::get('/api/reply',                   'HomeController@versionReplyFormats')->name('v2_api_apiReply');

	// VERSION 4

		// Bibles
		Route::get('bible/LanguageNames',           'BiblesController@languageNames');
		Route::get('bible/{abbr}/equivalents',      'BiblesController@equivalents')->name('api_bibles.equivalents');
		Route::get('bible/{id}/{book}/{chapter}',   'BiblesController@text');
		Route::get('bible/{abbr}/book/{book}',      'BiblesController@book');
		Route::get('bible/{abbr}/books',            'BiblesController@books')->name('api_v4_books');
		Route::resource('bible/films',              'BibleFilmsController',['names' => [
			'index'   => 'v4_bible_films.index',
			'update'  => 'v4_bible_films.update',
			'store'   => 'v4_bible_films.store',
			'show'    => 'v4_bible_films.show',
		]]);
		Route::resource('/bibles/books',      'BooksController',['names' => [
			'index'   => 'v4_api_books.index',
			'update'  => 'v4_api_books.update',
			'store'   => 'v4_api_books.store',
			'show'    => 'v4_api_books.show',
		]]);
		Route::resource('bibles',                   'BiblesController',['names' => [
			'index'   => 'api_bibles.index',
			'update'  => 'api_bibles.update',
			'store'   => 'api_bibles.store',
			'show'    => 'api_bibles.show',
		]]);

		// Country
		Route::resource('countries',                'CountriesController', ['names' => [
			'index'   => 'api_countries.index',
			'update'  => 'api_countries.update',
			'store'   => 'api_countries.store',
			'show'    => 'api_countries.show',
		]]);

		// Languages
		Route::resource('languages',                'LanguagesController',['names' => [
			'index'   => 'api_languages.index',
			'update'  => 'api_languages.update',
			'store'   => 'api_languages.store',
			'show'    => 'api_languages.show',
		]]);
		Route::resource('alphabets',                'AlphabetsController',['names' => [
			'index'   => 'api_alphabets.index',
			'update'  => 'api_alphabets.update',
			'store'   => 'api_alphabets.store',
			'show'    => 'api_alphabets.show',
		]]);
		Route::get('numbers/range',               'NumbersController@customRange');
		Route::resource('numbers',                'NumbersController',['names' => [
			'index'   => 'api_languages.index',
			'update'  => 'api_languages.update',
			'store'   => 'api_languages.store',
			'show'    => 'api_languages.show',
		]]);

		// Community
		Route::resource('/organizations',            'OrganizationsController',['names' => [
			'index'   => 'api_organizations.index',
			'update'  => 'api_organizations.update',
			'store'   => 'api_organizations.store',
			'show'    => 'api_organizations.show',
		]]);
		Route::resource('/users',              'UsersController',['names' => [
			'index'   => 'api_users.index',
			'update'  => 'api_users.update',
			'store'   => 'api_users.store',
			'show'    => 'api_users.show',
		]]);

		// API INFO
		Route::get('sign', 'HomeController@signedUrl');
		Route::get('/api/versions',                'HomeController@versions')->name('v4_api_versions');
		Route::get('/api/versions/latest',         'HomeController@versionLatest')->name('v4_api_versionLatest');
		Route::get('/api/versions/replyFormats',   'HomeController@versionReplyFormats')->name('v4_api_replyFormats');