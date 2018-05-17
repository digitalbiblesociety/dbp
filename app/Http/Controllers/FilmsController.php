<?php

namespace App\Http\Controllers;

use App\Models\Bible\Video;
use App\Transformers\FilmTransformer;

class FilmsController extends APIController {


	/**
	 * Video Location
	 *
	 * @OAS\Get(
	 *     path="/video/location",
	 *     tags={"Library Video"},
	 *     summary="Information about the media distribution servers & protocols",
	 *     description="This method allows the caller to retrieve information about the media distribution servers and protocols they support.",
	 *     operationId="v2_video_location",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Parameter(name="video_server_bucket", in="query", description="The server's bucket", @OAS\Schema(type="string", default="dbp-video.s3.amazonaws.com", example="dbp-video.s3.amazonaws.com")),
	 *     @OAS\Parameter(name="video_server_alias", in="query", description="The server's alias",  @OAS\Schema(type="string", default="video.dbt.io", example="video.dbt.io")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_video_location")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v2_video_location"))
	 *     )
	 * )
	 *
     * @OAS\Schema (
     *     type="object",
     *     schema="v2_video_location",
     *     description="",
     *     title="The single alphabet response",
     *     @OAS\Xml(name="v2_video_location"),
     *     @OAS\Property(property="server",      @OAS\Schema(type="string",example="dbp-video.s3.amazonaws.com")),
     *     @OAS\Property(property="root_path",   @OAS\Schema(type="string")),
     *     @OAS\Property(property="protocol",    @OAS\Schema(type="string",example="http",enum={"http","https"})),
     *     @OAS\Property(property="CDN",         @OAS\Schema(type="integer",example=0,enum={0,1})),
     *     @OAS\Property(property="priority",    @OAS\Schema(type="integer",example=5))
     * )
	 *
	 * @return mixed
	 */
	public function location() {
		$videoServer      = env( 'video_server_bucket' ) ?? "dbp-video.s3.amazonaws.com";
		$videoServerAlias = env( 'video_server_alias' ) ?? "video.dbt.io";

		return $this->reply( [
			[
				"server"    => $videoServer,
				"root_path" => "",
				"protocol"  => "http",
				"CDN"       => 0,
				"priority"  => 5
			],
			[
				"server"    => $videoServerAlias,
				"root_path" => "",
				"protocol"  => "http",
				"CDN"       => 1,
				"priority"  => 3
			]
		] );
	}

	/**
	 *
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */

	/**
	 *
	 * Returns an array of version return types
	 *
	 * @category v2_video_path
	 * @link http://api.bible.build/api/reply - V4 Access
	 * @link https://api.dbp.dev/api/reply?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
	 *
	 * @OAS\Get(
	 *     path="/video/path",
	 *     tags={"Library Video"},
	 *     summary="",
	 *     description="",
	 *     operationId="v2_video_path",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Parameter(name="dam_id", in="query", description="DAM ID for the video volume desired", @OAS\Schema(type="string",title="encoding")),
	 *     @OAS\Parameter(name="encoding", in="query", description="The video encoding format desired", @OAS\Schema(type="string",enum={"mp4","m3u8"},default="mp4")),
	 *     @OAS\Parameter(name="resolution", in="query", description="Resolution of video files requested corresponding to the basic categories of low, medium, and high. The default is 'lo'. DBT will determine if the volume is configured for the requested resolution. If not, it will return the next highest resolution to the requested resolution for which the volume is configured", @OAS\Schema(type="string",enum={"lo","med","hi"},example="med")),
	 *     @OAS\Parameter(name="segment_order", in="query", description="The order number of the video segment in the volume. This is particularly useful for story volumes as there are no applicable OSIS or USFM book codes.", @OAS\Schema(type="string",title="encoding")),
	 *     @OAS\Parameter(name="book_id", in="query", description="The USFM/OSIS book code may be used to filter segments by references to book desired.", @OAS\Schema(type="string")),
	 *     @OAS\Parameter(name="chapter_id", in="query", description="Chapter id to filter segments by references based on book and chapter.", @OAS\Schema(type="string",title="encoding")),
	 *     @OAS\Parameter(name="verse_id", in="query", description="Verse id to filter segments by references based on book, chapter and verse.", @OAS\Schema(type="string",title="encoding")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_video_path")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v2_video_path"))
	 *     )
	 * )
	 *
	 * @return mixed
	 */
	public function videopath() {
		if(!$this->api) return view('docs.v2.video_videoPath');

		$bible_id = checkParam('dam_id|fileset_id');
		$encoding = checkParam('encoding', null, 'optional');
		$resolution = checkParam('resolution', null, 'optional');
		$segment_order = checkParam('segment_order', null, 'optional');
		$book_id = checkParam('book_id', null, 'optional');
		$chapter_id = checkParam('chapter_id', null, 'optional');
		$verse_id = checkParam('verse_id', null, 'optional');

		$films = Video::with(['book','bible.translations','translations','related' => function ($query) use($bible_id) {
			$query->where('bible_id', $bible_id);
		}])->where('bible_id', $bible_id)->where('section','main')->get();

		return $this->reply(fractal()->collection( $films )->transformWith(new FilmTransformer())->serializeWith( $this->serializer )->toArray());
	}


}
