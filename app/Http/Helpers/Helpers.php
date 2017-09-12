<?php

function bookCodeConvert($code = null, $source_type = null, $destination_type = null) {
	$book = BookCode::where('type',$source_type)->where('code',$code)->first();
	return BookCode::where('type',$destination_type)->where('book_id',$book->book_id)->first()->code;
}

function checkParam($param, $v4Style = null, $optional = false)
{
	$url_param = (isset($_GET[$param])) ? $_GET[$param] : false;
	$url_header = Request::header($param);

	if($v4Style) return $v4Style;
	if(!$url_param AND !$url_header) {
		if($optional != "optional") abort(422, "You need to provide the missing parameter '$param'. Please append it to the url or the request Header.");
		return null;
	}
	if($url_param) return $url_param;
	if($url_header) return $url_header;
}

function fetchLanguage($id)
{
	$language = new \App\Models\Language\Language();

	// Query string or Header overrides param if exists
	if(isset($_GET['language_id'])) $id = $_GET['language_id'];
	if(Request::header('language_id')) $id = Request::header('language_id');

	// Check the incrementing numeric ID first
	if(is_numeric($id)) return $language->find($id);

	// Otherwise Fetch by string length
	switch (strlen($id)) {
		case 2:  return $language->where('iso1', $id)->first();
		case 3:  return $language->where('iso', $id)->first();
		case 8:  return $language->where('glotto_id', $id)->first();
		default: return false;
	}
}

function fetchAPI($path)
{
	if(env('APP_ENV') == "local") {
		$context = ["ssl" => ["verify_peer"=> false]];
	} else {
		$context = [
			"ssl" => [
				"cafile" => storage_path("cacert.pem"),
				"verify_peer"=> true,
				"verify_peer_name"=> true,
			]
		];
	}
	$contents = json_decode(file_get_contents($path, false, stream_context_create($context)));
	return json_encode($contents, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

}