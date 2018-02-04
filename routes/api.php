<?php

	// VERSION 2
	Route::name('v2_library_asset')->get('library/asset',                                 'HomeController@libraryAsset');
	Route::name('v2_library_version')->get('library/version',                             'BiblesController@libraryVersion');
	Route::name('v2_library_book')->get('library/book',                                   'BooksController@show');
	Route::name('v2_library_bookName')->get('library/bookname',                           'BooksController@bookNames');
	Route::name('v2_library_bookOrder')->get('library/bookorder',                         'BooksController@show');
	Route::name('v2_library_chapter')->get('library/chapter',                             'BooksController@chapters');
	Route::name('v2_library_language')->get('library/language',                           'LanguagesController@index');
	Route::name('v2_library_verseInfo')->get('library/verseinfo',                         'VerseController@info');
	Route::name('v2_library_numbers')->get('library/numbers',                             'NumbersController@customRange');
	Route::name('v2_library_metadata')->get('library/metadata',                           'BiblesController@libraryMetadata');
	Route::name('v2_library_volume')->get('library/volume',                               'BiblesController@index');
	Route::name('v2_library_volumeLanguage')->get('library/volumelanguage',               'LanguagesController@volumeLanguage');
	Route::name('v2_library_volumeLanguageFamily')->get('library/volumelanguagefamily',   'LanguagesController@volumeLanguageFamily');
	Route::name('v2_volume_organization_list')->get('library/volumeorganization',         'OrganizationsController@index');
	Route::name('v2_volume_history')->get('library/volumehistory',                        'BiblesController@history');
	Route::name('v2_library_organization')->get('library/organization',                   'OrganizationsController@index');
	Route::name('v2_audio_location')->get('audio/location',                               'AudioController@location');
	Route::name('v2_audio_path')->get('audio/path',                                       'AudioController@index');
	Route::name('v2_audio_timestamps')->get('audio/versestart',                           'AudioController@timestampsByReference');
	Route::name('v2_text_font')->get('text/font',                                         'TextController@fonts');
	Route::name('v2_text_verse')->get('text/verse',                                       'TextController@index');
	Route::name('v2_text_search')->get('text/search',                                     'TextController@search');
	Route::name('v2_text_search_group')->get('text/searchgroup',                          'TextController@searchGroup');
	Route::name('v2_video_location')->get('video/location',                               'FilmsController@location');
	Route::name('v2_video_video_path')->get('video/path',                                 'FilmsController@videoPath');
	Route::name('v2_country_lang')->get('country/countrylang',                            'LanguagesController@CountryLang');
	Route::name('v2_api_versionLatest')->get('api/apiversion',                            'HomeController@versionLatest');
	Route::name('v2_api_apiReply')->get('api/reply',                                      'HomeController@versionReplyTypes');

	// VERSION 3
	// What can man do against such reckless hate
	Route::prefix('v3')->group(function () {
		Route::name('v3_query')->get('search',                                           'V3Controller@search');
		Route::name('v3_books')->get('books',                                            'V3Controller@books');
	});

	// VERSION 4

	// VERSION 4 | BIBLE
	Route::name('v4_bible_filesets.index')->get('bibles/filesets/{ id }',                 'BibleFilesSetsController@show');
	Route::post('bibles/filesets/{id}/files/{file_id}',    'BibleFilesController@update');

	Route::resource('bibles/filesets/{id}/permissions',    'BibleFileSetPermissionsController', ['names' => [
		'v4_bible_filesets.permissions_index'   => 'view_bible_filesets_permissions.index',
		'v4_bible_filesets.permissions_edit'    => 'view_bible_filesets_permissions.edit',
		'v4_bible_filesets.permissions_create'  => 'view_bible_filesets_permissions.create',
		'v4_bible_filesets.permissions_store'   => 'view_bible_filesets_permissions.store',
		'v4_bible_filesets.permissions_show'    => 'view_bible_filesets_permissions.show',
		'v4_bible_filesets.permissions_update'  => 'view_bible_filesets_permissions.update'
	]]);
	Route::get('bibles/filesets/{id}/download', 'BibleFileSetsController@download')->name('v4_bible_filesets.download');
	Route::resource('bibles/filesets',           'BibleFileSetsController', ['names' => [
		'v4_bible_filesets.index'   => 'view_bible_filesets.index',
		'v4_bible_filesets.edit'    => 'view_bible_filesets.edit',
		'v4_bible_filesets.create'  => 'view_bible_filesets.create',
		'v4_bible_filesets.show'    => 'view_bible_filesets.show',
	]]);

	Route::name('v4_bible.all')->get('bibles',                                            'BiblesController@index');
	Route::name('v4_bible.one')->get('bibles/{id}',                                       'BiblesController@show');
	Route::name('v4_bible.books')->get('bible/{id}/book/{book}',                          'BiblesController@books');
	Route::name('v4_bible.chapter')->get('bible/{id}/{book}/{chapter}',                   'TextController@index');
	Route::name('v4_bible_books')->get('bibles/books/',                                   'BooksController@index');
	Route::name('v4_timestamps')->get('timestamps',                                       'AudioController@availableTimestamps');
	Route::name('v4_timestamps.tag')->get('timestamps/{id}',                              'AudioController@timestampsByTag');
	Route::name('v4_timestamps.verse')->get('timestamps/{id}/{book}/{chapter}',           'AudioController@timestampsByReference');

	// VERSION 4 | WIKI
	Route::name('v4_countries.all')->get('countries',                                     'CountriesController@index');
	Route::name('v4_countries.one')->get('countries/{id}',                                'CountriesController@show');
	Route::name('v4_languages.all')->get('languages',                                     'LanguagesController@index');
	Route::name('v4_languages.one')->get('languages/{id}',                                'LanguagesController@show');
	Route::name('v4_alphabets.all')->get('alphabets',                                     'AlphabetsController@index');
	Route::name('v4_alphabets.one')->get('alphabets/{id}',                                'AlphabetsController@show');
	Route::name('v4_numbers.range')->get('numbers/range',                                 'NumbersController@customRange');
	Route::name('v4_numbers.all')->get('numbers/',                                        'NumbersController@index');
	Route::name('v4_numbers.one')->get('numbers/{id}',                                    'NumbersController@show');

	// VERSION 4 | USERS
	Route::name('v4_user.index')->get('users',                                            'UsersController@index');
	Route::name('v4_user.create')->post('users',                                          'UsersController@store');

	Route::name('v4_user.login')->post('users/login',                                     'UsersController@login');
	Route::name('v4_user.oAuth')->get('users/login/{driver}',                             'Auth\LoginController@redirectToProvider');
	Route::name('v4_user.oAuth')->get('users/login/{driver}/callback',                    'Auth\LoginController@handleProviderCallback');
	Route::name('v4_notes.index')->get('users/{user_id}/notes',                           'UserNotesController@index');
	Route::name('v4_notes.store')->post('users/{user_id}/notes',                          'UserNotesController@store');
	Route::name('v4_notes.update')->put('users/{user_id}/notes',                          'UserNotesController@update');
	Route::name('v4_user.destroy')->delete('users/{user_id}/notes/{note_id}',             'UserNotesController@destroy');
	Route::name('v4_organizations.all')->get('organizations/',                            'OrganizationsController@index');
	Route::name('v4_organizations.one')->get('organizations/{id}',                        'OrganizationsController@show');
	Route::name('v4_api.versions')->get('/api/versions',                                  'HomeController@versions');
	Route::name('v4_api.versionLatest')->get('/api/versions/latest',                      'HomeController@versionLatest');
	Route::name('v4_api.replyTypes')->get('/api/versions/replyTypes',                     'HomeController@versionReplyTypes');
	Route::name('v4_api.sign')->get('sign',                                               'HomeController@signedUrls');


	// Error Handling
	// Route::name('v4_api.sign')->get('sign',                                               'HomeController@signedUrls');