<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\Video;
use App\Models\Language\Language;
use Illuminate\Http\Request;
use App\Transformers\FilmTransformer;

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
		if(!$this->api) return view('docs.v2.video_videoPath');

		$bible_id = checkParam('dam_id');
		$films = Video::with('book','bible.translations','translations','related')->with(['related' => function ($query) use($bible_id) {
			$query->where('bible_id', $bible_id);
		}])->where('bible_id', $bible_id)->where('section','main')->get();

		return $this->reply(fractal()->collection( $films )->transformWith(new FilmTransformer())->serializeWith( $this->serializer )->toArray());
	}


}
