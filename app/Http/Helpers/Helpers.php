<?php

function bookCodeConvert($code = null, $source_type = null, $destination_type = null) {
	$book = BookCode::where('type',$source_type)->where('code',$code)->first();
	return BookCode::where('type',$destination_type)->where('book_id',$book->book_id)->first()->code;
}

function checkParam($param, $v4Style = null)
{
	if($v4Style) return $v4Style;
	if(!isset($_GET[$param])) abort(422, "You need to provide the missing parameter '$param'. Please append it to the url.");
	return $_GET[$param];
}

