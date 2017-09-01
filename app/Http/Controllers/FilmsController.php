<?php

namespace App\Http\Controllers;

use App\Models\Bible\Film;
use Illuminate\Http\Request;

class FilmsController extends APIController {

	/**
	 * Video Location
	 * This method allows the caller to retrieve information about the media distribution servers and protocols they support.
	 *
	 * @return JSON
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

	public function videopath() {
		if ( ! $this->api ) return view( 'docs.v2.video_videoPath' );

		$series_id = checkParam( 'dam_id' );
		$films = Film::where( 'series_id', $series_id )->get();

		return $this->reply(fractal()->collection( $films )->transformWith(new FilmTransformer())->serializeWith( $this->serializer )->toArray());
	}


}
