<?php

namespace Tests\Integration;

use App\Http\Controllers\ApiMetadataController;
use Illuminate\Support\Facades\Input;

class InternalStatusTest extends ApiV4Test
{
    /**
     *
     * @group    travis
     * @test
     */
    public function ensureStatusChecksAreSuccessful()
    {
        Input::replace($this->params);

        $metaController = new ApiMetadataController();
        $response = $metaController->getStatus();
        $response = json_decode($response->content());

        $this->assertTrue($response->bibles_count > 0);
        $this->assertSame(200, $response->systems->status_code);
        $this->assertSame('live', $response->systems->cache);
        $this->assertSame('live', $response->database->users);
        $this->assertSame('live', $response->database->dbp);
    }
}
