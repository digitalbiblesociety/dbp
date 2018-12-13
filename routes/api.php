<?php

    // VERSION 2
    Route::name('v2_pass_through')->get('pass-through/{path1?}/{path2?}', 'Connections\V2Controllers\ApiMetadataController@passThrough');
    Route::name('v2_library_asset')->get('library/asset', 'Connections\V2Controllers\ApiMetadataController@libraryAsset');
    Route::name('v2_api_versionLatest')->get('api/apiversion', 'Connections\V2Controllers\ApiMetadataController@versionLatest');
    Route::name('v2_api_apiReply')->get('api/reply', 'Connections\V2Controllers\ApiMetadataController@versionReplyTypes');
    Route::name('v2_library_version')->get('library/version', 'Connections\V2Controllers\LibraryCatalog\LibraryVersionController@libraryVersion');
    Route::name('v2_library_book')->get('library/book', 'Connections\V2Controllers\LibraryCatalog\BooksController@book');
    Route::name('v2_library_bookOrder')->get('library/bookorder', 'Connections\V2Controllers\LibraryCatalog\BooksController@bookOrder');
    Route::name('v2_library_bookName')->get('library/bookname', 'Connections\V2Controllers\LibraryCatalog\BooksController@bookNames');
    Route::name('v2_library_chapter')->get('library/chapter', 'Connections\V2Controllers\LibraryCatalog\BooksController@chapters');
    Route::name('v2_library_language')->get('library/language', 'Connections\V2Controllers\LibraryCatalog\LanguageController@languageListing');
    Route::name('v2_volume_history')->get('library/volumehistory', 'Connections\V2Controllers\LibraryCatalog\LibraryVolumeController@history');
    Route::name('v2_library_volumeLanguage')->get('library/volumelanguage', 'Connections\V2Controllers\LibraryCatalog\LanguageController@volumeLanguage');
    Route::name('v2_library_volumeLanguageFamily')->get('library/volumelanguagefamily', 'Connections\V2Controllers\LibraryCatalog\LanguageController@volumeLanguageFamily');
    Route::name('v2_country_lang')->get('country/countrylang', 'Connections\V2Controllers\LibraryCatalog\LanguageController@countryLang');
    Route::name('v2_library_metadata')->get('library/metadata', 'Connections\V2Controllers\LibraryCatalog\LibraryMetadataController@index');
    Route::name('v2_library_volume')->get('library/volume', 'Connections\V2Controllers\LibraryCatalog\LibraryVolumeController@libraryVolume');
    Route::name('v2_volume_organization_list')->get('library/volumeorganization', 'Organization\OrganizationsController@index');
    Route::name('v2_library_verseInfo')->get('library/verseinfo', 'Connections\V2Controllers\VerseController@info');
    Route::name('v2_library_numbers')->get('library/numbers', 'Wiki\NumbersController@customRange');
    Route::name('v2_library_organization')->get('library/organization', 'Organization\OrganizationsController@index');
    Route::name('v2_audio_location')->get('audio/location', 'Connections\V2Controllers\ApiMetadataController@libraryAsset');
    Route::name('v2_audio_path')->get('audio/path', 'Bible\AudioController@index');
    Route::name('v2_audio_timestamps')->get('audio/versestart', 'Bible\AudioController@timestampsByReference');
    Route::name('v2_text_font')->get('text/font', 'Bible\TextController@fonts');
    Route::name('v2_text_verse')->get('text/verse', 'Bible\TextController@index');
    Route::name('v2_text_search')->get('text/search', 'Bible\TextController@search');
    Route::name('v2_text_search_group')->get('text/searchgroup', 'Bible\TextController@searchGroup');
    Route::name('v2_video_location')->get('video/location', 'Organization\FilmsController@location');
    Route::name('v2_video_path')->get('video/videopath', 'Organization\FilmsController@videoPath');
    Route::name('v2_api_jesusFilms')->get('library/jesusfilm', 'Organization\ResourcesController@jesusFilmListing');

    Route::name('v2_api_jesusFilm_index')->get('video/jesusfilm', 'Connections\ArclightController@index');
    Route::name('v2_api_jesusFilm_stream')->get('arclight/chapter/{id}.m3u8', 'Connections\ArclightController@chapter');

    // Bible.is API
    Route::name('v2_users_banners_banner')->get('/banners/banner', 'Connections\V2Controllers\UsersController@banner');
    Route::name('v2_users_user')->match(['get','post','options'], '/users/user', 'Connections\V2Controllers\UsersController@user');
    Route::name('v2_users_profile')->post('/users/profile', 'Connections\V2Controllers\UsersController@profile');
    Route::name('v2_user_login')->match(['put','post','options'], '/users/login', 'Connections\V2Controllers\UsersController@login');
    Route::name('v2_annotations_list')->get('/annotations/list', 'Connections\V2Controllers\UsersController@annotationList');
    Route::name('v2_annotations_bookmarks')->get('/annotations/bookmark', 'Connections\V2Controllers\UsersController@annotationBookmark');
    Route::name('v2_annotations_bookmarks_store')->post('/annotations/bookmark', 'Connections\V2Controllers\UsersController@annotationBookmarkStore');
    Route::name('v2_annotations_notes')->get('/annotations/note', 'Connections\V2Controllers\UsersController@annotationNote');
    Route::name('v2_annotations_notes_store')->post('/annotations/note', 'Connections\V2Controllers\UsersController@annotationNoteStore');
    Route::name('v2_annotations_highlights')->get('/annotations/highlight', 'Connections\V2Controllers\UsersController@annotationHighlight');
    Route::name('v2_annotations_highlights_store')->match(['post','options','delete'], '/annotations/highlight', 'Connections\V2Controllers\UsersController@annotationHighlightAlter');

    // VERSION 3
    // What can man do against such reckless hate
    Route::prefix('v3')->group(function () {
        Route::name('v3_query')->get('search', 'Connections\V3Controller@search');
        Route::name('v3_books')->get('books', 'Connections\V3Controller@books');
    });

    // VERSION 4 | BIBLE
    Route::name('v4_access_groups.index')->get('access/groups', 'User\AccessGroupController@index');
    Route::name('v4_access_groups.store')->post('access/groups/', 'User\AccessGroupController@store');
    Route::name('v4_access_groups.show')->get('access/groups/{group_id}', 'User\AccessGroupController@show');
    Route::name('v4_access_groups.access')->get('access/current', 'User\AccessGroupController@current');
    Route::name('v4_access_groups.update')->put('access/groups/{group_id}', 'User\AccessGroupController@update');
    Route::name('v4_access_groups.destroy')->delete('access/groups/{group_id}', 'User\AccessGroupController@destroy');

    Route::name('v4_bible_filesets.types')->get('bibles/filesets/media/types', 'Bible\BibleFileSetsController@mediaTypes');
    Route::name('v4_video_stream')->get('bible/filesets/{fileset_id}/stream/{file_id}/playlist.m3u8', 'Bible\VideoStreamController@index');
    Route::name('v4_video_stream_ts')->get('bible/filesets/{fileset_id}/stream/{file_id}/{file_name}', 'Bible\VideoStreamController@transportStream');
    Route::name('v4_bible_filesets.podcast')->get('bibles/filesets/{fileset_id}/podcast', 'Bible\BibleFileSetsController@podcast');
    Route::name('v4_bible_filesets.download')->get('bibles/filesets/{fileset_id}/download', 'Bible\BibleFileSetsController@download');
    Route::name('v4_bible_filesets.copyright')->get('bibles/filesets/{fileset_id}/copyright', 'Bible\BibleFileSetsController@copyright');
    Route::name('v4_bible_filesets.books')->get('bibles/filesets/{fileset_id}/books', 'Bible\BooksController@show');
    Route::name('v4_bible_filesets.show')->get('bibles/filesets/{fileset_id?}', 'Bible\BibleFileSetsController@show');

    Route::name('v4_bible_filesets.chapter')->get('bibles/filesets/{fileset_id}/{book}/{chapter}', 'Bible\TextController@index');
    Route::name('v4_text_search')->get('search', 'Bible\TextController@search');

    Route::name('v4_bible.links')->get('bibles/links', 'Bible\BibleLinksController@index');
    Route::name('v4_bible_books_all')->get('bibles/books/', 'Bible\BooksController@index');
    Route::name('v4_bible_equivalents.all')->get('bible/equivalents', 'Bible\BibleEquivalentsController@index');
    Route::name('v4_bible.books')->get('bibles/{bible_id}/book/{book?}', 'Bible\BiblesController@books');
    Route::name('v4_bible.archival')->get('bibles/archival', 'Bible\BiblesController@archival');
    Route::name('v4_bible.one')->get('bibles/{bible_id}', 'Bible\BiblesController@show');
    Route::name('v4_bible.all')->get('bibles', 'Bible\BiblesController@index');
    Route::name('v4_timestamps')->get('timestamps', 'Bible\AudioController@availableTimestamps');
    Route::name('v4_timestamps.tag')->get('/timestamps/search', 'Bible\AudioController@timestampsByTag');
    Route::name('v4_timestamps.verse')->get('timestamps/{fileset_id}/{book}/{chapter}', 'Bible\AudioController@timestampsByReference');

    // VERSION 4 | WIKI
    Route::name('v4_countries.all')->get('countries', 'Wiki\CountriesController@index');
    Route::name('v4_countries.jsp')->get('countries/joshua-project/', 'Wiki\CountriesController@joshuaProjectIndex');
    Route::name('v4_countries.one')->get('countries/{country_id}', 'Wiki\CountriesController@show');
    Route::name('v4_languages.all')->get('languages', 'Wiki\LanguagesController@index');
    Route::name('v4_languages.one')->get('languages/{language_id}', 'Wiki\LanguagesController@show');
    Route::name('v4_alphabets.all')->get('alphabets', 'Wiki\AlphabetsController@index');
    Route::name('v4_alphabets.one')->get('alphabets/{alphabet_id}', 'Wiki\AlphabetsController@show');
    Route::name('v4_alphabets.store')->post('alphabets', 'Wiki\AlphabetsController@store');
    Route::name('v4_alphabets.update')->put('alphabets/{alphabet_id}', 'Wiki\AlphabetsController@update');
    Route::name('v4_numbers.all')->get('numbers/', 'Wiki\NumbersController@index');
    Route::name('v4_numbers.one')->get('numbers/{number_id}', 'Wiki\NumbersController@show');

    // VERSION 4 | USERS
    Route::name('v4_user.index')->get('users', 'User\UsersController@index');
    Route::name('v4_user.store')->post('users', 'User\UsersController@store');
    Route::name('v4_user.geolocate')->get('users/geolocate', 'User\UsersController@geoLocate');

    Route::name('v4_user.login')->post('/login', 'User\UsersController@login');
    Route::name('v4_user.oAuth')->get('/login/{driver}', 'User\UserSocialController@getSocialRedirect');
    Route::name('v4_user.oAuthCallback')->get('/login/{driver}/callback', 'User\UserSocialController@handleProviderCallback');

    Route::name('v4_user.show')->get('users/{user_id}', 'User\UsersController@show');
    Route::name('v4_user.update')->put('users/{user_id}', 'User\UsersController@update');
    Route::name('v4_user.destroy')->delete('users/{user_id}', 'User\UsersController@destroy');

    // VERSION 4 | USER PASSWORDS
    Route::name('v4_user.password_reset')->post('users/password/reset/{token?}', 'User\UserPasswordsController@validatePasswordReset');
    Route::name('v4_user.password_email')->post('users/password/email', 'User\UserPasswordsController@triggerPasswordResetEmail');

    // VERSION 4 | USER ACCOUNTS
    Route::name('v4_user_accounts.index')->get('/accounts', 'User\UserAccountsController@index');
    Route::name('v4_user_accounts.show')->get('/accounts/{account_id}', 'User\UserAccountsController@show');
    Route::name('v4_user_accounts.store')->post('/accounts', 'User\UserAccountsController@store');
    Route::name('v4_user_accounts.update')->put('/accounts/{account_id}', 'User\UserAccountsController@update');
    Route::name('v4_user_accounts.destroy')->delete('/accounts/{account_id}', 'User\UserAccountsController@destroy');

    // VERSION 4 | USER NOTES
    Route::name('v4_notes.index')->get('users/{user_id}/notes', 'User\UserNotesController@index');
    Route::name('v4_notes.show')->get('users/{user_id}/notes/{note_id}', 'User\UserNotesController@show');
    Route::name('v4_notes.store')->post('users/{user_id}/notes', 'User\UserNotesController@store');
    Route::name('v4_notes.update')->put('users/{user_id}/notes/{note_id}', 'User\UserNotesController@update');
    Route::name('v4_notes.destroy')->delete('users/{user_id}/notes/{note_id}', 'User\UserNotesController@destroy');

    // VERSION 4 | USER Messages
    Route::name('v4_messages.index')->get('users/messages', 'User\UserContactController@index');
    Route::name('v4_messages.show')->get('users/messages/{note_id}', 'User\UserContactController@show');

    // VERSION 4 | USER BOOKMARKS
    Route::name('v4_bookmarks.index')->get('users/{user_id}/bookmarks', 'User\UserBookmarksController@index');
    Route::name('v4_bookmarks.store')->post('users/{user_id}/bookmarks', 'User\UserBookmarksController@store');
    Route::name('v4_bookmarks.update')->put('users/{user_id}/bookmarks/{bookmark_id}', 'User\UserBookmarksController@update');
    Route::name('v4_bookmarks.destroy')->delete('users/{user_id}/bookmarks/{bookmark_id}', 'User\UserBookmarksController@destroy');

    // VERSION 4 | USER HIGHLIGHTS
    Route::name('v4_highlights.index')->get('users/{user_id}/highlights', 'User\UserHighlightsController@index');
    Route::name('v4_highlights.store')->post('users/{user_id}/highlights', 'User\UserHighlightsController@store');
    Route::name('v4_highlights.update')->put('users/{user_id}/highlights/{highlight_id}', 'User\UserHighlightsController@update');
    Route::name('v4_highlights.destroy')->delete('users/{user_id}/highlights/{highlight_id}', 'User\UserHighlightsController@destroy');

    Route::name('v4_organizations.all')->get('organizations/', 'Organization\OrganizationsController@index');
    Route::name('v4_organizations.one')->get('organizations/{organization_id}', 'Organization\OrganizationsController@show');
    Route::name('v4_organizations.compare')->get('organizations/compare/{org1}/to/{org2}', 'Organization\OrganizationsController@compare');
    Route::name('v4_api.versions')->get('/api/versions', 'HomeController@versions');

    // VERSION 4 | PROJECTS
    Route::name('v4_projects.index')->get('projects', 'Organization\ProjectsController@index');
    Route::name('v4_projects.show')->get('projects/{project_id}', 'Organization\ProjectsController@show');
    Route::name('v4_projects.update')->put('projects/{project_id}', 'Organization\ProjectsController@update');
    Route::name('v4_projects.store')->post('projects', 'Organization\ProjectsController@store');
    Route::name('v4_projects.destroy')->delete('projects/{project_id}', 'Organization\ProjectsController@destroy');
    Route::name('v4_projects_oAuthProvider.index')->get('projects/{project_id}/oauth-providers/', 'Organization\ProjectOAuthProvidersController@index');
    Route::name('v4_projects_oAuthProvider.show')->get('projects/{project_id}/oauth-providers/{id}', 'Organization\ProjectOAuthProvidersController@show');
    Route::name('v4_projects_oAuthProvider.update')->put('projects/{project_id}/oauth-providers/{id}', 'Organization\ProjectOAuthProvidersController@update');
    Route::name('v4_projects_oAuthProvider.store')->post('projects/{project_id}/oauth-providers', 'Organization\ProjectOAuthProvidersController@store');
    Route::name('v4_projects_oAuthProvider.destroy')->delete('projects/{project_id}/oauth-providers/{id}', 'Organization\ProjectOAuthProvidersController@destroy');

    // VERSION 4 | UTILITY
    Route::name('v4_api.buckets')->get('/api/buckets', 'HomeController@buckets');
    Route::name('v4_api.stats')->get('/stats', 'HomeController@stats');
    Route::name('v4_algolia.bibles')->get('/algolia/bibles', 'Connections\AlgoliaOutputController@bibles');
    Route::name('v4_algolia.languages')->get('algolia/languages', 'Connections\AlgoliaOutputController@languages');
    Route::name('v4_connections_jfm.sync')->get('connections/jesus-film/sync', 'Connections\ArclightController@sync');
    Route::name('v4_connections_jfm.index')->get('connections/jesus-film/{iso}', 'Connections\ArclightController@index');
    Route::name('v4_connections_app.deeplink')->get('connections/app/deeplinking', 'Connections\MobileAppsController@redirecDeepLink');
    Route::name('v4_resources.index')->get('resources', 'Organization\ResourcesController@index');
    Route::name('v4_resources.show')->get('resources/{resource_id}', 'Organization\ResourcesController@show');
    Route::name('v4_resources.update')->put('resources/{resource_id}', 'Organization\ResourcesController@update');
    Route::name('v4_resources.store')->post('resources', 'Organization\ResourcesController@store');
    Route::name('v4_resources.destroy')->delete('resources/{resource_id}', 'Organization\ResourcesController@destroy');
    Route::name('v4_articles.index')->get('articles', 'User\ArticlesController@index');
    Route::name('v4_articles.show')->get('articles/{article_id}', 'User\ArticlesController@show');
    Route::name('v4_articles.update')->put('articles/{article_id}', 'User\ArticlesController@update');
    Route::name('v4_articles.store')->post('articles', 'User\ArticlesController@store');
    Route::name('v4_articles.destroy')->delete('articles/{article_id}', 'User\ArticlesController@destroy');
