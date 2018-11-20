<?php

namespace Tests\Feature;

use App\Http\Controllers\Bible\VideoStreamController;

class v4_videoRoutesTest extends API_V4_Test
{

	/**
	 * @category V4_API
	 * @category Route Name: v4_video_stream
	 * @category Route Path: https://api.dbp.test/?v=4&key=1234/stream/{file_id}/playlist.m3u8
     * @see      VideoStreamController::index
	 */
	public function test_v4_video_stream()
	{
		$video_stream = [
			'file_id'    => '1215691',
			'fileset_id' => 'BOXWYIP2DV',
			'file_name'  => 'Buamu_MRK_1-1-8_R_stream.m3u8'
		];
		$path = route('v4_video_stream', array_merge($this->params,$video_stream));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
        $this->assertEquals($response->getStatusCode(), 200);

        $disposition_header = $response->headers->get('content-disposition');
        $this->assertContains('attachment', $disposition_header);
        $this->assertContains('filename="' . $video_stream['file_name'] . '"', $disposition_header);
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_video_stream_ts
	 * @category Route Path: https://api.dbp.test/bible/filesets/{fileset_id}/stream/{file_id}/{file_name}?v=4&key={key}
	 * @see      VideoStreamController::transportStream
	 */

	public function test_v4_video_stream_ts()
	{
		$video_stream = [
			'file_id'    => '1215691',
			'fileset_id' => 'BOXWYIP2DV',
			'file_name'  => 'Buamu_MRK_1-1-8_R_stream.m3u8'
		];
		$path = route('v4_video_stream_ts', array_merge($video_stream, $this->params));
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertContains('attachment', $response->headers->get('content-disposition'));
	}

}
