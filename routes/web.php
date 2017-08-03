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

Route::get('bible/{id}/{book}/{chapter}',   'BiblesController@text');
Route::get('bibles/audio/uploads/thanks',   'AudioProcessingController@thanks')->name('bibles_audio_uploads.thanks');
Route::resource('bibles/audio/uploads',     'AudioProcessingController');
Route::resource('bibles/ocr',               'PrintProcesses');
Route::resource('bibles',                   'BiblesController');

Route::get('docs',                          'DocsController@index')->name('docs');
Route::get('docs/team',                     'DocsController@team')->name('docs_team');
Route::get('docs/bibles',                   'DocsController@bibles')->name('docs_bibles');
Route::get('docs/bibles/equivalents',       'DocsController@bibleEquivalents')->name('docs_bible_equivalents');
Route::get('docs/bibles/books',             'DocsController@books')->name('docs_bible_books');
Route::get('docs/languages',                'DocsController@languages')->name('docs_languages');
Route::get('docs/language/create',          'DocsController@languages')->name('docs_language_create');
Route::get('docs/language/update',          'DocsController@languages')->name('docs_language_update');
Route::get('docs/countries',                'DocsController@countries')->name('docs_countries');
Route::get('docs/alphabets',                'DocsController@alphabets')->name('docs_alphabets');


Route::resource('books',                    'BooksController',['only'=>['index','show','edit','update']]);
Route::resource('languages',                'LanguagesController');
Route::resource('alphabets',                'AlphabetsController');
Route::resource('countries',                'CountriesController',['only'=>['index','show']]);

Route::get('login/{provider}',          'Auth\LoginController@redirectToProvider')->name('login.social_redirect');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('login.social_callback');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/',     'HomeController@welcome')->name('welcome');
Route::get('server-info', function () { phpinfo(); });
