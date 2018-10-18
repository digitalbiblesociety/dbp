<?php

namespace Tests\Feature;

use Tests\TestCase;
class API_V4_Test extends TestCase
{

	protected $params;
	protected $swagger;
	protected $schemas;
	protected $key;

	/**
	 * API_V2_Test constructor
	 *
	 *
	 */
	public function setUp()
	{
		parent::setUp();
		config(['app.url' => 'https://api.dbp.test']);

		$this->key    = '53355c32fca5f3cac4d7a670d2df2e09';
		$this->params = ['v' => 4, 'key' => $this->key, 'pretty'];

		// Fetch the Swagger Docs for Structure Validation
		$arrContextOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]];
		$swagger_url       = 'https://dbp.test/swagger_docs?v=v4';
		$this->swagger     = json_decode(file_get_contents($swagger_url, false, stream_context_create($arrContextOptions)), true);
		$this->schemas     = $this->swagger['components']['schemas'];
		ini_set('memory_limit', '1264M');
	}

	public function getSchemaKeys($schema)
	{
		if (isset($this->schemas[$schema]['items'])) return array_keys($this->schemas[$schema]['items']['properties']);
		return array_keys($this->schemas[$schema]['properties']);
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_access_groups.index
	 * @category Route Path: https://api.dbp.test/access/groups?v=4&key=1234
	 * @see      \App\Http\Controllers\User\AccessGroupController@index
	 *
	 */
	public function test_v4_access_groups_index()
	{
		$path = route('v4_access_groups.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_access_groups.store
	 * @category Route Path: https://api.dbp.test/access/groups/?v=4&key=1234
	 * @see      \App\Http\Controllers\User\AccessGroupController@store
	 *
	 */
	public function test_v4_access_groups_store()
	{
		$path = route('v4_access_groups.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_access_groups.show
	 * @category Route Path: https://api.dbp.test/access/groups/{group_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\AccessGroupController@show
	 *
	 */
	public function test_v4_access_groups_show()
	{
		$path = route('v4_access_groups.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_access_groups.update
	 * @category Route Path: https://api.dbp.test/access/groups/{group_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\AccessGroupController@update
	 *
	 */
	public function test_v4_access_groups_update()
	{
		$path = route('v4_access_groups.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_access_groups.destroy
	 * @category Route Path: https://api.dbp.test/access/groups/{group_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\AccessGroupController@destroy
	 *
	 */
	public function test_v4_access_groups_destroy()
	{
		$path = route('v4_access_groups.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.types
	 * @category Route Path: https://api.dbp.test/bibles/filesets/media/types?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController@mediaTypes
	 *
	 */
	public function test_v4_bible_filesets_types()
	{
		$path = route('v4_bible_filesets.types', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_video_stream'
	 * @category Route Path:
	 * @see      \App\Http\Controllers\bible/filesets/{fileset_id}https://api.dbp.test/
	 *           ''?v=4&key=1234/stream/{file_id}/playlist.m3u8',  'Bible\VideoStreamController@index
	 *
	 */
	public function test_v4_video_stream()
	{
		$path = route('v4_video_stream', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_video_stream_ts'
	 * @category Route Path:
	 * @see      \App\Http\Controllers\bible/filesets/{fileset_id}/stream/{fhttps://api.dbp.test/
	 *           ''?v=4&key=1234ile_id}/{file_name}', 'Bible\VideoStreamController@transportStream
	 *
	 */
	public function test_v4_video_stream_ts()
	{
		$path = route('v4_video_stream_ts', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.podcast
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/podcast?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController@podcast
	 *
	 */
	public function test_v4_bible_filesets_podcast()
	{
		$path = route('v4_bible_filesets.podcast', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.download
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/download?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController@download
	 *
	 */
	public function test_v4_bible_filesets_download()
	{
		$path = route('v4_bible_filesets.download', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.copyright
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/copyright?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController@copyright
	 *
	 */
	public function test_v4_bible_filesets_copyright()
	{
		$path = route('v4_bible_filesets.copyright', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible.filesets
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/books?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BooksController@show
	 *
	 */
	public function test_v4_bible_filesets()
	{
		$path = route('v4_bible.filesets', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.chapter
	 * @category Route Path: https://api.dbp.test/bibles/filesets/ENGKJV/GEN/1?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\TextController@index
	 *
	 */
	public function test_v4_bible_filesets_chapter()
	{
		$this->params = array_merge(['fileset_id' => 'ENGKJV','book_id' => 'GEN', 'chapter' => 1], $this->params);
		$path = route('v4_bible_filesets.chapter', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.show
	 * @category Route Path: https://api.dbp.test/bibles/filesets/ENGKJV?v=4&key=1234&type=plain_text&bucket=dbs-web
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController@show
	 *
	 */
	public function test_v4_bible_filesets_show()
	{
		$this->params = array_merge(['fileset_id' => 'ENGKJV','book_id' => 'GEN', 'chapter' => 1], $this->params);
		$path = route('v4_bible_filesets.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.update
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController@update
	 *
	 */
	public function test_v4_bible_filesets_update()
	{
		$path = route('v4_bible_filesets.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.store
	 * @category Route Path: https://api.dbp.test/bibles/filesets/?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController@store
	 *
	 */
	public function test_v4_bible_filesets_store()
	{
		$path = route('v4_bible_filesets.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible.links
	 * @category Route Path: https://api.dbp.test/bibles/links?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleLinksController@index
	 *
	 */
	public function test_v4_bible_links()
	{
		$path = route('v4_bible.links', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible.allBooks
	 * @category Route Path: https://api.dbp.test/bibles/books/?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BooksController@index
	 *
	 */
	public function test_v4_bible_allBooks()
	{
		$path = route('v4_bible.allBooks', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_text_search'
	 * @category Route Path: https://api.dbp.test/search?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\TextController@search
	 *
	 */
	public function test_v4_text_search()
	{
		$path = route('v4_text_search', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible_equivalents.all
	 * @category Route Path: https://api.dbp.test/bible/equivalents?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleEquivalentsController@index
	 *
	 */
	public function test_v4_bible_equivalents_all()
	{
		$path = route('v4_bible_equivalents.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible_equivalents.one
	 * @category Route Path: https://api.dbp.test/bibles/{bible_id}/equivalents?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleEquivalentsController@show
	 *
	 */
	public function test_v4_bible_equivalents_one()
	{
		$path = route('v4_bible_equivalents.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible.books
	 * @category Route Path: https://api.dbp.test/bibles/{bible_id}/book/{book?}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController@books
	 *
	 */
	public function test_v4_bible_books()
	{
		$path = route('v4_bible.books', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible.archival
	 * @category Route Path: https://api.dbp.test/bibles/archival?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController@archival
	 *
	 */
	public function test_v4_bible_archival()
	{
		$path = route('v4_bible.archival', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible.one
	 * @category Route Path: https://api.dbp.test/bibles/{bible_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController@show
	 *
	 */
	public function test_v4_bible_one()
	{
		$path = route('v4_bible.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bible.all
	 * @category Route Path: https://api.dbp.test/bibles?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController@index
	 *
	 */
	public function test_v4_bible_all()
	{
		$path = route('v4_bible.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_timestamps'
	 * @category Route Path: https://api.dbp.test/timestamps?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\AudioController@availableTimestamps
	 *
	 */
	public function test_v4_timestamps()
	{
		$path = route('v4_timestamps', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_timestamps.tag
	 * @category Route Path: https://api.dbp.test/timestamps/{fileset_id}/{query}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\AudioController@timestampsByTag
	 *
	 */
	public function test_v4_timestamps_tag()
	{
		$path = route('v4_timestamps.tag', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_timestamps.verse
	 * @category Route Path: https://api.dbp.test/timestamps/{fileset_id}/{book}/{chapter}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\AudioController@timestampsByReference
	 *
	 */
	public function test_v4_timestamps_verse()
	{
		$path = route('v4_timestamps.verse', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_countries.all
	 * @category Route Path: https://api.dbp.test/countries?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\CountriesController@index
	 *
	 */
	public function test_v4_countries_all()
	{
		$path = route('v4_countries.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_countries.jsp
	 * @category Route Path: https://api.dbp.test/countries/joshua-project/?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\CountriesController@joshuaProjectIndex
	 *
	 */
	public function test_v4_countries_jsp()
	{
		$path = route('v4_countries.jsp', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_countries.one
	 * @category Route Path: https://api.dbp.test/countries/{country_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\CountriesController@show
	 *
	 */
	public function test_v4_countries_one()
	{
		$path = route('v4_countries.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_languages.all
	 * @category Route Path: https://api.dbp.test/languages?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\LanguagesController@index
	 *
	 */
	public function test_v4_languages_all()
	{
		$path = route('v4_languages.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_languages.one
	 * @category Route Path: https://api.dbp.test/languages/{language_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\LanguagesController@show
	 *
	 */
	public function test_v4_languages_one()
	{
		$path = route('v4_languages.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_alphabets.all
	 * @category Route Path: https://api.dbp.test/alphabets?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\AlphabetsController@index
	 *
	 */
	public function test_v4_alphabets_all()
	{
		$path = route('v4_alphabets.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_alphabets.one
	 * @category Route Path: https://api.dbp.test/alphabets/{alphabet_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\AlphabetsController@show
	 *
	 */
	public function test_v4_alphabets_one()
	{
		$path = route('v4_alphabets.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_alphabets.store
	 * @category Route Path: https://api.dbp.test/alphabets?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\AlphabetsController@store
	 *
	 */
	public function test_v4_alphabets_store()
	{
		$path = route('v4_alphabets.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_alphabets.update
	 * @category Route Path: https://api.dbp.test/alphabets/{alphabet_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\AlphabetsController@update
	 *
	 */
	public function test_v4_alphabets_update()
	{
		$path = route('v4_alphabets.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_numbers.all
	 * @category Route Path: https://api.dbp.test/numbers/?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\NumbersController@index
	 *
	 */
	public function test_v4_numbers_all()
	{
		$path = route('v4_numbers.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_numbers.one
	 * @category Route Path: https://api.dbp.test/numbers/{number_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Wiki\NumbersController@show
	 *
	 */
	public function test_v4_numbers_one()
	{
		$path = route('v4_numbers.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.index
	 * @category Route Path: https://api.dbp.test/users?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController@index
	 *
	 */
	public function test_v4_user_index()
	{
		$path = route('v4_user.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.store
	 * @category Route Path: https://api.dbp.test/users?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController@store
	 *
	 */
	public function test_v4_user_store()
	{
		$path = route('v4_user.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.show
	 * @category Route Path: https://api.dbp.test/users/{user_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController@show
	 *
	 */
	public function test_v4_user_show()
	{
		$path = route('v4_user.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.update
	 * @category Route Path: https://api.dbp.test/users/{user_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController@update
	 *
	 */
	public function test_v4_user_update()
	{
		$path = route('v4_user.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.destroy
	 * @category Route Path: https://api.dbp.test/users/{user_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController@destroy
	 *
	 */
	public function test_v4_user_destroy()
	{
		$path = route('v4_user.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.login
	 * @category Route Path: https://api.dbp.test/users/login?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController@login
	 *
	 */
	public function test_v4_user_login()
	{
		$path = route('v4_user.login', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.geolocate
	 * @category Route Path: https://api.dbp.test/users/geolocate?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController@geoLocate
	 *
	 */
	public function test_v4_user_geolocate()
	{
		$path = route('v4_user.geolocate', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.oAuth
	 * @category Route Path: https://api.dbp.test/users/login/{driver}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController@getSocialRedirect
	 *
	 */
	public function test_v4_user_oAuth()
	{
		$path = route('v4_user.oAuth', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.oAuthCallback
	 * @category Route Path: https://api.dbp.test/users/login/{driver}/callback?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UsersController@getSocialHandle
	 *
	 */
	public function test_v4_user_oAuthCallback()
	{
		$path = route('v4_user.oAuthCallback', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.password_reset
	 * @category Route Path: https://api.dbp.test/users/password/reset?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserPasswordsController@validatePasswordReset
	 *
	 */
	public function test_v4_user_password_reset()
	{
		$path = route('v4_user.password_reset', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user.password_email
	 * @category Route Path: https://api.dbp.test/users/password/email?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserPasswordsController@triggerPasswordResetEmail
	 *
	 */
	public function test_v4_user_password_email()
	{
		$path = route('v4_user.password_email', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user_accounts.index
	 * @category Route Path: https://api.dbp.test//accounts?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserAccountsController@index
	 *
	 */
	public function test_v4_user_accounts_index()
	{
		$path = route('v4_user_accounts.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user_accounts.show
	 * @category Route Path: https://api.dbp.test//accounts/{account_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserAccountsController@show
	 *
	 */
	public function test_v4_user_accounts_show()
	{
		$path = route('v4_user_accounts.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user_accounts.store
	 * @category Route Path: https://api.dbp.test//accounts?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserAccountsController@store
	 *
	 */
	public function test_v4_user_accounts_store()
	{
		$path = route('v4_user_accounts.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user_accounts.update
	 * @category Route Path: https://api.dbp.test//accounts/{account_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserAccountsController@update
	 *
	 */
	public function test_v4_user_accounts_update()
	{
		$path = route('v4_user_accounts.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_user_accounts.destroy
	 * @category Route Path: https://api.dbp.test//accounts/{account_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserAccountsController@destroy
	 *
	 */
	public function test_v4_user_accounts_destroy()
	{
		$path = route('v4_user_accounts.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_notes.index
	 * @category Route Path: https://api.dbp.test/users/{user_id}/notes?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserNotesController@index
	 *
	 */
	public function test_v4_notes_index()
	{
		$path = route('v4_notes.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_notes.show
	 * @category Route Path: https://api.dbp.test/users/{user_id}/notes/{note_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserNotesController@show
	 *
	 */
	public function test_v4_notes_show()
	{
		$path = route('v4_notes.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_notes.store
	 * @category Route Path: https://api.dbp.test/users/{user_id}/notes?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserNotesController@store
	 *
	 */
	public function test_v4_notes_store()
	{
		$path = route('v4_notes.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_notes.update
	 * @category Route Path: https://api.dbp.test/users/{user_id}/notes/{note_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserNotesController@update
	 *
	 */
	public function test_v4_notes_update()
	{
		$path = route('v4_notes.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_notes.destroy
	 * @category Route Path: https://api.dbp.test/users/{user_id}/notes/{note_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserNotesController@destroy
	 *
	 */
	public function test_v4_notes_destroy()
	{
		$path = route('v4_notes.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_messages.index
	 * @category Route Path: https://api.dbp.test/users/messages?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserContactController@index
	 *
	 */
	public function test_v4_messages_index()
	{
		$path = route('v4_messages.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_messages.show
	 * @category Route Path: https://api.dbp.test/users/messages/{note_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserContactController@show
	 *
	 */
	public function test_v4_messages_show()
	{
		$path = route('v4_messages.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bookmarks.index
	 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserBookmarksController@index
	 *
	 */
	public function test_v4_bookmarks_index()
	{
		$path = route('v4_bookmarks.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bookmarks.store
	 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserBookmarksController@store
	 *
	 */
	public function test_v4_bookmarks_store()
	{
		$path = route('v4_bookmarks.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bookmarks.update
	 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks/{bookmark_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserBookmarksController@update
	 *
	 */
	public function test_v4_bookmarks_update()
	{
		$path = route('v4_bookmarks.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_bookmarks.destroy
	 * @category Route Path: https://api.dbp.test/users/{user_id}/bookmarks/{bookmark_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserBookmarksController@destroy
	 *
	 */
	public function test_v4_bookmarks_destroy()
	{
		$path = route('v4_bookmarks.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_highlights.index
	 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserHighlightsController@index
	 *
	 */
	public function test_v4_highlights_index()
	{
		$path = route('v4_highlights.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_highlights.store
	 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserHighlightsController@store
	 *
	 */
	public function test_v4_highlights_store()
	{
		$path = route('v4_highlights.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_highlights.update
	 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights/{highlight_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserHighlightsController@update
	 *
	 */
	public function test_v4_highlights_update()
	{
		$path = route('v4_highlights.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_highlights.destroy
	 * @category Route Path: https://api.dbp.test/users/{user_id}/highlights/{highlight_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\UserHighlightsController@destroy
	 *
	 */
	public function test_v4_highlights_destroy()
	{
		$path = route('v4_highlights.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_organizations.all
	 * @category Route Path: https://api.dbp.test/organizations/?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\OrganizationsController@index
	 *
	 */
	public function test_v4_organizations_all()
	{
		$path = route('v4_organizations.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_organizations.one
	 * @category Route Path: https://api.dbp.test/organizations/{organization_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\OrganizationsController@show
	 *
	 */
	public function test_v4_organizations_one()
	{
		$path = route('v4_organizations.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_organizations.compare
	 * @category Route Path: https://api.dbp.test/organizations/compare/{org1}/to/{org2}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\OrganizationsController@compare
	 *
	 */
	public function test_v4_organizations_compare()
	{
		$path = route('v4_organizations.compare', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_api.versions
	 * @category Route Path: https://api.dbp.test//api/versions?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController@versions
	 *
	 */
	public function test_v4_api_versions()
	{
		$path = route('v4_api.versions', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_api.versionLatest
	 * @category Route Path: https://api.dbp.test//api/versions/latest?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController@versionLatest
	 *
	 */
	public function test_v4_api_versionLatest()
	{
		$path = route('v4_api.versionLatest', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_api.replyTypes
	 * @category Route Path: https://api.dbp.test//api/versions/replyTypes?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController@versionReplyTypes
	 *
	 */
	public function test_v4_api_replyTypes()
	{
		$path = route('v4_api.replyTypes', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_api.sign
	 * @category Route Path: https://api.dbp.test/sign?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController@signedUrls
	 *
	 */
	public function test_v4_api_sign()
	{
		$path = route('v4_api.sign', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_projects.index
	 * @category Route Path: https://api.dbp.test/projects?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectsController@index
	 *
	 */
	public function test_v4_projects_index()
	{
		$path = route('v4_projects.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_projects.show
	 * @category Route Path: https://api.dbp.test/projects/{project_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectsController@show
	 *
	 */
	public function test_v4_projects_show()
	{
		$path = route('v4_projects.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_projects.update
	 * @category Route Path: https://api.dbp.test/projects/{project_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectsController@update
	 *
	 */
	public function test_v4_projects_update()
	{
		$path = route('v4_projects.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_projects.store
	 * @category Route Path: https://api.dbp.test/projects?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectsController@store
	 *
	 */
	public function test_v4_projects_store()
	{
		$path = route('v4_projects.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_projects.destroy
	 * @category Route Path: https://api.dbp.test/projects/{project_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectsController@destroy
	 *
	 */
	public function test_v4_projects_destroy()
	{
		$path = route('v4_projects.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_projects_oAuthProvider.index
	 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController@index
	 *
	 */
	public function test_v4_projects_oAuthProvider_index()
	{
		$path = route('v4_projects_oAuthProvider.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_projects_oAuthProvider.show
	 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/{id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController@show
	 *
	 */
	public function test_v4_projects_oAuthProvider_show()
	{
		$path = route('v4_projects_oAuthProvider.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_projects_oAuthProvider.update
	 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/{id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController@update
	 *
	 */
	public function test_v4_projects_oAuthProvider_update()
	{
		$path = route('v4_projects_oAuthProvider.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_projects_oAuthProvider.store
	 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController@store
	 *
	 */
	public function test_v4_projects_oAuthProvider_store()
	{
		$path = route('v4_projects_oAuthProvider.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_projects_oAuthProvider.destroy
	 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/{id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController@destroy
	 *
	 */
	public function test_v4_projects_oAuthProvider_destroy()
	{
		$path = route('v4_projects_oAuthProvider.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_api.buckets
	 * @category Route Path: https://api.dbp.test//api/buckets?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController@buckets
	 *
	 */
	public function test_v4_api_buckets()
	{
		$path = route('v4_api.buckets', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_api.stats
	 * @category Route Path: https://api.dbp.test//stats?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController@stats
	 *
	 */
	public function test_v4_api_stats()
	{
		$path = route('v4_api.stats', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_api.logs
	 * @category Route Path: https://api.dbp.test/sign?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController@signedUrls
	 *
	 */
	public function test_v4_api_logs()
	{
		$path = route('v4_api.logs', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_algolia.bibles
	 * @category Route Path: https://api.dbp.test//algolia/bibles?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\AlgoliaOutputController@bibles
	 *
	 */
	public function test_v4_algolia_bibles()
	{
		$path = route('v4_algolia.bibles', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_algolia.languages
	 * @category Route Path: https://api.dbp.test/algolia/languages?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\AlgoliaOutputController@languages
	 *
	 */
	public function test_v4_algolia_languages()
	{
		$path = route('v4_algolia.languages', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_connections_jfm.sync
	 * @category Route Path: https://api.dbp.test/connections/jesus-film/sync?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\ArclightController@sync
	 *
	 */
	public function test_v4_connections_jfm_sync()
	{
		$path = route('v4_connections_jfm.sync', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_connections_jfm.index
	 * @category Route Path: https://api.dbp.test/connections/jesus-film/{iso}?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\ArclightController@index
	 *
	 */
	public function test_v4_connections_jfm_index()
	{
		$path = route('v4_connections_jfm.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_connections_app.deeplink
	 * @category Route Path: https://api.dbp.test/connections/app/deeplinking?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\MobileAppsController@redirectDeepLink
	 *
	 */
	public function test_v4_connections_app_deeplink()
	{
		$path = route('v4_connections_app.deeplink', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_connections_grn.sync
	 * @category Route Path: https://api.dbp.test/connections/grn/sync?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\GRNController@sync
	 *
	 */
	public function test_v4_connections_grn_sync()
	{
		$path = route('v4_connections_grn.sync', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_connections_grn.index
	 * @category Route Path: https://api.dbp.test/connections/grn/{iso}?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\GRNController@index
	 *
	 */
	public function test_v4_connections_grn_index()
	{
		$path = route('v4_connections_grn.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_resources.index
	 * @category Route Path: https://api.dbp.test/resources?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ResourcesController@index
	 *
	 */
	public function test_v4_resources_index()
	{
		$path = route('v4_resources.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_resources.show
	 * @category Route Path: https://api.dbp.test/resources/{resource_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ResourcesController@show
	 *
	 */
	public function test_v4_resources_show()
	{
		$path = route('v4_resources.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_resources.update
	 * @category Route Path: https://api.dbp.test/resources/{resource_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ResourcesController@update
	 *
	 */
	public function test_v4_resources_update()
	{
		$path = route('v4_resources.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_resources.store
	 * @category Route Path: https://api.dbp.test/resources?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ResourcesController@store
	 *
	 */
	public function test_v4_resources_store()
	{
		$path = route('v4_resources.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_resources.destroy
	 * @category Route Path: https://api.dbp.test/resources/{resource_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ResourcesController@destroy
	 *
	 */
	public function test_v4_resources_destroy()
	{
		$path = route('v4_resources.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_articles.index
	 * @category Route Path: https://api.dbp.test/articles?v=4&key=1234
	 * @see      \App\Http\Controllers\User\ArticlesController@index
	 *
	 */
	public function test_v4_articles_index()
	{
		$path = route('v4_articles.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_articles.show
	 * @category Route Path: https://api.dbp.test/articles/{article_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\ArticlesController@show
	 *
	 */
	public function test_v4_articles_show()
	{
		$path = route('v4_articles.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_articles.update
	 * @category Route Path: https://api.dbp.test/articles/{article_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\ArticlesController@update
	 *
	 */
	public function test_v4_articles_update()
	{
		$path = route('v4_articles.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_articles.store
	 * @category Route Path: https://api.dbp.test/articles?v=4&key=1234
	 * @see      \App\Http\Controllers\User\ArticlesController@store
	 *
	 */
	public function test_v4_articles_store()
	{
		$path = route('v4_articles.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 *
	 * @category V4_API
	 * @category Route Name: v4_articles.destroy
	 * @category Route Path: https://api.dbp.test/articles/{article_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\User\ArticlesController@destroy
	 *
	 */
	public function test_v4_articles_destroy()
	{
		$path = route('v4_articles.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

}
