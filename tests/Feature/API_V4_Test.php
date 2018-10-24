<?php

namespace Tests\Feature;

use Tests\TestCase;
class API_V4_Test extends TestCase
{

	protected $params;
	protected $swagger;
	protected $schemas;
	protected $key;

	/**API_V2_Test constructor
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
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.types
	 * @category Route Path: https://api.dbp.test/bibles/filesets/media/types?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::mediaTypes
	 */
	public function test_v4_bible_filesets_types()
	{
		$path = route('v4_bible_filesets.types', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_video_stream'
	 * @category Route Path:
	 * @see      \App\Http\Controllers\bible/filesets/{fileset_id}https://api.dbp.test/
	 *           ''?v=4&key=1234/stream/{file_id}/playlist.m3u8',  'Bible\VideoStreamController::index
	 */
	public function test_v4_video_stream()
	{
		$path = route('v4_video_stream', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_video_stream_ts'
	 * @category Route Path:
	 * @see      \App\Http\Controllers\bible/filesets/{fileset_id}/stream/{fhttps://api.dbp.test/
	 *           ''?v=4&key=1234ile_id}/{file_name}', 'Bible\VideoStreamController::transportStream
	 */
	public function test_v4_video_stream_ts()
	{
		$path = route('v4_video_stream_ts', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.podcast
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/podcast?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::podcast
	 */
	public function test_v4_bible_filesets_podcast()
	{
		$path = route('v4_bible_filesets.podcast', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.download
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/download?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::download
	 */
	public function test_v4_bible_filesets_download()
	{
		$path = route('v4_bible_filesets.download', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.copyright
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/copyright?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::copyright
	 */
	public function test_v4_bible_filesets_copyright()
	{
		$path = route('v4_bible_filesets.copyright', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.filesets
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/books?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BooksController::show
	 */
	public function test_v4_bible_filesets()
	{
		$path = route('v4_bible.filesets', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.chapter
	 * @category Route Path: https://api.dbp.test/bibles/filesets/ENGKJV/GEN/1?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\TextController::index
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
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.show
	 * @category Route Path: https://api.dbp.test/bibles/filesets/ENGKJV?v=4&key=1234&type=plain_text&bucket=dbs-web
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::show
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
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.update
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::update
	 */
	public function test_v4_bible_filesets_update()
	{
		$path = route('v4_bible_filesets.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.store
	 * @category Route Path: https://api.dbp.test/bibles/filesets/?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::store
	 */
	public function test_v4_bible_filesets_store()
	{
		$path = route('v4_bible_filesets.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.links
	 * @category Route Path: https://api.dbp.test/bibles/links?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleLinksController::index
	 */
	public function test_v4_bible_links()
	{
		$path = route('v4_bible.links', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.allBooks
	 * @category Route Path: https://api.dbp.test/bibles/books/?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BooksController::index
	 */
	public function test_v4_bible_allBooks()
	{
		$path = route('v4_bible.allBooks', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_text_search'
	 * @category Route Path: https://api.dbp.test/search?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\TextController::search
	 */
	public function test_v4_text_search()
	{
		$path = route('v4_text_search', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_equivalents.all
	 * @category Route Path: https://api.dbp.test/bible/equivalents?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleEquivalentsController::index
	 */
	public function test_v4_bible_equivalents_all()
	{
		$path = route('v4_bible_equivalents.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_equivalents.one
	 * @category Route Path: https://api.dbp.test/bibles/{bible_id}/equivalents?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleEquivalentsController::show
	 */
	public function test_v4_bible_equivalents_one()
	{
		$path = route('v4_bible_equivalents.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.books
	 * @category Route Path: https://api.dbp.test/bibles/{bible_id}/book/{book?}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController::books
	 */
	public function test_v4_bible_books()
	{
		$path = route('v4_bible.books', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.archival
	 * @category Route Path: https://api.dbp.test/bibles/archival?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController::archival
	 */
	public function test_v4_bible_archival()
	{
		$path = route('v4_bible.archival', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.one
	 * @category Route Path: https://api.dbp.test/bibles/{bible_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController::show
	 */
	public function test_v4_bible_one()
	{
		$path = route('v4_bible.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.all
	 * @category Route Path: https://api.dbp.test/bibles?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController::index
	 */
	public function test_v4_bible_all()
	{
		$path = route('v4_bible.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_timestamps'
	 * @category Route Path: https://api.dbp.test/timestamps?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\AudioController::availableTimestamps
	 */
	public function test_v4_timestamps()
	{
		$path = route('v4_timestamps', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_timestamps.tag
	 * @category Route Path: https://api.dbp.test/timestamps/{fileset_id}/{query}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\AudioController::timestampsByTag
	 */
	public function test_v4_timestamps_tag()
	{
		$path = route('v4_timestamps.tag', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_timestamps.verse
	 * @category Route Path: https://api.dbp.test/timestamps/{fileset_id}/{book}/{chapter}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\AudioController::timestampsByReference
	 */
	public function test_v4_timestamps_verse()
	{
		$path = route('v4_timestamps.verse', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_organizations.all
	 * @category Route Path: https://api.dbp.test/organizations/?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\OrganizationsController::index
	 */
	public function test_v4_organizations_all()
	{
		$path = route('v4_organizations.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_organizations.one
	 * @category Route Path: https://api.dbp.test/organizations/{organization_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\OrganizationsController::show
	 */
	public function test_v4_organizations_one()
	{
		$path = route('v4_organizations.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_organizations.compare
	 * @category Route Path: https://api.dbp.test/organizations/compare/{org1}/to/{org2}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\OrganizationsController::compare
	 */
	public function test_v4_organizations_compare()
	{
		$path = route('v4_organizations.compare', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_api.versions
	 * @category Route Path: https://api.dbp.test//api/versions?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController::versions
	 */
	public function test_v4_api_versions()
	{
		$path = route('v4_api.versions', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_api.versionLatest
	 * @category Route Path: https://api.dbp.test//api/versions/latest?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController::versionLatest
	 */
	public function test_v4_api_versionLatest()
	{
		$path = route('v4_api.versionLatest', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_api.replyTypes
	 * @category Route Path: https://api.dbp.test//api/versions/replyTypes?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController::versionReplyTypes
	 */
	public function test_v4_api_replyTypes()
	{
		$path = route('v4_api.replyTypes', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_api.sign
	 * @category Route Path: https://api.dbp.test/sign?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController::signedUrls
	 */
	public function test_v4_api_sign()
	{
		$path = route('v4_api.sign', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_projects_oAuthProvider.index
	 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController::index
	 */
	public function test_v4_projects_oAuthProvider_index()
	{
		$path = route('v4_projects_oAuthProvider.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_projects_oAuthProvider.show
	 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/{id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController::show
	 */
	public function test_v4_projects_oAuthProvider_show()
	{
		$path = route('v4_projects_oAuthProvider.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_projects_oAuthProvider.update
	 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/{id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController::update
	 */
	public function test_v4_projects_oAuthProvider_update()
	{
		$path = route('v4_projects_oAuthProvider.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_projects_oAuthProvider.store
	 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController::store
	 */
	public function test_v4_projects_oAuthProvider_store()
	{
		$path = route('v4_projects_oAuthProvider.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_projects_oAuthProvider.destroy
	 * @category Route Path: https://api.dbp.test/projects/{project_id}/oauth-providers/{id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Organization\ProjectOAuthProvidersController::destroy
	 */
	public function test_v4_projects_oAuthProvider_destroy()
	{
		$path = route('v4_projects_oAuthProvider.destroy', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_api.buckets
	 * @category Route Path: https://api.dbp.test//api/buckets?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController::buckets
	 */
	public function test_v4_api_buckets()
	{
		$path = route('v4_api.buckets', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_api.stats
	 * @category Route Path: https://api.dbp.test//stats?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController::stats
	 */
	public function test_v4_api_stats()
	{
		$path = route('v4_api.stats', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_api.logs
	 * @category Route Path: https://api.dbp.test/sign?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController::signedUrls
	 */
	public function test_v4_api_logs()
	{
		$path = route('v4_api.logs', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_algolia.bibles
	 * @category Route Path: https://api.dbp.test//algolia/bibles?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\AlgoliaOutputController::bibles
	 */
	public function test_v4_algolia_bibles()
	{
		$path = route('v4_algolia.bibles', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_algolia.languages
	 * @category Route Path: https://api.dbp.test/algolia/languages?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\AlgoliaOutputController::languages
	 */
	public function test_v4_algolia_languages()
	{
		$path = route('v4_algolia.languages', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_connections_jfm.sync
	 * @category Route Path: https://api.dbp.test/connections/jesus-film/sync?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\ArclightController::sync
	 */
	public function test_v4_connections_jfm_sync()
	{
		$path = route('v4_connections_jfm.sync', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_connections_jfm.index
	 * @category Route Path: https://api.dbp.test/connections/jesus-film/{iso}?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\ArclightController::index
	 */
	public function test_v4_connections_jfm_index()
	{
		$path = route('v4_connections_jfm.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_connections_app.deeplink
	 * @category Route Path: https://api.dbp.test/connections/app/deeplinking?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\MobileAppsController::redirectDeepLink
	 */
	public function test_v4_connections_app_deeplink()
	{
		$path = route('v4_connections_app.deeplink', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_connections_grn.sync
	 * @category Route Path: https://api.dbp.test/connections/grn/sync?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\GRNController::sync
	 */
	public function test_v4_connections_grn_sync()
	{
		$path = route('v4_connections_grn.sync', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_connections_grn.index
	 * @category Route Path: https://api.dbp.test/connections/grn/{iso}?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\GRNController::index
	 */
	public function test_v4_connections_grn_index()
	{
		$path = route('v4_connections_grn.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

}
