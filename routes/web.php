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
*/

Route::group(['prefix' => i18n::setLocale(), 'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function()
{

	Route::get('docs',                          'DocsController@index')->name('docs');
	Route::get('docs/history',                  'DocsController@history')->name('history');
	Route::get('docs/swagger',                  'DocsController@swagger')->name('swagger');
	Route::get('docs/progress',                 'DocsController@progress')->name('docs_progress');
	Route::get('docs/team',                     'DocsController@team')->name('docs_team');
	Route::get('docs/bibles',                   'DocsController@bibles')->name('docs_bibles');
	Route::get('docs/bibles/equivalents',       'DocsController@bibleEquivalents')->name('docs_bible_equivalents');
	Route::get('docs/bibles/books',             'DocsController@books')->name('docs_bible_books');
	Route::get('docs/languages',                'DocsController@languages')->name('docs_languages');
	Route::get('docs/language/create',          'DocsController@languages')->name('docs_language_create');
	Route::get('docs/language/update',          'DocsController@languages')->name('docs_language_update');
	Route::get('docs/countries',                'DocsController@countries')->name('docs_countries');
	Route::get('docs/alphabets',                'DocsController@alphabets')->name('docs_alphabets');

	Route::get('docs/v2/books/book-order-listing',  'BooksController@show')->name('docs_books_BookOrderListing');
	Route::get('docs/v2/text/search',  'TextController@search')->name('v2_docs_text_search');
	// library/volumelanguage

	Route::get('library/volumelanguage', 'LanguagesController@volumeLanguage')->name('data_library_volumeLanguage');

});

Route::get('bible/{id}/{book}/{chapter}',   'BiblesController@text');
Route::get('bibles/audio/uploads/thanks',   'AudioProcessingController@thanks')->name('bibles_audio_uploads.thanks');
Route::resource('bibles/audio/uploads',     'AudioProcessingController');
Route::resource('bibles/ocr',               'PrintProcesses');
Route::resource('bibles/connections',       'BibleConnectionsController', ['names' => [
	'index'   => 'view_bible_connections.index',
	'edit'    => 'view_bible_connections.edit',
	'create'  => 'view_bible_connections.create',
	'show'    => 'view_bible_connections.show',
]]);
Route::resource('bibles',                   'BiblesController', ['names' => [
	'index'   => 'view_bibles.index',
	'edit'    => 'view_bibles.edit',
	'create'  => 'view_bibles.create',
	'show'    => 'view_bibles.show',
]]);

Route::resource('books',                    'BooksController', ['names' => [
	'index'   => 'view_books.index',
	'edit'    => 'view_books.edit',
	'create'  => 'view_books.create',
	'show'    => 'view_books.show',
]]);
Route::resource('languages',                'LanguagesController', ['names' => [
	'index'   => 'view_languages.index',
	'edit'    => 'view_languages.edit',
	'create'  => 'view_languages.create',
	'show'    => 'view_languages.show',
]]);
Route::resource('numbers',                  'NumbersController', ['names' => [
	'index'   => 'view_numbers.index',
	'edit'    => 'view_numbers.edit',
	'create'  => 'view_numbers.create',
	'show'    => 'view_numbers.show',
]]);
Route::resource('alphabets',                'AlphabetsController', ['names' => [
	'index'   => 'view_alphabets.index',
	'edit'    => 'view_alphabets.edit',
	'create'  => 'view_alphabets.create',
	'show'    => 'view_alphabets.show',
]]);
Route::resource('resources',                'ResourcesController', ['names' => [
	'index'   => 'view_resources.index',
	'edit'    => 'view_resources.edit',
	'create'  => 'view_resources.create',
	'show'    => 'view_resources.show',
]]);
Route::resource('countries',                'CountriesController',['only'=>['index','show']]);

Route::get('login/{provider}',          'Auth\LoginController@redirectToProvider')->name('login.social_redirect');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('login.social_callback');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// Organizations Dashboard
Route::resource('home/organizations/roles',       'Dashboard\Organizations\OrganizationRolesController', ['names' => [
	'index'   => 'dashboard_organization_roles.index',
	'edit'    => 'dashboard_organization_roles.edit',
	'create'  => 'dashboard_organization_roles.create',
	'show'    => 'dashboard_organization_roles.show',
]]);
Route::resource('/home/organizations', 'OrganizationsController', ['names' => [
	'index'   => 'dashboard_organizations.index',
	'edit'    => 'dashboard_organizations.edit',
	'create'  => 'dashboard_organizations.create',
	'show'    => 'dashboard_organizations.show',
]]);

// User Dashboard
Route::resource('/home/organizations/{organization_id}/users', 'Dashboard\Organizations\UsersController',['names' => [
	'index'   => 'dashboard_organizations_users.index',
	'edit'    => 'dashboard_organizations_users.edit',
	'create'  => 'dashboard_organizations_users.create',
	'show'    => 'dashboard_organizations_users.show',
]]);

// Organizations Resources Dashboard
Route::resource('/home/organizations/{organization_id}/resources', 'Dashboard\Organizations\ResourcesController',['names' => [
	'index'   => 'dashboard_organizations_resources.index',
	'edit'    => 'dashboard_organizations_resources.edit',
	'create'  => 'dashboard_organizations_resources.create',
	'show'    => 'dashboard_organizations_resources.show',
]]);

Route::resource('/home/organizations/{organization_id}/resources', 'Dashboard\Organizations\ResourcesController',['names' => [
	'index'   => 'dashboard_organizations_bibles.index',
	'edit'    => 'dashboard_organizations_bibles.edit',
	'create'  => 'dashboard_organizations_bibles.create',
	'show'    => 'dashboard_organizations_bibles.show',
]]);

Route::get('/',     'HomeController@welcome')->name('welcome');



Route::get('/test-armor', function () {
	$locations = ['/bin','/home','/etc','/home/forge/aaTrap/'];
	foreach ( $locations as $location ) {
		echo "\nAttempting: ".$location;
		@file_put_contents("$location/armorTest.txt","Hi, \nI'm a test for app Armor");
		if(file_exists("$location/armorTest.txt")) {
			echo "\n File Successfully created";
		} else {
			echo "\n File was not created";
		}
	}
});