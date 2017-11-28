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
			$selection = \DB::connection('sophia')->table($bible_id.'_vpl')->orderBy('canon_order')->first();
			$book_id = $selection->book;
			$chapter = $selection->chapter;
		}
		if(!$chapter) {
			$selection = \DB::connection('sophia')->table($bible_id.'_vpl')->orderBy('canon_order')->first();
			$chapter = $selection->chapter;
		}

		$bibleNavigation =  \DB::connection('sophia')->table($bible_id.'_vpl')->select('book','chapter')->distinct()->get()->groupBy('book');
		$bibleLanguages = Bible::with('currentTranslation','language')->has('text')->get()->groupBy('language.name');
		$verses = \DB::connection('sophia')->table($bible_id.'_vpl')->select(['book','verse_start','verse_text','chapter'])->where('book', $book_id)->where('chapter',$chapter)->orderBy('verse_start')->get();
		$query = false;
		return view('bibles.chapter',compact('verses','bibleLanguages','bibleNavigation','query','bible_id'));
	}

	public function search(Request $request)
	{
		$query = $request->search;
		$bible_id = $request->bible_id;
		$limit = 100;

		$bibleNavigation =  \DB::connection('sophia')->table($bible_id.'_vpl')->select('book','chapter')->distinct()->get()->groupBy('book');
		$bibleLanguages = Bible::with('currentTranslation','language')->has('text')->get()->groupBy('language.name');
		$search = \DB::connection()->getPdo()->quote('+'.str_replace(' ',' +',$query));
		$verses = \DB::connection('sophia')->table($bible_id.'_vpl')->whereRaw(\DB::raw("MATCH (verse_text) AGAINST($search IN NATURAL LANGUAGE MODE)"))->limit($limit)->get();
		return view('bibles.chapter',compact('verses','bibleLanguages','bibleNavigation','query','bible_id'));
	}

}
