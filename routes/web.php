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
	Route::name('docs')->get('docs',                                        'DocsController@index');
	Route::name('history')->get('docs/history',                             'DocsController@history');
	Route::name('docs.sdk')->get('docs/sdk',                                'DocsController@sdk');
	Route::name('swagger_v2')->get('docs/swagger/v2',                       'DocsController@swagger_v2');
	Route::name('swagger_v4')->get('docs/swagger/v4',                       'DocsController@swagger_v4');
	Route::name('swagger_v4')->get('docs/swagger/gen',                      'DocsController@swagger_docs');
	Route::name('swagger_docs_gen')->get('swagger_docs',                    'DocsController@swagger_docs_gen');
	Route::name('swagger_database')->get('docs/swagger/database',           'DocsController@swagger_database');
	Route::name('swagger_database_model')->get('docs/swagger/database/{id}','DocsController@swagger_database_model');
	Route::name('docs_team')->get('docs/team',                              'DocsController@team');
	Route::name('docs_bibles')->get('docs/bibles',                          'DocsController@bibles');
	Route::name('docs_bible_equivalents')->get('docs/bibles/equivalents',   'DocsController@bibleEquivalents');
	Route::name('docs_bible_books')->get('docs/bibles/books',               'DocsController@books');
	Route::name('docs_languages')->get('docs/languages',                    'DocsController@languages');
	Route::name('docs_language_create')->get('docs/language/create',        'DocsController@languages');
	Route::name('docs_language_update')->get('docs/language/update',        'DocsController@languages');
	Route::name('docs_countries')->get('docs/countries',                    'DocsController@countries');
	Route::name('docs_alphabets')->get('docs/alphabets',                    'DocsController@alphabets');
	Route::name('docs_books_BookOrderListing')->get('docs/v2/books/book-order-listing',  'BooksController@show');
	Route::name('v2_docs_text_search')->get('docs/v2/text/search',  'TextController@search');
	Route::name('data_library_volumeLanguage')->get('library/volumelanguage', 'LanguagesController@volumeLanguage');

});


Route::get('bibles/audio/uploads/thanks',   'AudioProcessingController@thanks')->name('bibles_audio_uploads.thanks');
Route::resource('bibles/audio/uploads',     'AudioProcessingController');
Route::resource('bibles/ocr',               'PrintProcesses');
Route::resource('dbl',                      'Connections\DigitalBibleLibraryController');

// This Collection of routes handles the javascript-less reader fallback

Route::name('ui_bibleDisplay_read.index')->get('/read/',                          'BibleDisplayController@index');
Route::name('ui_bibleDisplay_read.bible')->get('/read/{id}',                      'BibleDisplayController@navigation');
Route::name('ui_bibleDisplay_read.results')->get('/read/{id}/search',             'BibleDisplayController@search');
Route::name('ui_bibleDisplay_read.search')->post('/read/{id}/search',             'BibleDisplayController@search');
Route::name('ui_bibleDisplay_read.chapter')->get('/read/{id}/{book}/{chapter}',   'BibleDisplayController@chapter');


Route::get('/permissions',       'BibleFileSetPermissionsController@user')->name('view_bible_filesets_permissions.user');
Route::resource('bibles/filesets/{id}/permissions',       'BibleFileSetPermissionsController', ['names' => [
	'index'   => 'view_bible_filesets_permissions.index',
	'edit'    => 'view_bible_filesets_permissions.edit',
	'create'  => 'view_bible_filesets_permissions.create',
	'store'   => 'view_bible_filesets_permissions.store',
	'show'    => 'view_bible_filesets_permissions.show',
	'update'  => 'view_bible_filesets_permissions.update'
]]);
Route::resource('bibles/files',       'BibleFileSetsController', ['names' => [
	'index'   => 'view_bible_filesets.index',
	'edit'    => 'view_bible_filesets.edit',
	'create'  => 'view_bible_filesets.create',
	'show'    => 'view_bible_filesets.show',
]]);

Route::get('bibles/{id}/manage',            'BiblesController@manage')->name('view_bibles.manage');
Route::resource('bibles',                   'BiblesController', ['names' => [
	'index'   => 'view_bibles.index',
	'edit'    => 'view_bibles.edit',
	'create'  => 'view_bibles.create',
	'update'  => 'view_bibles.update',
	'store'   => 'view_bibles.store',
	'show'    => 'view_bibles.show',
]]);
Route::resource('articles',                   'ArticlesController', ['names' => [
	'index'   => 'view_articles.index',
	'edit'    => 'view_articles.edit',
	'create'  => 'view_articles.create',
	'update'  => 'view_articles.update',
	'store'   => 'view_articles.store',
	'show'    => 'view_articles.show',
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
	'update'  => 'view_alphabets.update',
]]);
Route::resource('resources',                'ResourcesController', ['names' => [
	'index'   => 'view_resources.index',
	'edit'    => 'view_resources.edit',
	'create'  => 'view_resources.create',
	'show'    => 'view_resources.show',
]]);
Route::resource('organizations', 'OrganizationsController',['names' => [
	'index'   => 'view_organizations.index',
	'edit'    => 'view_organizations.edit',
	'create'  => 'view_organizations.create',
	'show'    => 'view_organizations.show',
]]);

Route::resource('countries',                'CountriesController', [
	'only' => ['index','show'],
	'names'=> ['index' => 'view_countries.index','show'  => 'view_countries.show']
]);

Route::get('login/{provider}',          'Auth\LoginController@redirectToProvider')->name('login.social_redirect');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('login.social_callback');
Auth::routes();


Route::get('dashboard', 'HomeController@index')->name('home');
Route::get('admin', 'HomeController@admin')->name('admin');
Route::resource('dashboard/users',    'UsersController');
Route::resource('dashboard/projects', 'ProjectsController');

Route::get('/verify-email/{token}', 'Auth\LoginController@verify');
Route::get('/dashboard/users/notes', 'UserNotesController@index')->name('users.notes_index');

// Organizations Dashboard
Route::resource('dashboard/organizations/roles',       'Dashboard\Organizations\OrganizationRolesController', ['names' => [
	'index'   => 'dashboard_organization_roles.index',
	'edit'    => 'dashboard_organization_roles.edit',
	'create'  => 'dashboard_organization_roles.create',
	'show'    => 'dashboard_organization_roles.show',
]]);
Route::resource('dashboard/organizations', 'OrganizationsController', ['names' => [
	'index'   => 'dashboard_organizations.index',
	'edit'    => 'dashboard_organizations.edit',
	'create'  => 'dashboard_organizations.create',
	'show'    => 'dashboard_organizations.show',
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