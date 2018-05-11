<?php

namespace App\Http\Controllers;

use App\Models\Bible\Video;
use App\Transformers\FilmTransformer;

class FilmsController extends APIController {


	/**
	 * Video Location
	 * This method allows the caller to retrieve information about the media distribution servers and protocols they support.
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
	 *     tags={"Version 2"},
	 *     summary="",
	 *     description="",
	 *     operationId="v2_video_path",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/responses/v2_video_path")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/responses/v2_video_path"))
	 *     )
	 * )
	 *
	 * @return mixed
	 */
	public function videopath() {
		if(!$this->api) return view('docs.v2.video_videoPath');

		$bible_id = checkParam('dam_id');
		$films = Video::with('book','bible.translations','translations','related')->with(['related' => function ($query) use($bible_id) {
			$query->where('bible_id', $bible_id);
		}])->where('bible_id', $bible_id)->where('section','main')->get();

		return $this->reply(fractal()->collection( $films )->transformWith(new FilmTransformer())->serializeWith( $this->serializer )->toArray());
	}


}
