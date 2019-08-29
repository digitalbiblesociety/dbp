<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\APIController;

use App\Models\Bible\Video;
use App\Transformers\FilmTransformer;

class FilmsController extends APIController
{


    /**
     * Video Location
     *
     * @OA\Get(
     *     path="/video/location",
     *     tags={"Library Video"},
     *     summary="Information about the media distribution servers & protocols",
     *     description="This method allows the caller to retrieve information about the media distribution servers and protocols they support.",
     *     operationId="v2_video_location",
     *     @OA\Parameter(name="video_server_bucket", in="query", description="The server's bucket", @OA\Schema(type="string", default="dbp-video.s3.amazonaws.com", example="dbp-video.s3.amazonaws.com")),
     *     @OA\Parameter(name="video_server_alias", in="query", description="The server's alias",  @OA\Schema(type="string", default="video.dbt.io", example="video.dbt.io")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_video_location")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_video_location")),
     *         @OA\MediaType(mediaType="text/csv",  @OA\Schema(ref="#/components/schemas/v2_video_location")),
     *         @OA\MediaType(mediaType="text/x-yaml",  @OA\Schema(ref="#/components/schemas/v2_video_location"))
     *     )
     * )
     *
     * @OA\Schema (
     *     type="object",
     *     schema="v2_video_location",
     *     description="",
     *     title="The single alphabet response",
     *     @OA\Xml(name="v2_video_location"),
     *     @OA\Property(property="server",      @OA\Schema(type="string",example="dbp-video.s3.amazonaws.com")),
     *     @OA\Property(property="root_path",   @OA\Schema(type="string")),
     *     @OA\Property(property="protocol",    @OA\Schema(type="string",example="http",enum={"http","https"})),
     *     @OA\Property(property="CDN",         @OA\Schema(type="integer",example=0,enum={0,1})),
     *     @OA\Property(property="priority",    @OA\Schema(type="integer",example=5))
     * )
     *
     * @return mixed
     */
    public function location()
    {
        return $this->reply([
            [
                'server'    => 'dbp-video.s3.amazonaws.com',
                'root_path' => '',
                'protocol'  => 'http',
                'CDN'       => 0,
                'priority'  => 5,
            ],
            [
                'server'    => 'video.dbt.io',
                'root_path' => '',
                'protocol'  => 'http',
                'CDN'       => 1,
                'priority'  => 3,
            ],
        ]);
    }

    /**
     *
     * Returns an array of version return types
     *
     * @category v2_video_path
     * @link http://api.dbp4.org/api/reply - V4 Access
     * @link https://api.dbp.test/api/reply?key=1234&v=4&pretty - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
     *
     * @OA\Get(
     *     path="/video/path",
     *     tags={"Library Video"},
     *     summary="",
     *     description="",
     *     operationId="v2_video_path",
     *     @OA\Parameter(name="dam_id", in="query", description="DAM ID for the video volume desired", @OA\Schema(type="string",title="encoding")),
     *     @OA\Parameter(name="encoding", in="query", description="The video encoding format desired", @OA\Schema(type="string",enum={"mp4","m3u8"},default="mp4")),
     *     @OA\Parameter(name="resolution", in="query", description="Resolution of video files requested corresponding to the basic categories of low, medium, and high. The default is 'lo'. DBT will determine if the volume is configured for the requested resolution. If not, it will return the next highest resolution to the requested resolution for which the volume is configured", @OA\Schema(type="string",enum={"lo","med","hi"},example="med")),
     *     @OA\Parameter(name="segment_order", in="query", description="The order number of the video segment in the volume. This is particularly useful for story volumes as there are no applicable OSIS or USFM book codes.", @OA\Schema(type="string",title="encoding")),
     *     @OA\Parameter(name="book_id", in="query", description="The USFM/OSIS book code may be used to filter segments by references to book desired.", @OA\Schema(type="string")),
     *     @OA\Parameter(name="chapter_id", in="query", description="Chapter id to filter segments by references based on book and chapter.", @OA\Schema(type="string",title="encoding")),
     *     @OA\Parameter(name="verse_id", in="query", description="Verse id to filter segments by references based on book, chapter and verse.", @OA\Schema(type="string",title="encoding")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_video_path")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_video_path")),
     *         @OA\MediaType(mediaType="text/csv",  @OA\Schema(ref="#/components/schemas/v2_video_path")),
     *         @OA\MediaType(mediaType="text/x-yaml",  @OA\Schema(ref="#/components/schemas/v2_video_path"))
     *     )
     * )
     *
     * @return mixed
     */
    public function videoPath()
    {
        if (!$this->api) {
            return view('docs.v2.video_videoPath');
        }

        $bible_id      = checkParam('dam_id|fileset_id');
        $encoding      = checkParam('encoding');
        $resolution    = checkParam('resolution');
        $segment_order = checkParam('segment_order');
        $book_id       = checkParam('book_id');
        $chapter_id    = checkParam('chapter_id');
        $verse_id      = checkParam('verse_id');

        $films = Video::with([
            'book',
            'bible.translations',
            'translations',
            'related' => function ($query) use ($bible_id) {
                $query->where('bible_id', $bible_id);
            },
        ])->where('bible_id', $bible_id)->where('section', 'main')->get();

        return $this->reply(fractal($films, new FilmTransformer(), $this->serializer));
    }
}
