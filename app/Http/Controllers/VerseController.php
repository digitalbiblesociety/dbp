<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\Book;
use App\Models\Bible\Text;

class VerseController extends APIController
{
    public function info()
    {
    	$bible_id = checkParam('bible_id');
	    $book_id = checkParam('book_id');
	    $chapter_id = checkParam('chapter');
	    $verse_start = checkParam('verse_start');
		$verse_end = checkParam('verse_end', null, true);

		$bible = Bible::find($bible_id);
		$book = Book::where('id',$book_id)->orWhere('id_usfx',$book_id)->first();

		$verse_info = \DB::connection('sophia')->table($bible->id.'_vpl')->where([
			['book',            '=',  $book->id_usfx],
			['chapter',         '=',  $chapter_id],
			['verse_start',     '>=', $verse_start]
		])->when($verse_end, function ($query) use ($verse_end) {
			return $query->where('verse_start', '<=', $verse_end);
		})->select(['book as book_id','chapter as chapter_number','verse_start','verse_end','verse_text','canon_order as id'])->get();
		foreach($verse_info as $key => $verse) {
			$verse_info[$key]->bible_id = $bible->id;
			$verse_info[$key]->bible_variation_id = null;
		}
		return $this->reply($verse_info);
    }
}
