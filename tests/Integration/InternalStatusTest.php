<?php

namespace Tests\Integration;

use Tests\TestCase;

class InternalStatusTest extends TestCase
{
    /**
     * @test
     */
    public function ensureStatusChecksAreSuccessful()
    {
        $arrContextOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]];
        $route = str_replace('api.','',route('api_status'));
        $response = json_decode(file_get_contents($route, false, stream_context_create($arrContextOptions)));

        $this->assertTrue($response->bibles_count > 0);
        $this->assertSame(200, $response->systems->status_code);
        $this->assertSame('live',$response->systems->cache);
        $this->assertSame('live',$response->database->users);
        $this->assertSame('live',$response->database->dbp);

    }
}
