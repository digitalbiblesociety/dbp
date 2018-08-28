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
Route::get('/about/privacy-policy',                'WelcomeController@privacy_policy')->name('privacy_policy');
Route::get('/acerca-de/politica-de-privacidad',    'WelcomeController@privacy_policy')->name('es.privacy_policy')->localization('es');

Route::get('/about/contact',         'User\UserContactController@create')->name('contact.create');
Route::post('/about/contact',        'User\UserContactController@store')->name('contact.store');

// About
Route::get('/about/relations',         'WelcomeController@relations')->name('relations');

// Authentication Routes | Passwords
Auth::routes();

Route::group(['prefix' => 'verification','as' => 'laravel2step::','middleware' => ['web']], function() {

	Route::name('verificationNeeded')->get('/needed',           'User\UserTwoStepController@showVerification');
	Route::name('verify')->post('/verify',                      'User\UserTwoStepController@verify');
	Route::name('resend')->post('/resend',                      'User\UserTwoStepController@resend');

});

// Public Routes
Route::group(['middleware' => ['web', 'activity']], function () {

	Route::name('notes')->get('notes',                                       'User\UserNotesController@index');

	// Docs Routes
	Route::name('docs')->get('docs',                                         'User\DocsController@index');
	Route::name('swagger_v4')->get('docs/swagger/v4',                        'User\DocsController@swagger_v4');
	Route::name('swagger_v2')->get('docs/swagger/v2',                        'User\DocsController@swagger_v2');
	Route::name('history')->get('docs/history',                              'User\DocsController@history');
	Route::name('docs.sdk')->get('docs/sdk',                                 'User\DocsController@sdk');
	Route::name('docs_team')->get('docs/getting-started',                    'User\DocsController@getting_started');
	Route::name('docs_team')->get('docs/team',                               'User\DocsController@team');
	Route::name('docs_bible_equivalents')->get('docs/bibles/equivalents',    'User\DocsController@bibleEquivalents');
	Route::name('docs_bible_books')->get('docs/bibles/books',                'User\DocsController@books');
	Route::name('docs_bibles')->get('docs/bibles',                           'User\DocsController@bibles');
	Route::name('docs_language_create')->get('docs/language/create',         'User\DocsController@languages');
	Route::name('docs_language_update')->get('docs/language/update',         'User\DocsController@languages');
	Route::name('docs_languages')->get('docs/languages',                     'User\DocsController@languages');
	Route::name('docs_countries')->get('docs/countries',                     'User\DocsController@countries');
	Route::name('docs_alphabets')->get('docs/alphabets',                     'User\DocsController@alphabets');
	Route::name('docs_analysis')->get('docs/code-analysis',                  'User\DocsController@code_analysis');

	// Docs Generator Routes
	Route::name('swagger_docs_gen')->get('swagger_docs',                     'User\DocsController@swagger_docs_gen');
	Route::name('swagger_database')->get('docs/swagger/database',            'User\DocsController@swagger_database');
	Route::name('swagger_database_model')->get('docs/swagger/database/{id}', 'User\DocsController@swagger_database_model');

    // Activation Routes
    Route::name('activate')->get('/activate',                           'Auth\ActivateController@initial');
    Route::name('authenticated.activate')->get('/activate/{token}',     'Auth\ActivateController@activate');
    Route::name('authenticated.activation-resend')->get('/activation',  'Auth\ActivateController@resend');
    Route::name('exceeded')->get('/exceeded',                           'Auth\ActivateController@exceeded');

    // Socialite Register Routes
    Route::name('social.redirect')->get('/social/redirect/{provider}',  'Auth\SocialController@getSocialRedirect');
    Route::name('social.handle')->get('/social/handle/{provider}',      'Auth\SocialController@getSocialHandle');
    Route::name('user.reactivate')->get('/re-activate/{token}',         'User\Dashboard\RestoreUserController@userReActivate');    // Route to for user to reactivate their user deleted account.
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity']], function () {
    Route::name('activation-required')->get('/activation-required',     'Auth\ActivateController@activationRequired');
    Route::name('logout')->get('/logout',                               'Auth\LoginController@logout');

    Route::name('public.home')->get('/home',                           'User\UserController@index');       //  Homepage Route - Redirect based on user role is in controller.
    Route::name('profiles.show')->get('profile/{username}',            'User\Dashboard\ProfilesController@show');    // Show users profile - viewable by other users.
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'activity']], function () {

    // User Profile and Account Routes
    Route::resource('profile', 'User\Dashboard\ProfilesController', ['only' => ['show', 'edit', 'update', 'create']]);
    Route::name('{username}')->put('profile/{username}/updateUserAccount', 'User\Dashboard\ProfilesController@updateUserAccount');
    Route::name('{username}')->put('profile/{username}/updateUserPassword', 'User\Dashboard\ProfilesController@updateUserPassword');
    Route::name('{username}')->delete('profile/{username}/deleteUserAccount', 'User\Dashboard\ProfilesController@deleteUserAccount');
    Route::get('images/profile/{id}/avatar/{image}', 'User\Dashboard\ProfilesController@userProfileAvatar');   // Route to show user avatar
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'User\Dashboard\ProfilesController@upload']); // Route to upload user avatar.
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'role:admin', 'activity']], function () {

	// Dashboards
	Route::get('/activity',                         'User\Dashboard\LaravelLoggerController@showAccessLog')->name('activity');
	Route::get('/activity/cleared',                 'User\Dashboard\LaravelLoggerController@showClearedActivityLog')->name('cleared');
	Route::get('/activity/log/{id}',                'User\Dashboard\LaravelLoggerController@showAccessLogEntry');
	Route::get('/activity/cleared/log/{id}',        'User\Dashboard\LaravelLoggerController@showClearedAccessLogEntry');
	Route::delete('/activity/clear-activity',       'User\Dashboard\LaravelLoggerController@clearActivityLog')->name('clear-activity');
	Route::delete('/activity/destroy-activity',     'User\Dashboard\LaravelLoggerController@destroyActivityLog')->name('destroy-activity');
	Route::post('/activity/restore-log',            'User\Dashboard\LaravelLoggerController@restoreClearedActivityLog')->name('restore-activity');


	Route::get('/php-info',           'User\Dashboard\AdminDetailsController@phpinfo')->name('phpinfo');
	Route::get('/messages',           'User\UserContactController@index')->name('messages.index');
    Route::resource('/users/deleted', 'User\Dashboard\SoftDeletesController', ['only' => ['index', 'show', 'update', 'destroy']]);

	Route::resource('bibles', 'Bible\BiblesManagementController', [
		'names' => [
			'index'   => 'dashboard.bibles',
			'create'  => 'dashboard.bibles.create',
			'store'   => 'dashboard.bibles.store',
			'delete'  => 'dashboard.bible.delete',
		],
		'except' => [
			'deleted',
		],
	]);

    Route::resource('users', 'User\Dashboard\UsersManagementController', [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('search-users', 'User\Dashboard\UsersManagementController@search')->name('search-users');

    Route::get('logs/{log?}', 'User\Dashboard\LogViewerController@index');
    Route::get('routes', 'User\Dashboard\AdminDetailsController@listRoutes');
    Route::get('active-users', 'User\Dashboard\AdminDetailsController@activeUsers');
});

});