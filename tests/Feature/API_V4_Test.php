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
