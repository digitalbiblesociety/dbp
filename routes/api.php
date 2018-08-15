<?php

	// VERSION 2
	Route::name('v2_pass_through')->get('pass-through/{path1?}/{path2?}',                 'HomeController@passThrough');
	Route::name('v2_library_asset')->get('library/asset',                                 'HomeController@libraryAsset');
	Route::name('v2_library_version')->get('library/version',                             'Bible\BiblesController@libraryVersion');
	Route::name('v2_library_book')->get('library/book',                                   'Bible\BooksController@book');
	Route::name('v2_library_bookOrder')->get('library/bookorder',                         'Bible\BooksController@bookOrder');
	Route::name('v2_library_bookName')->get('library/bookname',                           'Bible\BooksController@bookNames');
	Route::name('v2_library_chapter')->get('library/chapter',                             'Bible\BooksController@chapters');

	Route::name('v2_library_language')->get('library/language',                           'Wiki\LanguagesController@languageListing');
	Route::name('v2_volume_history')->get('library/volumehistory',                        'Connections\V2Controllers\LibraryCatalog\LibraryVolumeController@history');
	Route::name('v2_library_volumeLanguage')->get('library/volumelanguage',               'Wiki\LanguagesController@volumeLanguage');
	Route::name('v2_library_volumeLanguageFamily')->get('library/volumelanguagefamily',   'Wiki\LanguagesController@volumeLanguageFamily');
	Route::name('v2_country_lang')->get('country/countrylang',                            'Wiki\LanguagesController@CountryLang');

	Route::name('v2_library_verseInfo')->get('library/verseinfo',                         'Bible\VerseController@info');
	Route::name('v2_library_numbers')->get('library/numbers',                             'Wiki\NumbersController@customRange');
	Route::name('v2_library_metadata')->get('library/metadata',                           'Connections\V2Controllers\LibraryCatalog\LibraryMetadataController@index');
	Route::name('v2_library_volume')->get('library/volume',                               'Connections\V2Controllers\LibraryCatalog\LibraryVolumeController@libraryVolume');

	Route::name('v2_volume_organization_list')->get('library/volumeorganization',         'Organization\OrganizationsController@index');

	// TODO: Cache below Routes
	Route::name('v2_library_organization')->get('library/organization',                   'Organization\OrganizationsController@index');
	Route::name('v2_audio_location')->get('audio/location',                               'Bible\AudioController@location');
	Route::name('v2_audio_path')->get('audio/path',                                       'Bible\AudioController@index');
	Route::name('v2_audio_timestamps')->get('audio/versestart',                           'Bible\AudioController@timestampsByReference');
	Route::name('v2_text_font')->get('text/font',                                         'Bible\TextController@fonts');
	Route::name('v2_text_verse')->get('text/verse',                                       'Bible\TextController@index');
	Route::name('v2_text_search')->get('text/search',                                     'Bible\TextController@search');
	Route::name('v2_text_search_group')->get('text/searchgroup',                          'Bible\TextController@searchGroup');
	Route::name('v2_video_location')->get('video/location',                               'Organization\FilmsController@location');
	Route::name('v2_video_path')->get('video/path',                                       'Organization\FilmsController@videoPath');
	Route::name('v2_api_versionLatest')->get('api/apiversion',                            'HomeController@versionLatest');
	Route::name('v2_api_apiReply')->get('api/reply',                                      'HomeController@versionReplyTypes');
	Route::name('v2_api_jesusFilms')->get('library/jesusfilm',                            'Organization\ResourcesController@jesusFilmListing');

	// VERSION 3
	// What can man do against such reckless hate
	Route::prefix('v3')->group(function () {
		Route::name('v3_query')->get('search',                                           'Connections\V3Controller@search');
		Route::name('v3_books')->get('books',                                            'Connections\V3Controller@books');
	});

	// VERSION 4

	// VERSION 4 | BIBLE
	Route::name('v4_access_groups.index')->get('access/groups',                          'User\AccessGroupController@index');
	Route::name('v4_access_groups.store')->post('access/groups/',                        'User\AccessGroupController@store');
	Route::name('v4_access_groups.show')->get('access/groups/{group_id}',                'User\AccessGroupController@show');
	Route::name('v4_access_groups.update')->put('access/groups/{group_id}',              'User\AccessGroupController@update');
	Route::name('v4_access_groups.destroy')->delete('access/groups/{group_id}',          'User\AccessGroupController@destroy');

	Route::name('v4_bible_filesets.types')->get('bibles/filesets/media/types',                      'Bible\BibleFileSetsController@mediaTypes');
	Route::name('v4_bible_filesets.podcast')->get('bibles/filesets/{fileset_id}/podcast',           'Bible\BibleFileSetsController@podcast');
	Route::name('v4_bible_filesets.download')->get('bibles/filesets/{fileset_id}/download',         'Bible\BibleFileSetsController@download');
	Route::name('v4_bible_filesets.copyright')->get('bibles/filesets/{fileset_id}/copyright',       'Bible\BibleFileSetsController@copyright');
	Route::name('v4_bible_filesets.chapter')->get('bibles/filesets/{fileset_id}/{book}/{chapter}',  'Bible\TextController@index');
	Route::name('v4_bible_filesets.show')->get('bibles/filesets/{fileset_id?}',                     'Bible\BibleFileSetsController@show');
	Route::name('v4_bible_filesets.update')->put('bibles/filesets/{fileset_id}',                    'Bible\BibleFileSetsController@update');
	Route::name('v4_bible_filesets.store')->post('bibles/filesets/',                                'Bible\BibleFileSetsController@store');
	Route::name('v4_bible.links')->get('bibles/links',                                              'Bible\BibleLinksController@index');
	Route::name('v4_bible.allBooks')->get('bibles/books/',                                          'Bible\BooksController@index');
	Route::name('v4_text_search')->get('search',                                                    'Bible\TextController@search');
	Route::name('v4_bible.books')->get('bibles/{bible_id}/book/{book?}',                            'Bible\BiblesController@books');
	Route::name('v4_bible.archival')->get('bibles/archival',                                        'Bible\BiblesController@archival');
	Route::name('v4_bible.one')->get('bibles/{bible_id}',                                           'Bible\BiblesController@show');
	Route::name('v4_bible.all')->get('bibles',                                                      'Bible\BiblesController@index');
	Route::name('v4_timestamps')->get('timestamps',                                                 'Bible\AudioController@availableTimestamps');
	Route::name('v4_timestamps.tag')->get('timestamps/{fileset_id}/{query}',                        'Bible\AudioController@timestampsByTag');
	Route::name('v4_timestamps.verse')->get('timestamps/{fileset_id}/{book}/{chapter}',             'Bible\AudioController@timestampsByReference');

	// VERSION 4 | WIKI
	Route::name('v4_countries.all')->get('countries',                                     'Wiki\CountriesController@index');
	Route::name('v4_countries.jsp')->get('countries/joshua-project/',                     'Wiki\CountriesController@joshuaProjectIndex');
	Route::name('v4_countries.one')->get('countries/{country_id}',                        'Wiki\CountriesController@show');
	Route::name('v4_languages.all')->get('languages',                                     'Wiki\LanguagesController@index');
	Route::name('v4_languages.one')->get('languages/{language_id}',                       'Wiki\LanguagesController@show');
	Route::name('v4_alphabets.all')->get('alphabets',                                     'Wiki\AlphabetsController@index');
	Route::name('v4_alphabets.one')->get('alphabets/{alphabet_id}',                       'Wiki\AlphabetsController@show');
	Route::name('v4_alphabets.store')->post('alphabets',                                  'Wiki\AlphabetsController@store');
	Route::name('v4_alphabets.update')->put('alphabets/{alphabet_id}',                    'Wiki\AlphabetsController@update');
	Route::name('v4_numbers.range')->get('numbers/range',                                 'Wiki\NumbersController@customRange');
	Route::name('v4_numbers.all')->get('numbers/',                                        'Wiki\NumbersController@index');
	Route::name('v4_numbers.one')->get('numbers/{number_id}',                             'Wiki\NumbersController@show');

	// VERSION 4 | USERS
	Route::name('v4_user.index')->get('users',                                            'User\UsersController@index');
	Route::name('v4_user.store')->post('users',                                           'User\UsersController@store');
	Route::name('v4_user.show')->get('users/{user_id}',                                   'User\UsersController@show');
	Route::name('v4_user.update')->put('users/{user_id}',                                 'User\UsersController@update');
	Route::name('v4_user.destroy')->delete('users/{user_id}',                             'User\UsersController@destroy');
	Route::name('v4_user.login')->post('users/login',                                     'User\UsersController@login');
	Route::name('v4_user.geolocate')->get('users/geolocate',                              'User\UsersController@geoLocate');
	Route::name('v4_user.oAuth')->get('users/login/{driver}',                             'Auth\LoginController@redirectToProvider');
	Route::name('v4_user.oAuthCallback')->get('users/login/{driver}/callback',            'Auth\LoginController@handleProviderCallback');

	// VERSION 4 | USER PASSWORDS
	Route::name('v4_user.password_reset')->post('users/password/reset',                   'User\UserPasswordsController@validatePasswordReset');
	Route::name('v4_user.password_email')->post('users/password/email',                   'User\UserPasswordsController@triggerPasswordResetEmail');

	// VERSION 4 | USER ACCOUNTS
	Route::name('v4_user_accounts.index')->get('/accounts',                               'User\UserAccountsController@index');
	Route::name('v4_user_accounts.show')->get('/accounts/{account_id}',                   'User\UserAccountsController@show');
	Route::name('v4_user_accounts.store')->post('/accounts',                              'User\UserAccountsController@store');
	Route::name('v4_user_accounts.update')->put('/accounts/{account_id}',                 'User\UserAccountsController@update');
	Route::name('v4_user_accounts.destroy')->delete('/accounts/{account_id}',             'User\UserAccountsController@destroy');

	// VERSION 4 | USER NOTES
	Route::name('v4_notes.index')->get('users/{user_id}/notes',                           'User\UserNotesController@index');
	Route::name('v4_notes.show')->get('users/{user_id}/notes/{note_id}',                  'User\UserNotesController@show');
	Route::name('v4_notes.store')->post('users/{user_id}/notes',                          'User\UserNotesController@store');
	Route::name('v4_notes.update')->put('users/{user_id}/notes/{note_id}',                'User\UserNotesController@update');
	Route::name('v4_notes.destroy')->delete('users/{user_id}/notes/{note_id}',            'User\UserNotesController@destroy');

	// VERSION 4 | USER BOOKMARKS
	Route::name('v4_bookmarks.index')->get('users/{user_id}/bookmarks',                     'User\UserNotesController@index');
	Route::name('v4_bookmarks.show')->get('users/{user_id}/bookmarks/{bookmark_id}',        'User\UserNotesController@show');
	Route::name('v4_bookmarks.store')->post('users/{user_id}/bookmarks',                    'User\UserNotesController@store');
	Route::name('v4_bookmarks.update')->put('users/{user_id}/bookmarks/{bookmark_id}',      'User\UserNotesController@update');
	Route::name('v4_bookmarks.destroy')->delete('users/{user_id}/bookmarks/{bookmark_id}',  'User\UserNotesController@destroy');

	// VERSION 4 | USER HIGHLIGHTS

	Route::name('v4_highlights.index')->get('users/{user_id}/highlights',                      'User\UserHighlightsController@index');
	Route::name('v4_highlights.store')->post('users/{user_id}/highlights',                     'User\UserHighlightsController@store');
	Route::name('v4_highlights.update')->put('users/{user_id}/highlights/{highlight_id}',      'User\UserHighlightsController@update');
	Route::name('v4_highlights.destroy')->delete('users/{user_id}/highlights/{highlight_id}',  'User\UserHighlightsController@destroy');

	Route::name('v4_organizations.all')->get('organizations/',                            'Organization\OrganizationsController@index');
	Route::name('v4_organizations.one')->get('organizations/{organization_id}',           'Organization\OrganizationsController@show');
	Route::name('v4_api.versions')->get('/api/versions',                                  'HomeController@versions');
	Route::name('v4_api.versionLatest')->get('/api/versions/latest',                      'HomeController@versionLatest');
	Route::name('v4_api.replyTypes')->get('/api/versions/replyTypes',                     'HomeController@versionReplyTypes');
	Route::name('v4_api.sign')->get('sign',                                               'HomeController@signedUrls');

	// VERSION 4 | PROJECTS
	Route::name('v4_projects.index')->get('projects',                                      'Organization\ProjectsController@index');
	Route::name('v4_projects.show')->get('projects/{project_id}',                          'Organization\ProjectsController@show');
	Route::name('v4_projects.update')->put('projects/{project_id}',                        'Organization\ProjectsController@update');
	Route::name('v4_projects.store')->post('projects',                                     'Organization\ProjectsController@store');
	Route::name('v4_projects.destroy')->delete('projects/{project_id}',                    'Organization\ProjectsController@destroy');

	Route::name('v4_projects_oAuthProvider.index')->get('projects/{project_id}/oauth-providers/',          'Organization\ProjectOAuthProvidersController@index');
	Route::name('v4_projects_oAuthProvider.show')->get('projects/{project_id}/oauth-providers/{id}',       'Organization\ProjectOAuthProvidersController@show');
	Route::name('v4_projects_oAuthProvider.update')->put('projects/{project_id}/oauth-providers/{id}',     'Organization\ProjectOAuthProvidersController@update');
	Route::name('v4_projects_oAuthProvider.store')->post('projects/{project_id}/oauth-providers',          'Organization\ProjectOAuthProvidersController@store');
	Route::name('v4_projects_oAuthProvider.destroy')->delete('projects/{project_id}/oauth-providers/{id}', 'Organization\ProjectOAuthProvidersController@destroy');

	// VERSION 4 | UTILITY
	Route::name('v4_api.buckets')->get('/api/buckets',                                    'HomeController@buckets');
	Route::name('v4_api.stats')->get('/stats',                                            'HomeController@stats');
	Route::name('v4_api.logs')->get('sign',                                               'HomeController@signedUrls');

	// VERSION 4 | CONNECTIONS
	Route::name('v4_connections_jfm.sync')->get('connections/jesus-film/sync',           'Connections\ArclightController@sync');
	Route::name('v4_connections_jfm.index')->get('connections/jesus-film/{iso}',         'Connections\ArclightController@index');
	Route::name('v4_connections_app.deeplink')->get('connections/app/deeplinking',       'Connections\MobileAppsController@redirectDeepLink');
	Route::name('v4_connections_grn.sync')->get('connections/grn/sync',                  'Connections\GRNController@sync');
	Route::name('v4_connections_grn.index')->get('connections/grn/{iso}',                'Connections\GRNController@index');

	// VERSION 4 | Resources
	Route::name('v4_resources.index')->get('resources',                                  'Organization\ResourcesController@index');
	Route::name('v4_resources.show')->get('resources/{resource_id}',                     'Organization\ResourcesController@show');
	Route::name('v4_resources.update')->put('resources/{resource_id}',                   'Organization\ResourcesController@update');
	Route::name('v4_resources.store')->post('resources',                                 'Organization\ResourcesController@store');
	Route::name('v4_resources.destroy')->delete('resources/{resource_id}',               'Organization\ResourcesController@destroy');

	// VERSION 4 | ARTICLES
	Route::name('v4_articles.index')->get('articles',                                      'User\ArticlesController@index');
	Route::name('v4_articles.show')->get('articles/{article_id}',                          'User\ArticlesController@show');
	Route::name('v4_articles.update')->put('articles/{article_id}',                        'User\ArticlesController@update');
	Route::name('v4_articles.store')->post('articles',                                     'User\ArticlesController@store');
	Route::name('v4_articles.destroy')->delete('articles/{article_id}',                    'User\ArticlesController@destroy');
