<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class v4_BaseTest extends BaseTestCase
{
	use CreatesApplication;

	protected $params;
	protected $swagger;
	protected $schemas;

	/**
	 * API_V4_Test constructor
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


}
