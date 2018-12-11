<?php

namespace Tests\Integration;

class TimestampsRoutesTest extends ApiV4Test
{

    /**
     * @category V4_API
     * @category Route Name: v4_timestamps'
     * @category Route Path: https://api.dbp.test/timestamps?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\AudioController::availableTimestamps
     * @group    V4
     * @test
     */
    public function timestamps()
    {
        $path = route('v4_timestamps', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_timestamps.tag
     * @category Route Path: https://api.dbp.test/timestamps/{fileset_id}/{query}?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\AudioController::timestampsByTag
     * @group    V4
     * @test
     */
    public function timestampsTag()
    {
        $additional_params = [
            'audio_fileset_id' => 'ENGESVO2DA',
            'text_fileset_id' => 'ENGESV',
            'query' => 'God'
        ];

        $path = route('v4_timestamps.tag', array_merge($additional_params, $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }
}
