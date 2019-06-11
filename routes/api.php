<?php

// VERSION 2 | Metadata
Route::name('v2_pass_through')->get('pass-through/{path1?}/{path2?}',              'ApiMetadataController@passThrough');
Route::name('v2_library_asset')->get('library/asset',                              'ApiMetadataController@assets');
Route::name('v2_api_versionLatest')->get('api/apiversion',                         'ApiMetadataController@versionLatest');
Route::name('v2_api_apiReply')->get('api/reply',                                   'ApiMetadataController@replyTypes');

// VERSION 2 | Books
Route::name('v2_library_book')->get('library/book',                                'Bible\BooksControllerV2@book');
Route::name('v2_library_bookOrder')->get('library/bookorder',                      'Bible\BooksControllerV2@bookOrder');
Route::name('v2_library_bookName')->get('library/bookname',                        'Bible\BooksControllerV2@bookNames');
Route::name('v2_library_chapter')->get('library/chapter',                          'Bible\BooksControllerV2@chapters');

// VERSION 2 | Languages
Route::name('v2_library_language')->get('library/language',                        'Wiki\LanguageControllerV2@languageListing');
Route::name('v2_library_volumeLanguage')->get('library/volumelanguage',            'Wiki\LanguageControllerV2@volumeLanguage');
Route::name('v2_library_volumeLanguageFamily')->get('library/volumelanguagefamily','Wiki\LanguageControllerV2@volumeLanguageFamily');
Route::name('v2_country_lang')->get('country/countrylang',                         'Wiki\LanguageControllerV2@countryLang');

// VERSION 2 | Library
Route::name('v2_library_version')->get('library/version',                          'Bible\LibraryController@version');
Route::name('v2_library_metadata')->get('library/metadata',                        'Bible\LibraryController@metadata');
Route::name('v2_library_volume')->get('library/volume',                            'Bible\LibraryController@volume');
Route::name('v2_library_verseInfo')->get('library/verseinfo',                      'Bible\TextController@info');
Route::name('v2_library_numbers')->get('library/numbers',                          'Wiki\NumbersController@customRange');
Route::name('v2_library_organization')->get('library/organization',                'Organization\OrganizationsController@index');
Route::name('v2_volume_history')->get('library/volumehistory',                     'Bible\LibraryController@history');
Route::name('v2_volume_organization_list')->get('library/volumeorganization',      'Organization\OrganizationsController@index');

// VERSION 2 | Text
Route::name('v2_text_font')->get('text/font',                                      'Bible\TextController@fonts');
Route::name('v2_text_verse')->get('text/verse',                                    'Bible\TextController@index');
Route::name('v2_text_search')->get('text/search',                                  'Bible\TextController@search');
Route::name('v2_text_search_group')->get('text/searchgroup',                       'Bible\TextController@searchGroup');

// VERSION 2 | Audio
Route::name('v2_audio_location')->get('audio/location',                            'ApiMetadataController@assets');
Route::name('v2_audio_path')->get('audio/path',                                    'Bible\AudioController@index');
Route::name('v2_audio_timestamps')->get('audio/versestart',                        'Bible\AudioController@timestampsByReference');

// VERSION 2 | Video
Route::name('v2_video_location')->get('video/location',                            'Organization\FilmsController@location');
Route::name('v2_video_path')->get('video/videopath',                               'Organization\FilmsController@videoPath');
Route::name('v2_api_jesusFilms')->get('library/jesusfilm',                         'Organization\ResourcesController@jesusFilmListing');

Route::name('v2_api_jesusFilm_index')->get('video/jesusfilm',                     'Connections\ArclightController@index');
Route::name('v2_api_jesusFilm_stream')->get('video/jesusfilm/{id}.m3u8',          'Connections\ArclightController@chapter');

// VERSION 2 | Users
Route::name('v2_users_banners_banner')->get('/banners/banner',                     'User\UsersControllerV2@banner');
Route::name('v2_users_user')->match(['get','post','options'], '/users/user',       'User\UsersControllerV2@user');
Route::name('v2_users_profile')->post('/users/profile',                            'User\UsersControllerV2@profile');
Route::name('v2_user_login')->match(['put','post','options'], '/users/login',      'User\UsersControllerV2@login');
Route::name('v2_annotations')->get('/annotations/list',                            'User\UsersControllerV2@annotationList');
Route::name('v2_bookmarks')->get('/annotations/bookmark',                          'User\UsersControllerV2@bookmark');
Route::name('v2_bookmarks_alter')->post('/annotations/bookmark',                   'User\UsersControllerV2@bookmarkAlter');
Route::name('v2_bookmarks_delete')->delete('/annotations/bookmark',                'User\UsersControllerV2@bookmarkAlter');
Route::name('v2_notes')->get('/annotations/note',                                  'User\UsersControllerV2@note');
Route::name('v2_notes_store')->post('/annotations/note',                           'User\UsersControllerV2@noteAlter');
Route::name('v2_notes_delete')->delete('/annotations/note',                        'User\UsersControllerV2@noteAlter');
Route::name('v2_highlights')->get('/annotations/highlight',                        'User\UsersControllerV2@highlight');
Route::name('v2_highlights_store')->post('/annotations/highlight',                 'User\UsersControllerV2@highlightAlter');
Route::name('v2_highlights_delete')->delete('/annotations/highlight',              'User\UsersControllerV2@highlightAlter');

Route::prefix('v3')->group(function () {
    Route::name('v3_query')->get('search',                                         'Connections\V3Controller@search');
    Route::name('v3_books')->get('books',                                          'Connections\V3Controller@books');
});

// VERSION 4 | Access Groups
Route::name('v4_access_groups.index')->get('access/groups',                        'User\AccessGroupController@index');
Route::name('v4_access_groups.store')->post('access/groups/',                      'User\AccessGroupController@store');
Route::name('v4_access_groups.show')->get('access/groups/{group_id}',              'User\AccessGroupController@show');
Route::name('v4_access_groups.access')->get('access/current',                      'User\AccessGroupController@current');
Route::name('v4_access_groups.update')->put('access/groups/{group_id}',            'User\AccessGroupController@update');
Route::name('v4_access_groups.destroy')->delete('access/groups/{group_id}',        'User\AccessGroupController@destroy');

// VERSION 4 | Stream
Route::name('v4_video_stream')->get('bible/filesets/{fileset_id}/{file_id}/playlist.m3u8',    'Bible\VideoStreamController@index');
Route::name('v4_video_stream_ts')->get('bible/filesets/{fileset_id}/{file_id}/{file_name}',   'Bible\VideoStreamController@transportStream');

// VERSION 4 | Bible
Route::name('v4_bible.books')->get('bibles/{bible_id}/book/{book?}',               'Bible\BiblesController@books');
Route::name('v4_bible_equivalents.all')->get('bible/equivalents',                  'Bible\BibleEquivalentsController@index');
Route::name('v4_bible.archival')->get('bibles/archival',                           'Bible\BiblesController@archival');
Route::name('v4_bible.links')->get('bibles/links',                                 'Bible\BibleLinksController@index');
Route::name('v4_bible_books_all')->get('bibles/books/',                            'Bible\BooksController@index');
Route::name('v4_bible.one')->get('bibles/{bible_id}',                              'Bible\BiblesController@show');
Route::name('v4_bible.all')->get('bibles',                                         'Bible\BiblesController@index');

// VERSION 4 | Filesets
Route::name('v4_filesets.types')->get('bibles/filesets/media/types',               'Bible\BibleFileSetsController@mediaTypes');
Route::name('v4_filesets.podcast')->get('bibles/filesets/{fileset_id}/podcast',    'Bible\BibleFilesetsPodcastController@index');
Route::name('v4_filesets.download')->get('bibles/filesets/{fileset_id}/download',  'Bible\BibleFileSetsController@download');
Route::name('v4_filesets.copyright')->get('bibles/filesets/{fileset_id}/copyright','Bible\BibleFileSetsController@copyright');
Route::name('v4_filesets.show')->get('bibles/filesets/{fileset_id?}',              'Bible\BibleFileSetsController@show');
Route::name('v4_filesets.update')->put('bibles/filesets/{fileset_id}',             'User\Dashboard\BibleFilesetsManagementController@update');
Route::name('v4_filesets.store')->post('bibles/filesets',             'User\Dashboard\BibleFilesetsManagementController@store');
Route::name('v4_filesets.books')->get('bibles/filesets/{fileset_id}/books',        'Bible\BooksController@show');

// VERSION 4 | Text
Route::name('v4_filesets.chapter')->get('bibles/filesets/{fileset_id}/{book}/{chapter}', 'Bible\TextController@index');
Route::name('v4_text_search')->get('search',                                             'Bible\TextController@search');

// VERSION 4 | Commentaries

Route::name('v4_commentary_index')->get('commentaries/',                                       'Bible\Study\CommentaryController@index');
Route::name('v4_commentary_chapters')->get('commentaries/{commentary_id}/chapters',            'Bible\Study\CommentaryController@chapters');
Route::name('v4_commentary_chapters')->get('commentaries/{commentary_id}/{book_id}/{chapter}', 'Bible\Study\CommentaryController@sections');

// VERSION 4 | Study Lexicons

Route::name('v4_lexicon_index')->get('lexicons',                                   'Bible\Study\LexiconController@index');

// VERSION 4 | Timestamps
Route::name('v4_timestamps')->get('timestamps',                                    'Bible\AudioController@availableTimestamps');
Route::name('v4_timestamps.tag')->get('timestamps/search',                        'Bible\AudioController@timestampsByTag');
Route::name('v4_timestamps.verse')->get('timestamps/{id}/{book}/{chapter}',        'Bible\AudioController@timestampsByReference');

// VERSION 4 | Countries
Route::name('v4_countries.all')->get('countries',                                  'Wiki\CountriesController@index');
Route::name('v4_countries.jsp')->get('countries/joshua-project/',                  'Wiki\CountriesController@joshuaProjectIndex');
Route::name('v4_countries.one')->get('countries/{country_id}',                     'Wiki\CountriesController@show');

// VERSION 4 | Languages
Route::name('v4_languages.all')->get('languages',                                  'Wiki\LanguagesController@index');
Route::name('v4_languages.one')->get('languages/{language_id}',                    'Wiki\LanguagesController@show');

// VERSION 4 | Alphabets
Route::name('v4_alphabets.all')->get('alphabets',                                  'Wiki\AlphabetsController@index');
Route::name('v4_alphabets.one')->get('alphabets/{alphabet_id}',                    'Wiki\AlphabetsController@show');
Route::name('v4_alphabets.store')->post('alphabets',                               'Wiki\AlphabetsController@store');
Route::name('v4_alphabets.update')->put('alphabets/{alphabet_id}',                 'Wiki\AlphabetsController@update');
Route::name('v4_numbers.all')->get('numbers/',                                     'Wiki\NumbersController@index');
Route::name('v4_numbers.range')->get('numbers/range',                              'Wiki\NumbersController@customRange');
Route::name('v4_numbers.one')->get('numbers/{number_id}',                          'Wiki\NumbersController@show');

// VERSION 4 | Users
Route::name('v4_user.index')->get('users',                                         'User\UsersController@index');
Route::name('v4_user.store')->post('users',                                        'User\UsersController@store');
Route::name('v4_user.show')->get('users/{user_id}',                                'User\UsersController@show');
Route::name('v4_user.update')->put('users/{user_id}',                              'User\UsersController@update');
Route::name('v4_user.destroy')->delete('users/{user_id}',                          'User\UsersController@destroy');
Route::name('v4_user.login')->post('/login',                                       'User\UsersController@login');
Route::name('v4_user.oAuth')->get('/login/{driver}',                               'User\SocialController@redirect');
Route::name('v4_user.oAuthCallback')->get('/login/{driver}/callback',              'User\SocialController@callback');
Route::name('v4_user.password_reset')->post('users/password/reset/{token?}',       'User\PasswordsController@validatePasswordReset');
Route::name('v4_user.password_email')->post('users/password/email',                'User\PasswordsController@triggerPasswordResetEmail');

// VERSION 4 | Annotations
Route::name('v4_notes.index')->get('users/{user_id}/notes',                        'User\NotesController@index');
Route::name('v4_notes.show')->get('users/{user_id}/notes/{id}',                    'User\NotesController@show');
Route::name('v4_notes.store')->post('users/{user_id}/notes',                       'User\NotesController@store');
Route::name('v4_notes.update')->put('users/{user_id}/notes/{id}',                  'User\NotesController@update');
Route::name('v4_notes.destroy')->delete('users/{user_id}/notes/{id}',              'User\NotesController@destroy');
Route::name('v4_bookmarks.index')->get('users/{user_id}/bookmarks',                'User\BookmarksController@index');
Route::name('v4_bookmarks.store')->post('users/{user_id}/bookmarks',               'User\BookmarksController@store');
Route::name('v4_bookmarks.update')->put('users/{user_id}/bookmarks/{id}',          'User\BookmarksController@update');
Route::name('v4_bookmarks.destroy')->delete('users/{user_id}/bookmarks/{id}',      'User\BookmarksController@destroy');
Route::name('v4_highlights.index')->get('users/{user_id}/highlights',              'User\HighlightsController@index');
Route::name('v4_highlights.store')->post('users/{user_id}/highlights',             'User\HighlightsController@store');
Route::name('v4_highlights.update')->put('users/{user_id}/highlights/{id}',        'User\HighlightsController@update');
Route::name('v4_highlights.destroy')->delete('users/{user_id}/highlights/{id}',    'User\HighlightsController@destroy');

// VERSION 4 | User Settings
Route::name('v4_UserSettings.show')->get('users/{user_id}/settings',               'User\UserSettingsController@show');
Route::name('v4_UserSettings.store')->post('users/{user_id}/settings',             'User\UserSettingsController@store');

// VERSION 4 | Community
Route::name('v4_articles.index')->get('articles',                                  'User\ArticlesController@index');
Route::name('v4_articles.show')->get('articles/{id}',                              'User\ArticlesController@show');
Route::name('v4_articles.update')->put('articles/{id}',                            'User\ArticlesController@update');
Route::name('v4_articles.store')->post('articles',                                 'User\ArticlesController@store');
Route::name('v4_articles.destroy')->delete('articles/{id}',                        'User\ArticlesController@destroy');
Route::name('v4_organizations.compare')->get('organizations/compare/',             'Organization\OrganizationsController@compare');
Route::name('v4_organizations.one')->get('organizations/{organization_id}',        'Organization\OrganizationsController@show');
Route::name('v4_organizations.all')->get('organizations/',                         'Organization\OrganizationsController@index');
Route::name('v4_projects.index')->get('projects',                                  'Organization\ProjectsController@index');
Route::name('v4_projects.show')->get('projects/{project_id}',                      'Organization\ProjectsController@show');
Route::name('v4_projects.update')->put('projects/{project_id}',                    'Organization\ProjectsController@update');
Route::name('v4_projects.store')->post('projects',                                 'Organization\ProjectsController@store');
Route::name('v4_projects.destroy')->delete('projects/{project_id}',                'Organization\ProjectsController@destroy');
Route::name('v4_oAuth.index')->get('projects/{project_id}/oauth/',                 'Organization\OAuthProvidersController@index');
Route::name('v4_oAuth.show')->get('projects/{project_id}/oauth/{id}',              'Organization\OAuthProvidersController@show');
Route::name('v4_oAuth.update')->put('projects/{project_id}/oauth/{id}',            'Organization\OAuthProvidersController@update');
Route::name('v4_oAuth.store')->post('projects/{project_id}/oauth',                 'Organization\OAuthProvidersController@store');
Route::name('v4_oAuth.destroy')->delete('projects/{project_id}/oauth/{id}',        'Organization\OAuthProvidersController@destroy');

// VERSION 4 | Resources
Route::name('v4_resources.index')->get('resources',                                'Organization\ResourcesController@index');
Route::name('v4_resources.show')->get('resources/{resource_id}',                   'Organization\ResourcesController@show');
Route::name('v4_resources.update')->put('resources/{resource_id}',                 'Organization\ResourcesController@update');
Route::name('v4_resources.store')->post('resources',                               'Organization\ResourcesController@store');
Route::name('v4_resources.destroy')->delete('resources/{resource_id}',             'Organization\ResourcesController@destroy');

Route::name('v4_video_jesus_film_languages')->get('arclight/jesus-film/languages', 'Bible\VideoStreamController@jesusFilmsLanguages');
Route::name('v4_video_jesus_film_language')->get('arclight/jesus-film/chapters',   'Bible\VideoStreamController@jesusFilmChapters');
Route::name('v4_video_jesus_film_language')->get('arclight/jesus-film',            'Bible\VideoStreamController@jesusFilmFile');

// VERSION 4 | API METADATA
Route::name('v4_api.versions')->get('/api/versions',                               'HomeController@versions');
Route::name('v4_api.buckets')->get('/api/buckets',                                 'HomeController@buckets');
Route::name('v4_api.stats')->get('/stats',                                         'HomeController@stats');
Route::name('v4_api.gitVersion')->get('/api/git/version',                          'ApiMetadataController@gitVersion');
Route::name('v4_api.refreshDevCache')->get('/api/refresh-dev-cache',               'ApiMetadataController@refreshDevCache');

Route::name('v4_api.changes')->get('/api/changelog',                               'ApiMetadataController@changelog');

// VERSION 4 | GENERATOR
Route::name('v4_api.generator')->get('/api/gen/bibles',                            'Connections\GeneratorController@bibles');