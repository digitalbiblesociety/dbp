<?php

		// VERSION 2

		// Library
		Route::get('library/asset',                'HomeController@libraryAsset')->name('v2_library_asset');
		Route::get('library/version',              'BiblesController@libraryVersion')->name('v2_library_version');
		Route::get('library/book',                 'BooksController@show')->name('v2_library_book');
		Route::get('library/bookname',             'BooksController@bookNames')->name('v2_library_bookName');
		Route::get('library/bookorder',            'BooksController@show')->name('v2_library_bookOrder');
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
		Route::get('library/organization',         'OrganizationsController@index');
		Route::get('audio/location',               'AudioController@location')->name('v2_audio_location');
		Route::get('audio/path',                   'AudioController@index')->name('v2_audio_path');
		Route::get('audio/versestart',             'AudioController@timestampsByReference')->name('v2_audio_timestamps');
		Route::get('text/font',                    'TextController@fonts')->name('v2_text_font');
		Route::get('text/verse',                   'TextController@index')->name('v2_text_verse');
		Route::get('text/search',                  'TextController@search')->name('v2_text_search');
		Route::get('text/searchgroup',             'TextController@searchGroup')->name('v2_text_search_group');
		Route::get('video/location',               'FilmsController@location')->name('v2_video_location');
		Route::get('video/path',                   'FilmsController@videoPath')->name('v2_video_video_path');
		Route::get('country/countrylang',          'LanguagesController@CountryLang')->name('v2_country_lang');
		Route::get('api/apiversion',              'HomeController@versionLatest')->name('v2_api_versionLatest');
		Route::get('api/reply',                   'HomeController@versionReplyTypes')->name('v2_api_apiReply');

	// VERSION 4

		// Bibles
		Route::name('v4_bible.all')->get('bibles',                                            'BiblesController@index');
		Route::name('v4_bible.one')->get('bibles/{id}',                                       'BiblesController@show');
		Route::name('v4_bible.equivalents')->get('bible/{id}/equivalents',                    'BiblesController@equivalents');
		Route::name('v4_bible.books_all')->get('bible/{id}/book/{book}',                      'BiblesController@books');
		Route::name('v4_bible.books_one')->get('bible/{id}/book/{book}',                      'BiblesController@book');
		Route::name('v4_bible.read')->get('bible/{id}/{book}/{chapter}',                      'TextController@text');
		Route::name('v4_bible_books.all')->get('bibles/books/',                               'BooksController@index');
		Route::name('v4_bible_books.one')->get('bibles/books/{id}',                           'BooksController@show');
		Route::name('v4_bible_filesets.all')->get('bibles',                                   'BibleFileSetsController@index');
		Route::name('v4_bible_filesets.one')->get('bibles/{id}',                              'BibleFileSetsController@show');
		Route::name('v4_bible_filesets.permissions')->get('bibles/filesets/{id}/permissions', 'BibleFileSetPermissionsController@index');
		Route::name('v4_bibleFiles.one')->get('bibles/files/{ id }',                          'BibleFilesController@show');
		Route::name('v4_timestamps')->get('timestamps',                                       'AudioController@availableTimestamps');
		Route::name('v4_timestamps.tag')->get('timestamps/{id}',                              'AudioController@timestampsByTag');
		Route::name('v4_timestamps.verse')->get('timestamps/{id}/{book}/{chapter}',           'AudioController@timestampsByReference');
		Route::name('v4_countries.all')->get('countries',                                     'CountriesController@index');
		Route::name('v4_countries.one')->get('countries/{id}',                                'CountriesController@show');
		Route::name('v4_languages.all')->get('languages',                                     'LanguagesController@index');
		Route::name('v4_languages.one')->get('languages/{id}',                                'LanguagesController@show');
		Route::name('v4_alphabets.all')->get('alphabets',                                     'AlphabetsController@index');
		Route::name('v4_alphabets.one')->get('alphabets/{id}',                                'AlphabetsController@show');
		Route::name('v4_numbers.range')->get('numbers/range',                                 'NumbersController@customRange');
		Route::name('v4_numbers.all')->get('numbers/',                                        'NumbersController@index');
		Route::name('v4_numbers.one')->get('numbers/{id}',                                    'NumbersController@show');
		Route::name('v4_organizations.all')->get('organizations/',                            'OrganizationsController@index');
		Route::name('v4_organizations.one')->get('organizations/{id}',                        'OrganizationsController@show');
		Route::name('v4_users.all')->get('organizations/',                                    'UsersController@index');
		Route::name('v4_users.one')->get('organizations/{id}',                                'UsersController@show');
		Route::name('v4_api.versions')->get('/api/versions',                                  'HomeController@versions');
		Route::name('v4_api.versionLatest')->get('/api/versions/latest',                      'HomeController@versionLatest');
		Route::name('v4_api.replyTypes')->get('/api/versions/replyTypes',                     'HomeController@versionReplyTypes');
		Route::name('v4_api.sign')->get('sign',                                               'HomeController@signedUrls');
