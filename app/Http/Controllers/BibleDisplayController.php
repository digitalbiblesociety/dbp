<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bible\Text;
use App\Models\Bible\Bible;

class BibleDisplayController extends Controller
{

	public function chapter($bible_id = "ENGESV", $book_id = null, $chapter = null) {
		// handle starting routes
		if(!$book_id) {
			$selection = Text::where('bible_id',$bible_id)->orderBy('id')->first();
			$book_id = $selection->book_id;
			$chapter = $selection->chapter_number;
		}
		if(!$chapter) {
			$selection = Text::where('bible_id',$bible_id)->orderBy('id')->first();
			$chapter = $selection->chapter_number;
		}

		$bibleNavigation = Text::with('book')->select('book_id','chapter_number','bible_id')->distinct()->where('bible_id',$bible_id)->get()->groupBy('book.name');
		$bibleLanguages = Bible::with('currentTranslation','language')->has('text')->get()->groupBy('language.name');
		$verses = Text::select(['bible_id','book_id','verse_start','verse_text','chapter_number'])->where('bible_id',$bible_id)->where('book_id', $book_id)->where('chapter_number',$chapter)->orderBy('verse_start')->get();
		$query = false;
		return view('bibles.chapter',compact('verses','bibleLanguages','bibleNavigation','query'));
	}

	public function search(Request $request)
	{
		$query = $request->search;
		$bible_id = $request->bible_id;
		$limit = 100;

		$bibleNavigation = Text::with('book')->select('book_id','chapter_number','bible_id')->distinct()->where('bible_id',$bible_id)->get()->groupBy('book.name');
		$bibleLanguages = Bible::with('currentTranslation','language')->has('text')->get()->groupBy('language.name');
		$search = \DB::connection()->getPdo()->quote('+'.str_replace(' ',' +',$query));
		$verses = Text::with('book')->where('bible_id',$bible_id)->whereRaw(\DB::raw("MATCH (verse_text) AGAINST($search IN NATURAL LANGUAGE MODE)"))->limit($limit)->get();
		return view('bibles.chapter',compact('verses','bibleLanguages','bibleNavigation','query'));
	}

}
