<?php

namespace Tests\Feature;

class v4_apiMetaRoutesTest extends API_V4_Test
{

	/**
	 * @category V4_API
	 * @category Route Name: v4_api.versions
	 * @category Route Path: https://api.dbp.test/api/versions?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController::versions
	 */
	public function test_v4_api_meta_versions()
	{
		$path = route('v4_api.versions', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_api.buckets
	 * @category Route Path: https://api.dbp.test/api/buckets?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController::buckets
	 */
	public function test_v4_api_meta_buckets()
	{
		$path = route('v4_api.buckets', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_api.stats
	 * @category Route Path: https://api.dbp.test/stats?v=4&key=1234
	 * @see      \App\Http\Controllers\HomeController::stats
	 */
	public function test_v4_api_meta_stats()
	{
		$path = route('v4_api.stats', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

}
