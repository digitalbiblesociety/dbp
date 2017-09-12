<?php

namespace App\Http\Controllers;

use App\Models\Bible\Text;
use Illuminate\Http\Request;

class VerseController extends Controller
{
    public function info()
    {
    	$bible_id = checkParam('bible_id');
	    $book_id = checkParam('book_id');
	    $chapter_id = checkParam('chapter');
	    $verse_start = checkParam('verse_start');
		$verse_end = checkParam('verse_end', null, true);

		$verse_info = Text::where([
			['bible_id',       '=',  $bible_id],
			['book_id',        '=',  $book_id],
			['chapter_number', '=',  $chapter_id],
			['verse_start',    '>=', $verse_start]
		])->when($verse_end, function ($query) use ($verse_end) {
			return $query->where('verse_start', '<=', $verse_end);
		})->get();
		return $verse_info;
    }
}
