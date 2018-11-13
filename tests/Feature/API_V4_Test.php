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
		$this->key    = '1234';
		$this->params = ['v' => 4, 'key' => $this->key, 'pretty'];

		// Fetch the Swagger Docs for Structure Validation
		$arrContextOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]];
		$swagger_url       = base_path('resources/assets/js/swagger_v4.json');
		$this->swagger     = json_decode(file_get_contents($swagger_url, false, stream_context_create($arrContextOptions)), true);
		$this->schemas     = $this->swagger['components']['schemas'];
		ini_set('memory_limit', '1264M');
	}

	public function getSchemaKeys($schema)
	{
		if (isset($this->schemas[$schema]['items'])) return array_keys($this->schemas[$schema]['items']['properties']);
		return array_keys($this->schemas[$schema]['properties']);
	}


	// TODO: Check JFM Connection
	/**
	 * @category V4_API
	 * @category Route Name: v4_connections_jfm.index
	 * @category Route Path: https://api.dbp.test/connections/jesus-film/{iso}?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\ArclightController::index

	public function test_v4_connections_jfm_index()
	{
		$path = route('v4_connections_jfm.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}
	 *
	 */

	// TODO: Check Deeplink Connection
	/**
	 * @category V4_API
	 * @category Route Name: v4_connections_app.deeplink
	 * @category Route Path: https://api.dbp.test/connections/app/deeplinking?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\MobileAppsController::redirectDeepLink

	public function test_v4_connections_app_deeplink()
	{
		$path = route('v4_connections_app.deeplink', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}
	 */
	// TODO: Check GRN Connection
	/**
	 * @category V4_API
	 * @category Route Name: v4_connections_grn.index
	 * @category Route Path: https://api.dbp.test/connections/grn/{iso}?v=4&key=1234
	 * @see      \App\Http\Controllers\Connections\GRNController::index

	public function test_v4_connections_grn_index()
	{
		$path = route('v4_connections_grn.index', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}
	 * */

}
