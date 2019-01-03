<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User\Key;

class ApiV4Test extends TestCase
{

    protected $params;
    protected $swagger;
    protected $schemas;
    protected $key;

    /**Api_V2_Test constructor
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->key    = Key::where('name', 'test-key')->first()->key;
        $this->params = ['v' => 4, 'key' => $this->key, 'pretty'];

        // Fetch the Swagger Docs for Structure Validation
        $arrContextOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]];
        $swagger_url       = base_path('resources/assets/js/swagger_v4.json');
        $this->swagger     = json_decode(file_get_contents($swagger_url, false, stream_context_create($arrContextOptions)), true);
        ini_set('memory_limit', '1264M');
    }

    public function getSchemaKeys($schema)
    {
        if (isset($this->swagger['components']['schemas']['items'])) {
            return array_keys($this->schemas[$schema]['items']['properties']);
        }
        return array_keys($this->schemas[$schema]['properties']);
    }


    /**
     * @category V4_API
     * @category Route Name: v4_api.versions
     * @category Route Path: https://api.dbp.test/api/versions?v=4&key={key}
     * @see      \App\Http\Controllers\HomeController::versions
     * @group    V4
     * @test
     */
    public function versionsReturnSuccessful()
    {
        $path = route('v4_api.versions', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_api.buckets
     * @category Route Path: https://api.dbp.test/api/buckets?v=4&key={key}
     * @see      \App\Http\Controllers\HomeController::buckets
     * @group    V4
     * @test
     */
    public function bucketsReturnSuccessful()
    {
        $path = route('v4_api.buckets', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_api.stats
     * @category Route Path: https://api.dbp.test/stats?v=4&key={key}
     * @see      \App\Http\Controllers\HomeController::stats
     * @group    V4
     * @test
     */
    public function statsReturnSuccessful()
    {
        $path = route('v4_api.stats', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

}
