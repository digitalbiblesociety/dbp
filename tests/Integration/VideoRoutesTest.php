<?php

namespace Tests\Integration;

use App\Http\Controllers\Bible\VideoStreamController;
use App\Models\Bible\BibleFile;
use App\Models\Bible\VideoResolution;

class VideoRoutesTest extends ApiV4Test
{

    /**
     * @category V4_API
     * @category Route Name: v4_video_stream
     * @category Route Path: https://api.dbp.test/?v=4&key={key}/stream/{file_id}/playlist.m3u8
     * @see      VideoStreamController::index
     * @group    V4
     * @test
     */
    public function videoStream()
    {
        $bible_file = BibleFile::with('fileset')->where('file_name', 'like', '%.m3u8')->inRandomOrder()->first();
        $video_stream = [
            'file_id'    => $bible_file->id,
            'fileset_id' => $bible_file->fileset->id,
            'file_name'  => $bible_file->file_name
        ];
        $path = route('v4_video_stream', array_merge($this->params, $video_stream));
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
     * @group    V4
     * @test
     */
    public function videoStreamTs()
    {
        $resolution = VideoResolution::with('file.fileset')->inRandomOrder()->first();
        $video_stream = [
            'file_id'    => $resolution->bible_file_id,
            'fileset_id' => $resolution->file->fileset->id,
            'file_name'  => $resolution->file_name
        ];
        $path = route('v4_video_stream_ts', array_merge($video_stream, $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertContains('attachment', $response->headers->get('content-disposition'));
    }
}
