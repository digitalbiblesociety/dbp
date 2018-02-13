<?php

namespace Tests\Feature;

use App\Http\Controllers\ProjectsController;
use App\Models\User\Project;
use App\Models\User\User;
use Tests\TestCase;

use Illuminate\Http\UploadedFile;

class API_V4_Test extends TestCase
{

	protected $params;
	protected $swagger;
	protected $schemas;

	/**
	 * API_V2_Test constructor
	 *
	 *
	 */
	function setUp() {
		parent::setUp();
		$user = User::inRandomOrder()->first();
		$this->params = ['v' => 4,'key' => 'e8a946a0-d9e2-11e7-bfa7-b1fb2d7f5824','pretty'];

		// Fetch the Swagger Docs for Structure Validation
		$arrContextOptions= [ "ssl" => ["verify_peer"=>false, "verify_peer_name"=>false]];
		$swagger_url = env('APP_URL').'/swagger_v4.json';
		$this->swagger = json_decode(file_get_contents($swagger_url, false, stream_context_create($arrContextOptions)), true);
		$this->schemas = $this->swagger['components']['schemas'];
	}

	public function getSchemaKeys($schema)
	{
		return array_keys($this->schemas[$schema]['properties']);
	}

	/*
	Route::name('v4_bible_filesets.index')->get('bibles/filesets/{ id }',                 'BibleFilesSetsController@show');
	Route::post('bibles/filesets/{id}/files/{file_id}',    'BibleFilesController@update');
	Route::resource('bibles/filesets/{id}/permissions',    'BibleFileSetPermissionsController', ['names' => [
		'v4_bible_filesets.permissions_index'   => 'v4_bible_filesets_permissions.index',
		'v4_bible_filesets.permissions_edit'    => 'v4_bible_filesets_permissions.edit',
		'v4_bible_filesets.permissions_create'  => 'v4_bible_filesets_permissions.create',
		'v4_bible_filesets.permissions_store'   => 'v4_bible_filesets_permissions.store',
		'v4_bible_filesets.permissions_show'    => 'v4_bible_filesets_permissions.show',
		'v4_bible_filesets.permissions_update'  => 'v4_bible_filesets_permissions.update'
	]]);
	Route::get('bibles/filesets/{id}/download', 'BibleFileSetsController@download')->name('v4_bible_filesets.download');
	Route::resource('bibles/filesets',           'BibleFileSetsController', ['names' => [
		'v4_bible_filesets.index'   => 'view_bible_filesets.index',
		'v4_bible_filesets.edit'    => 'view_bible_filesets.edit',
		'v4_bible_filesets.create'  => 'view_bible_filesets.create',
		'v4_bible_filesets.show'    => 'view_bible_filesets.show',
	]]);
	Route::name('v4_bible.all')->get('bibles',                                            'BiblesController@index');
	Route::name('v4_bible.one')->get('bibles/{id}',                                       'BiblesController@show');
	Route::name('v4_bible.books')->get('bible/{id}/book/{book}',                          'BiblesController@books');
	Route::name('v4_bible.chapter')->get('bible/{id}/{book}/{chapter}',                   'TextController@index');
	Route::name('v4_bible_books')->get('bibles/books/',                                   'BooksController@index');
	Route::name('v4_timestamps')->get('timestamps',                                       'AudioController@availableTimestamps');
	Route::name('v4_timestamps.tag')->get('timestamps/{id}',                              'AudioController@timestampsByTag');
	Route::name('v4_timestamps.verse')->get('timestamps/{id}/{book}/{chapter}',           'AudioController@timestampsByReference');
	Route::name('v4_countries.all')->get('countries',                                     'CountriesController@index');
	Route::name('v4_countries.one')->get('countries/{id}',                                'CountriesController@show');
	Route::name('v4_languages.all')->get('languages',                                     'LanguagesController@index');
	Route::name('v4_languages.one')->get('languages/{id}',                                'LanguagesController@show');
	Route::name('v4_alphabets.all')->get('alphabets',                                     'AlphabetsController@index');
	Route::name('v4_alphabets.one')->get('alphabets/{id}',                                'AlphabetsController@show');
	Route::name('v4_numbers.range')->get('numbers/range',                                 'NumbersController@customRange');
	Route::name('v4_numbers.all')->get('numbers/',                                        'NumbersController@index');
	Route::name('v4_numbers.one')->get('numbers/{id}',                                    'NumbersController@show');
	Route::name('v4_user.index')->get('users',                                            'UsersController@index');
	Route::name('v4_user.create')->post('users',                                          'UsersController@store');
	Route::name('v4_user.update')->put('users/{user_id}',                                 'UsersController@update');
	Route::name('v4_user.destroy')->delete('users/{user_id}',                             'UsersController@destroy');
	Route::name('v4_user.login')->post('users/login',                                     'UsersController@login');
	Route::name('v4_user.oAuth')->get('users/login/{driver}',                             'Auth\LoginController@redirectToProvider');
	Route::name('v4_user.oAuthCallback')->get('users/login/{driver}/callback',            'Auth\LoginController@handleProviderCallback');

	Route::name('v4_api.versions')->get('/api/versions',                                  'HomeController@versions');
	Route::name('v4_api.versionLatest')->get('/api/versions/latest',                      'HomeController@versionLatest');
	Route::name('v4_api.replyTypes')->get('/api/versions/replyTypes',                     'HomeController@versionReplyTypes');
	Route::name('v4_api.sign')->get('sign',                                               'HomeController@signedUrls');
	 */

	// =========================================================================
	// USER NOTES v4
	// =========================================================================




	// =========================================================================
	// ORGANIZATIONS v4
	// =========================================================================



	// =========================================================================
	// PROJECTS v4
	// =========================================================================


}
