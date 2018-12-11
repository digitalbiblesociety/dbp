<?php

namespace Tests\Integration;

class ApiMetaRoutesTest extends ApiV4Test
{

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
