<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User\Key;
use App\Http\Controllers\User\SwaggerDocsController;

class ApiV4Test extends TestCase
{
    protected $params;
    protected $swagger;
    protected $schemas;
    protected $key;

    /**Api_V2_Test constructor
     *
     */
    protected function setUp():void
    {
        parent::setUp();
        $this->key    = Key::where('name', 'test-key')->first()->key;
        $this->params = ['v' => 4, 'key' => $this->key, 'pretty'];

        $swagger = new SwaggerDocsController();
        $this->swagger = json_decode($swagger->swaggerDocsGen('v4')->content(), true);
        $this->schemas = $this->swagger['components']['schemas'];
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
     * @group    travis
     * @test
     *
     *      This must ensure
     *      versions return evermore
     *      only two and four
     *
     */
    public function versionsReturnSuccessful()
    {
        $path = route('v4_api.versions', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
        $response->assertJson(['versions' => [2,4]]);
    }

    /**
     * @category V4_API
     * @category Route Name: v4_api.buckets
     * @category Route Path: https://api.dbp.test/api/buckets?v=4&key={key}
     * @see      \App\Http\Controllers\HomeController::buckets
     * @group    V4
     * @group    travis
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
     * @group    travis
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
