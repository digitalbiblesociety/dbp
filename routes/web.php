<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/


Localization::localizedRoutesGroup(function() {

// Homepage Route
Route::get('/',                      'WelcomeController@welcome')->name('welcome');

// Overview Page
Route::get('/about/',                'WelcomeController@overview')->name('overview');

// Legal Overview
Route::get('/about/legal',           'WelcomeController@legal')->name('legal');
Route::get('/acerca-de/legal',       'WelcomeController@legal')->name('es.legal')->localization('es');

// Legal | Eula Page
Route::get('/about/eula',            'WelcomeController@eula')->name('eula');
Route::get('/acerca-de/eula',        'WelcomeController@eula')->name('es.eula')->localization('es');

// Legal | License Page
Route::get('/about/license',         'WelcomeController@license')->name('license');
Route::get('/acerca-de/licencia',    'WelcomeController@license')->name('es.license')->localization('es');

// Legal | Privacy Policy
Route::get('/about/privacy-policy',                'WelcomeController@privacyPolicy')->name('privacy_policy');
Route::get('/acerca-de/politica-de-privacidad',    'WelcomeController@privacyPolicy')->name('es.privacy_policy')->localization('es');

Route::get('/about/contact',         'User\UserContactController@create')->name('contact.create');
Route::post('/about/contact',        'User\UserContactController@store')->name('contact.store');

// About
Route::get('/about/relations',         'WelcomeController@relations')->name('relations');
Route::get('/organizations',           'Organization\OrganizationsController@index')->name('organizations.index');

// About | Joining
Route::get('/about/join',               'WelcomeController@join')->name('about.join');
Route::get('/about/partnering',         'WelcomeController@partnering')->name('about.partnering');

// Reader
Route::get('/reader',                                   'Bible\ReaderController@languages')->name('reader.languages');
Route::get('/reader/languages/{language_id}',           'Bible\ReaderController@bibles')->name('reader.bibles');
Route::get('/reader/bibles/{id}/',                      'Bible\ReaderController@books')->name('reader.books');
Route::get('/reader/bibles/{id}/{book}/{chapter}',      'Bible\ReaderController@chapter')->name('reader.chapter');

// Authentication Routes | Passwords
Route::name('login')->match(['get','post'],'login',             'User\UsersController@login');
Route::name('logout')->post('logout',                           'User\UsersController@logout');
Route::name('register')->get('register',                        'User\UsersController@create');
Route::post('register',                                         'User\UsersController@store');
Route::name('password.request')->get('password/reset',          'User\UserPasswordsController@showRequestForm');
Route::name('password.email')->post('password/email',           'User\UserPasswordsController@triggerPasswordResetEmail');
Route::name('password.reset')->get('password/reset/{token}',    'User\UserPasswordsController@showResetForm');
Route::post('password/reset',                                   'User\UserPasswordsController@reset');


Route::name('wiki_bibles.one')->get('/wiki/bibles/{id}',              'Bible\BiblesController@show');
Route::name('wiki_bibles.all')->get('/wiki/bibles',                   'Bible\BiblesController@index');

Route::group(['prefix' => 'verification','as' => 'laravel2step::','middleware' => ['web']], function() {

	Route::name('verificationNeeded')->get('/needed',           'User\UserTwoStepController@showVerification');
	Route::name('verify')->post('/verify',                      'User\UserTwoStepController@verify');
	Route::name('resend')->post('/resend',                      'User\UserTwoStepController@resend');

});

// Public Routes
Route::group(['middleware' => ['web', 'activity']], function () {

	Route::name('notes')->get('notes',                                       'User\UserNotesController@index');

	// Getting Started
	Route::name('apiDocs_bible_equivalents')->get('/api/bible/bible-equivalents', 'Bible\BibleEquivalentsController@index');


	// Docs Routes
	Route::name('docs')->get('docs',                                         'User\DocsController@index');
	Route::name('swagger_v4')->get('docs/swagger/v4',                        'User\DocsController@swaggerV4');
	Route::name('swagger_v2')->get('docs/swagger/v2',                        'User\DocsController@swaggerV2');
	Route::name('history')->get('docs/history',                              'User\DocsController@history');
	Route::name('docs.sdk')->get('sdk',                                      'User\DocsController@sdk');
	Route::name('docs.getting_started')->get('guides/getting-started',       'User\DocsController@start');
	Route::name('docs_team')->get('docs/team',                               'User\DocsController@team');
	Route::name('docs_bible_equivalents')->get('docs/bibles/equivalents',    'User\DocsController@bibleEquivalents');
	Route::name('docs_bible_books')->get('docs/bibles/books',                'User\DocsController@books');
	Route::name('docs_bibles')->get('docs/bibles',                           'User\DocsController@bibles');
	Route::name('docs_language_create')->get('docs/language/create',         'User\DocsController@languages');
	Route::name('docs_language_update')->get('docs/language/update',         'User\DocsController@languages');
	Route::name('docs_languages')->get('docs/languages',                     'User\DocsController@languages');
	Route::name('docs_countries')->get('docs/countries',                     'User\DocsController@countries');
	Route::name('docs_alphabets')->get('docs/alphabets',                     'User\DocsController@alphabets');
	Route::name('docs_analysis')->get('docs/code-analysis',                  'User\DocsController@codeAnalysis');

	// Docs Generator Routes
	Route::name('swagger_docs_gen')->get('swagger_docs',                     'User\DocsController@swaggerDocsGen');
	Route::name('swagger_database')->get('docs/swagger/database',            'User\DocsController@swaggerDatabase');
	Route::name('swagger_database_model')->get('docs/swagger/database/{id}', 'User\DocsController@swaggerDatabase_model');

    // Activation Routes
	Route::name('projects.connect')->get('/connect/{token}',            'Organization\ProjectsController@connect');

    // Socialite Register Routes
    Route::name('social.redirect')->get('/login/redirect/{provider}',   'User\UserSocialController@getSocialRedirect');
    Route::name('social.handle')->get('/login/{provider}/callback',     'User\UserSocialController@handleProviderCallback');
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activity']], function () {
    Route::name('public.home')->get('/home',                           'User\Dashboard\DashboardController@home');       //  Homepage Route - Redirect based on user role is in controller.
});

});

// VERSION 4 | DEPLOYMENT
Route::name('deployments.github')->post('/deploy/github',                              'Connections\GitDeployController@deploy');