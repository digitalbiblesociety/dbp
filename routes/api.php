<?php

    // VERSION 2
    Route::name('v2_pass_through')->match(['get','options'], 'pass-through/{path1?}/{path2?}', 'Connections\V2Controllers\ApiMetadataController@passThrough');
    Route::name('v2_library_asset')->match(['get','options'], 'library/asset', 'Connections\V2Controllers\ApiMetadataController@libraryAsset');
    Route::name('v2_api_versionLatest')->match(['get','options'], 'api/apiversion', 'Connections\V2Controllers\ApiMetadataController@versionLatest');
    Route::name('v2_api_apiReply')->match(['get','options'], 'api/reply', 'Connections\V2Controllers\ApiMetadataController@versionReplyTypes');
    Route::name('v2_library_version')->match(['get','options'], 'library/version', 'Connections\V2Controllers\LibraryCatalog\LibraryVersionController@libraryVersion');
    Route::name('v2_library_book')->match(['get','options'], 'library/book', 'Connections\V2Controllers\LibraryCatalog\BooksController@book');
    Route::name('v2_library_bookOrder')->match(['get','options'], 'library/bookorder', 'Connections\V2Controllers\LibraryCatalog\BooksController@bookOrder');
    Route::name('v2_library_bookName')->match(['get','options'], 'library/bookname', 'Connections\V2Controllers\LibraryCatalog\BooksController@bookNames');
    Route::name('v2_library_chapter')->match(['get','options'], 'library/chapter', 'Connections\V2Controllers\LibraryCatalog\BooksController@chapters');
    Route::name('v2_library_language')->match(['get','options'], 'library/language', 'Connections\V2Controllers\LibraryCatalog\LanguageController@languageListing');
    Route::name('v2_volume_history')->match(['get','options'], 'library/volumehistory', 'Connections\V2Controllers\LibraryCatalog\LibraryVolumeController@history');
    Route::name('v2_library_volumeLanguage')->match(['get','options'], 'library/volumelanguage', 'Connections\V2Controllers\LibraryCatalog\LanguageController@volumeLanguage');
    Route::name('v2_library_volumeLanguageFamily')->match(['get','options'], 'library/volumelanguagefamily', 'Connections\V2Controllers\LibraryCatalog\LanguageController@volumeLanguageFamily');
    Route::name('v2_country_lang')->match(['get','options'], 'country/countrylang', 'Connections\V2Controllers\LibraryCatalog\LanguageController@countryLang');
    Route::name('v2_library_metadata')->match(['get','options'], 'library/metadata', 'Connections\V2Controllers\LibraryCatalog\LibraryMetadataController@index');
    Route::name('v2_library_volume')->match(['get','options'], 'library/volume', 'Connections\V2Controllers\LibraryCatalog\LibraryVolumeController@libraryVolume');
    Route::name('v2_volume_organization_list')->match(['get','options'], 'library/volumeorganization', 'Organization\OrganizationsController@index');
    Route::name('v2_library_verseInfo')->match(['get','options'], 'library/verseinfo', 'Connections\V2Controllers\VerseController@info');
    Route::name('v2_library_numbers')->match(['get','options'], 'library/numbers', 'Wiki\NumbersController@customRange');
    Route::name('v2_library_organization')->match(['get','options'], 'library/organization', 'Organization\OrganizationsController@index');
    Route::name('v2_audio_location')->match(['get','options'], 'audio/location', 'Connections\V2Controllers\ApiMetadataController@libraryAsset');
    Route::name('v2_audio_path')->match(['get','options'], 'audio/path', 'Bible\AudioController@index');
    Route::name('v2_audio_timestamps')->match(['get','options'], 'audio/versestart', 'Bible\AudioController@timestampsByReference');
    Route::name('v2_text_font')->match(['get','options'], 'text/font', 'Bible\TextController@fonts');
    Route::name('v2_text_verse')->match(['get','options'], 'text/verse', 'Bible\TextController@index');
    Route::name('v2_text_search')->match(['get','options'], 'text/search', 'Bible\TextController@search');
    Route::name('v2_text_search_group')->match(['get','options'], 'text/searchgroup', 'Bible\TextController@searchGroup');
    Route::name('v2_video_location')->match(['get','options'], 'video/location', 'Organization\FilmsController@location');
    Route::name('v2_video_path')->match(['get','options'], 'video/videopath', 'Organization\FilmsController@videoPath');
    Route::name('v2_api_jesusFilms')->match(['get','options'], 'library/jesusfilm', 'Organization\ResourcesController@jesusFilmListing');

    Route::name('v2_api_jesusFilm_index')->match(['get','options'], 'video/jesusfilm', 'Connections\ArclightController@index');
    Route::name('v2_api_jesusFilm_stream')->match(['get','options'], 'arclight/chapter/{id}.m3u8', 'Connections\ArclightController@chapter');

    // Bible.is API
    Route::name('v2_users_banners_banner')->match(['get','options'], '/banners/banner', 'Connections\V2Controllers\UsersController@banner');
    Route::name('v2_users_user')->match(['get','post','options'], '/users/user', 'Connections\V2Controllers\UsersController@user');
    Route::name('v2_users_profile')->match(['post','options'], '/users/profile', 'Connections\V2Controllers\UsersController@profile');
    Route::name('v2_user_login')->match(['put','post','options'], '/users/login', 'Connections\V2Controllers\UsersController@login');
    Route::name('v2_annotations_list')->match(['get','options'], '/annotations/list', 'Connections\V2Controllers\UsersController@annotationList');
    Route::name('v2_annotations_bookmarks')->match(['get','options'], '/annotations/bookmark', 'Connections\V2Controllers\UsersController@annotationBookmark');
    Route::name('v2_annotations_bookmarks_store')->match(['post','options'], '/annotations/bookmark', 'Connections\V2Controllers\UsersController@annotationBookmarkStore');
    Route::name('v2_annotations_notes')->match(['get','options'], '/annotations/note', 'Connections\V2Controllers\UsersController@annotationNote');
    Route::name('v2_annotations_notes_store')->match(['post','options'], '/annotations/note', 'Connections\V2Controllers\UsersController@annotationNoteStore');
    Route::name('v2_annotations_highlights')->match(['get','options'], '/annotations/highlight', 'Connections\V2Controllers\UsersController@annotationHighlight');
    Route::name('v2_annotations_highlights_store')->match(['post','options','delete'], '/annotations/highlight', 'Connections\V2Controllers\UsersController@annotationHighlightAlter');

    // VERSION 3
    // What can man do against such reckless hate
    Route::prefix('v3')->group(function () {
        Route::name('v3_query')->match(['get','options'], 'search', 'Connections\V3Controller@search');
        Route::name('v3_books')->match(['get','options'], 'books', 'Connections\V3Controller@books');
    });

    // VERSION 4 | BIBLE
    Route::name('v4_access_groups.index')->match(['get','options'], 'access/groups', 'User\AccessGroupController@index');
    Route::name('v4_access_groups.store')->match(['post','options'], 'access/groups/', 'User\AccessGroupController@store');
    Route::name('v4_access_groups.show')->match(['get','options'], 'access/groups/{group_id}', 'User\AccessGroupController@show');
    Route::name('v4_access_groups.access')->match(['get','options'], 'access/current', 'User\AccessGroupController@current');
    Route::name('v4_access_groups.update')->match(['put','options'], 'access/groups/{group_id}', 'User\AccessGroupController@update');
    Route::name('v4_access_groups.destroy')->match(['delete','options'], 'access/groups/{group_id}', 'User\AccessGroupController@destroy');

    Route::name('v4_bible_filesets.types')->match(['get','options'], 'bibles/filesets/media/types', 'Bible\BibleFileSetsController@mediaTypes');
    Route::name('v4_video_stream')->match(['get','options'], 'bible/filesets/{fileset_id}/stream/{file_id}/playlist.m3u8', 'Bible\VideoStreamController@index');
    Route::name('v4_video_stream_ts')->match(['get','options'], 'bible/filesets/{fileset_id}/stream/{file_id}/{file_name}', 'Bible\VideoStreamController@transportStream');
    Route::name('v4_bible_filesets.podcast')->match(['get','options'], 'bibles/filesets/{fileset_id}/podcast', 'Bible\BibleFileSetsController@podcast');
    Route::name('v4_bible_filesets.download')->match(['get','options'], 'bibles/filesets/{fileset_id}/download', 'Bible\BibleFileSetsController@download');
    Route::name('v4_bible_filesets.copyright')->match(['get','options'], 'bibles/filesets/{fileset_id}/copyright', 'Bible\BibleFileSetsController@copyright');
    Route::name('v4_bible_filesets.books')->match(['get','options'], 'bibles/filesets/{fileset_id}/books', 'Bible\BooksController@show');
    Route::name('v4_bible_filesets.show')->match(['get','options'], 'bibles/filesets/{fileset_id?}', 'Bible\BibleFileSetsController@show');

    Route::name('v4_bible_filesets.chapter')->match(['get','options'], 'bibles/filesets/{fileset_id}/{book}/{chapter}', 'Bible\TextController@index');
    Route::name('v4_text_search')->match(['get','options'], 'search', 'Bible\TextController@search');

    Route::name('v4_bible.links')->match(['get','options'], 'bibles/links', 'Bible\BibleLinksController@index');
    Route::name('v4_bible_books_all')->match(['get','options'], 'bibles/books/', 'Bible\BooksController@index');
    Route::name('v4_bible_equivalents.all')->match(['get','options'], 'bible/equivalents', 'Bible\BibleEquivalentsController@index');
    Route::name('v4_bible.books')->match(['get','options'], 'bibles/{bible_id}/book/{book?}', 'Bible\BiblesController@books');
    Route::name('v4_bible.archival')->match(['get','options'], 'bibles/archival', 'Bible\BiblesController@archival');
    Route::name('v4_bible.one')->match(['get','options'], 'bibles/{bible_id}', 'Bible\BiblesController@show');
    Route::name('v4_bible.all')->match(['get','options'], 'bibles', 'Bible\BiblesController@index');
    Route::name('v4_timestamps')->match(['get','options'], 'timestamps', 'Bible\AudioController@availableTimestamps');
    Route::name('v4_timestamps.tag')->match(['get','options'], '/timestamps/search', 'Bible\AudioController@timestampsByTag');
    Route::name('v4_timestamps.verse')->match(['get','options'], 'timestamps/{fileset_id}/{book}/{chapter}', 'Bible\AudioController@timestampsByReference');

    // VERSION 4 | WIKI
    Route::name('v4_countries.all')->match(['get','options'], 'countries', 'Wiki\CountriesController@index');
    Route::name('v4_countries.jsp')->match(['get','options'], 'countries/joshua-project/', 'Wiki\CountriesController@joshuaProjectIndex');
    Route::name('v4_countries.one')->match(['get','options'], 'countries/{country_id}', 'Wiki\CountriesController@show');
    Route::name('v4_languages.all')->match(['get','options'], 'languages', 'Wiki\LanguagesController@index');
    Route::name('v4_languages.one')->match(['get','options'], 'languages/{language_id}', 'Wiki\LanguagesController@show');
    Route::name('v4_alphabets.all')->match(['get','options'], 'alphabets', 'Wiki\AlphabetsController@index');
    Route::name('v4_alphabets.one')->match(['get','options'], 'alphabets/{alphabet_id}', 'Wiki\AlphabetsController@show');
    Route::name('v4_alphabets.store')->match(['post','options'], 'alphabets', 'Wiki\AlphabetsController@store');
    Route::name('v4_alphabets.update')->match(['put','options'], 'alphabets/{alphabet_id}', 'Wiki\AlphabetsController@update');
    Route::name('v4_numbers.all')->match(['get','options'], 'numbers/', 'Wiki\NumbersController@index');
    Route::name('v4_numbers.one')->match(['get','options'], 'numbers/{number_id}', 'Wiki\NumbersController@show');

    // VERSION 4 | USERS
    Route::name('v4_user.index')->match(['get','options'], 'users', 'User\UsersController@index');
    Route::name('v4_user.store')->match(['post','options'], 'users', 'User\UsersController@store');
    Route::name('v4_user.geolocate')->match(['get','options'], 'users/geolocate', 'User\UsersController@geoLocate');

    Route::name('v4_user.login')->match(['post','options'], '/login', 'User\UsersController@login');
    Route::name('v4_user.oAuth')->match(['get','options'], '/login/{driver}', 'User\UserSocialController@getSocialRedirect');
    Route::name('v4_user.oAuthCallback')->match(['get','options'], '/login/{driver}/callback', 'User\UserSocialController@handleProviderCallback');

    Route::name('v4_user.show')->match(['get','options'], 'users/{user_id}', 'User\UsersController@show');
    Route::name('v4_user.update')->match(['put','options'], 'users/{user_id}', 'User\UsersController@update');
    Route::name('v4_user.destroy')->match(['delete','options'], 'users/{user_id}', 'User\UsersController@destroy');

    // VERSION 4 | USER PASSWORDS
    Route::name('v4_user.password_reset')->match(['post','options'], 'users/password/reset/{token?}', 'User\UserPasswordsController@validatePasswordReset');
    Route::name('v4_user.password_email')->match(['post','options'], 'users/password/email', 'User\UserPasswordsController@triggerPasswordResetEmail');

    // VERSION 4 | USER ACCOUNTS
    Route::name('v4_user_accounts.index')->match(['get','options'], '/accounts', 'User\UserAccountsController@index');
    Route::name('v4_user_accounts.show')->match(['get','options'], '/accounts/{account_id}', 'User\UserAccountsController@show');
    Route::name('v4_user_accounts.store')->match(['post','options'], '/accounts', 'User\UserAccountsController@store');
    Route::name('v4_user_accounts.update')->match(['put','options'], '/accounts/{account_id}', 'User\UserAccountsController@update');
    Route::name('v4_user_accounts.destroy')->match(['delete','options'], '/accounts/{account_id}', 'User\UserAccountsController@destroy');

    // VERSION 4 | USER NOTES
    Route::name('v4_notes.index')->match(['get','options'], 'users/{user_id}/notes', 'User\UserNotesController@index');
    Route::name('v4_notes.show')->match(['get','options'], 'users/{user_id}/notes/{note_id}', 'User\UserNotesController@show');
    Route::name('v4_notes.store')->match(['post','options'], 'users/{user_id}/notes', 'User\UserNotesController@store');
    Route::name('v4_notes.update')->match(['put','options'], 'users/{user_id}/notes/{note_id}', 'User\UserNotesController@update');
    Route::name('v4_notes.destroy')->match(['delete','options'], 'users/{user_id}/notes/{note_id}', 'User\UserNotesController@destroy');

    // VERSION 4 | USER Messages
    Route::name('v4_messages.index')->match(['get','options'], 'users/messages', 'User\UserContactController@index');
    Route::name('v4_messages.show')->match(['get','options'], 'users/messages/{note_id}', 'User\UserContactController@show');

    // VERSION 4 | USER BOOKMARKS
    Route::name('v4_bookmarks.index')->match(['get','options'], 'users/{user_id}/bookmarks', 'User\UserBookmarksController@index');
    Route::name('v4_bookmarks.store')->match(['post','options'], 'users/{user_id}/bookmarks', 'User\UserBookmarksController@store');
    Route::name('v4_bookmarks.update')->match(['put','options'], 'users/{user_id}/bookmarks/{bookmark_id}', 'User\UserBookmarksController@update');
    Route::name('v4_bookmarks.destroy')->match(['delete','options'], 'users/{user_id}/bookmarks/{bookmark_id}', 'User\UserBookmarksController@destroy');

    // VERSION 4 | USER HIGHLIGHTS
    Route::name('v4_highlights.index')->match(['get','options'], 'users/{user_id}/highlights', 'User\UserHighlightsController@index');
    Route::name('v4_highlights.store')->match(['post','options'], 'users/{user_id}/highlights', 'User\UserHighlightsController@store');
    Route::name('v4_highlights.update')->match(['put','options'], 'users/{user_id}/highlights/{highlight_id}', 'User\UserHighlightsController@update');
    Route::name('v4_highlights.destroy')->match(['delete','options'], 'users/{user_id}/highlights/{highlight_id}', 'User\UserHighlightsController@destroy');

    Route::name('v4_organizations.all')->match(['get','options'], 'organizations/', 'Organization\OrganizationsController@index');
    Route::name('v4_organizations.one')->match(['get','options'], 'organizations/{organization_id}', 'Organization\OrganizationsController@show');
    Route::name('v4_organizations.compare')->match(['get','options'], 'organizations/compare/{org1}/to/{org2}', 'Organization\OrganizationsController@compare');
    Route::name('v4_api.versions')->match(['get','options'], '/api/versions', 'HomeController@versions');

    // VERSION 4 | PROJECTS
    Route::name('v4_projects.index')->match(['get','options'], 'projects', 'Organization\ProjectsController@index');
    Route::name('v4_projects.show')->match(['get','options'], 'projects/{project_id}', 'Organization\ProjectsController@show');
    Route::name('v4_projects.update')->match(['put','options'], 'projects/{project_id}', 'Organization\ProjectsController@update');
    Route::name('v4_projects.store')->match(['post','options'], 'projects', 'Organization\ProjectsController@store');
    Route::name('v4_projects.destroy')->match(['delete','options'], 'projects/{project_id}', 'Organization\ProjectsController@destroy');
    Route::name('v4_projects_oAuthProvider.index')->match(['get','options'], 'projects/{project_id}/oauth-providers/', 'Organization\ProjectOAuthProvidersController@index');
    Route::name('v4_projects_oAuthProvider.show')->match(['get','options'], 'projects/{project_id}/oauth-providers/{id}', 'Organization\ProjectOAuthProvidersController@show');
    Route::name('v4_projects_oAuthProvider.update')->match(['put','options'], 'projects/{project_id}/oauth-providers/{id}', 'Organization\ProjectOAuthProvidersController@update');
    Route::name('v4_projects_oAuthProvider.store')->match(['post','options'], 'projects/{project_id}/oauth-providers', 'Organization\ProjectOAuthProvidersController@store');
    Route::name('v4_projects_oAuthProvider.destroy')->match(['delete','options'], 'projects/{project_id}/oauth-providers/{id}', 'Organization\ProjectOAuthProvidersController@destroy');

    // VERSION 4 | UTILITY
    Route::name('v4_api.buckets')->match(['get','options'], '/api/buckets', 'HomeController@buckets');
    Route::name('v4_api.stats')->match(['get','options'], '/stats', 'HomeController@stats');
    Route::name('v4_algolia.bibles')->match(['get','options'], '/algolia/bibles', 'Connections\AlgoliaOutputController@bibles');
    Route::name('v4_algolia.languages')->match(['get','options'], 'algolia/languages', 'Connections\AlgoliaOutputController@languages');
    Route::name('v4_connections_jfm.sync')->match(['get','options'], 'connections/jesus-film/sync', 'Connections\ArclightController@sync');
    Route::name('v4_connections_jfm.index')->match(['get','options'], 'connections/jesus-film/{iso}', 'Connections\ArclightController@index');
    Route::name('v4_connections_app.deeplink')->match(['get','options'], 'connections/app/deeplinking', 'Connections\MobileAppsController@redirecDeepLink');
    Route::name('v4_connections_grn.sync')->match(['get','options'], 'connections/grn/sync', 'Connections\GRNController@sync');
    Route::name('v4_connections_grn.index')->match(['get','options'], 'connections/grn/{iso}', 'Connections\GRNController@index');
    Route::name('v4_resources.index')->match(['get','options'], 'resources', 'Organization\ResourcesController@index');
    Route::name('v4_resources.show')->match(['get','options'], 'resources/{resource_id}', 'Organization\ResourcesController@show');
    Route::name('v4_resources.update')->match(['put','options'], 'resources/{resource_id}', 'Organization\ResourcesController@update');
    Route::name('v4_resources.store')->match(['post','options'], 'resources', 'Organization\ResourcesController@store');
    Route::name('v4_resources.destroy')->match(['delete','options'], 'resources/{resource_id}', 'Organization\ResourcesController@destroy');
    Route::name('v4_articles.index')->match(['get','options'], 'articles', 'User\ArticlesController@index');
    Route::name('v4_articles.show')->match(['get','options'], 'articles/{article_id}', 'User\ArticlesController@show');
    Route::name('v4_articles.update')->match(['put','options'], 'articles/{article_id}', 'User\ArticlesController@update');
    Route::name('v4_articles.store')->match(['post','options'], 'articles', 'User\ArticlesController@store');
    Route::name('v4_articles.destroy')->match(['delete','options'], 'articles/{article_id}', 'User\ArticlesController@destroy');
