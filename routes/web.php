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

Route::get('bible/{id}/{book}/{chapter}',   'v4\BiblesController@text');
Route::resource('bibles',                   'v4\BiblesController');

Route::get('docs',                          'v4\DocsController@index')->name('v4_docs');
Route::get('docs/bibles',                   'v4\DocsController@bibles')->name('v4_docs_bibles');
Route::get('docs/bibles/books',             'v4\DocsController@books')->name('v4_docs_books');

Route::resource('books',                    'v4\BooksController',['only'=>['index','show','edit','update']]);
Route::resource('languages',                'v4\LanguagesController');
Route::resource('alphabets',                'v4\AlphabetsController');
Route::resource('countries',                'v4\CountriesController',['only'=>['index','show']]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/',     'HomeController@welcome')->name('welcome');
