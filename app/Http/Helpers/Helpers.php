<?php

function bookCodeConvert($code = null, $source_type = null, $destination_type = null) {
	$book = BookCode::where('type',$source_type)->where('code',$code)->first();
	return BookCode::where('type',$destination_type)->where('book_id',$book->book_id)->first()->code;
}

function checkUser()
{
	$user = (isset($_GET['key'])) ? \App\Models\User\Key::where('key',$_GET['key'])->first()->user : \Auth::user();
	return $user;
}

function checkParam($param, $v4Style = null, $optional = false)
{
	if(strpos($param, '|') !== false) {
		$url_params = explode('|',$param);
		foreach($url_params as $param) {
			$url_param = (isset($_GET[$param])) ? $_GET[$param] : false;
			if($url_param) {break;}
		}
	} else {
		$url_param = (isset($_GET[$param])) ? $_GET[$param] : false;
	}

	$url_header = request()->header($param);

	if($v4Style) return $v4Style;
	if(!$url_param AND !$url_header) {
		if($optional != "optional") {
			\Log::channel('errorlog')->error(["Missing Param '$param", 422]);
			abort(422, "You need to provide the missing parameter '$param'. Please append it to the url or the request Header.");
		}
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
		default: return $language->where('glotto_id', $id)->orWhere('name',$id)->first();
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

function fetchSwaggerSchema($schema, $version = "v4") {
	$arrContextOptions = (env('APP_ENV') == "local") ? stream_context_create([ "ssl" => ["verify_peer"=>false, "verify_peer_name"=>false]]) : stream_context_create([]);

	$swagger['url'] = env('APP_URL') . "/swagger_$version.json";
	$swagger['response'] = json_decode(file_get_contents($swagger['url'], false, $arrContextOptions), true);
	if(!isset($swagger['response']['components']['schemas'][$schema])) return $swagger;
	$swagger['schema'] = $swagger['response']['components']['schemas'][$schema];
	$swagger['field_names'] = array_keys($swagger['schema']['properties']);
	$swagger = collect($swagger);

	return $swagger;
}

function fetchRandomBibleID() {
	$bible = collect(\DB::connection('sophia')->select('SHOW TABLES'))->pluck('Tables_in_sophia')->filter(function ($value, $key) {
		return (strpos($value, '_vpl') !== false) ? $value : false;
	})->random(1)->first();
	return substr($bible,0,-4);
}

function fetchBible($bible_id)
{
	$bibleEquivalent = \App\Models\Bible\BibleEquivalent::where('equivalent_id',$bible_id)->orWhere('equivalent_id',substr($bible_id,0,7))->first();
	if(!isset($bibleEquivalent)) return \App\Models\Bible\Bible::find($bible_id);
	if(isset($bibleEquivalent) AND !isset($bible)) return $bibleEquivalent->bible;
	if(!$bible) return [];
}

function fetchVernacularNumbers($script,$iso,$start_number,$end_number)
{
	$numbers = \App\Models\Language\AlphabetNumber::where('script_id',$script)->get()->keyBy('numeral')->ToArray();

	// Run through the numbers and return the vernaculars
	$current_number = $start_number;
	while($end_number >= $current_number) {
		$number_vernacular = "";
		// If it's not supported by the system return "normal" numbers
		if(empty($numbers)) {
			$number_vernacular = $current_number;
		} else {
			foreach(str_split($current_number) as $i) {
				if(isset($numbers[$i]['numeral_vernacular'])) $number_vernacular .= $numbers[$i]['numeral_vernacular'];
			}
		}
		$out_numbers[] = [
			"numeral"            => intval($current_number),
			"numeral_vernacular" => $number_vernacular
		];
		$current_number++;
	}
	return collect($out_numbers)->pluck('numeral_vernacular','numeral');
}


if( ! function_exists('unique_random') ){
	/**
	 *
	 * Generate a unique random string of characters
	 * uses str_random() helper for generating the random string
	 *
	 * @param     $table - name of the table
	 * @param     $col - name of the column that needs to be tested
	 * @param int $chars - length of the random string
	 *
	 * @return string
	 */
	function unique_random($table, $col, $chars = 16){

		$unique = false;

		// Store tested results in array to not test them again
		$tested = [];

		do{

			// Generate random string of characters
			$random = str_random($chars);

			// Check if it's already testing
			// If so, don't query the database again
			if( in_array($random, $tested) ){
				continue;
			}

			// Check if it is unique in the database
			$count = DB::table($table)->where($col, '=', $random)->count();

			// Store the random character in the tested array
			// To keep track which ones are already tested
			$tested[] = $random;

			// String appears to be unique
			if( $count == 0){
				// Set unique to true to break the loop
				$unique = true;
			}

			// If unique is still false at this point
			// it will just repeat all the steps until
			// it has generated a random string of characters

		}
		while(!$unique);


		return $random;
	}

}