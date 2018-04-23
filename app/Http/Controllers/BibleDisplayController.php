<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bible\Text;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\Bible;

class BibleDisplayController extends Controller
{

	/**
	 * Returns an index page of all the Bibles
	 *
	 * @version 4
	 * @category ui_bibleDisplay_read.index
	 * @link http://bible.build/read/ - V4 Access
	 * @link http://dbp.dev/read/ - V4 Test Access
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$filesets = BibleFileset::with('bible.translations')->where('bucket_id', env('FCBH_AWS_BUCKET'))->where('set_type_code','text_plain')->get();
		return view('bibles.reader.index',compact('filesets'));
	}

	/**
	 * Displays a Chapter View
	 *
	 * @version 4
	 * @category ui_bibleDisplay_read
	 * @link http://bible.build/read/ - V4 Access
	 * @link http://dbp.dev/read/ - V4 Test Access
	 *
	 * @return \Illuminate\View\View
	 *
	 */
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
		$verses = \DB::connection('sophia')->table($bible_id.'_vpl')->select(['book','verse_start','verse_text','chapter'])->where('book', $book_id)->where('chapter',$chapter)->orderBy('verse_start')->get();
		$query = false;

		return view('bibles.reader.chapter',compact('verses','bibleLanguages','bibleNavigation','query','bible_id'));
	}

	/**
	 * Handles Javascript-less Search
	 *
	 * @version 4
	 * @category ui_bibleDisplay_search
	 * @link http://bible.build/search/ - V4 Access
	 * @link http://dbp.dev/search/ - V4 Test Access
	 *
	 * @return \Illuminate\View\View
	 *
	 */
	public function search(Request $request, $bible_id)
	{
		$query = $request->search;
		$bible_id = $bible_id ?? $request->bible_id;
		$limit = 100;

		$search = \DB::connection()->getPdo()->quote('+'.str_replace(' ',' +',$query));
		$verses = \DB::connection('sophia')->table($bible_id.'_vpl')->whereRaw(\DB::raw("MATCH (verse_text) AGAINST($search IN NATURAL LANGUAGE MODE)"))->limit($limit)->get();

		return view('bibles.reader.search',compact('verses','query','bible_id'));
	}

	/**
	 * Handles Javascript-less Navigation
	 *
	 * @version 4
	 * @category ui_bibleDisplay_read.bible
	 * @link http://bible.build/read/AMKWBT - V4 Access
	 * @link http://dbp.dev/read/AMKWBT - V4 Test Access
	 *
	 * @return \Illuminate\View\View
	 *
	 */
	public function navigation($bible_id)
	{
		$bibleNavigation =  \DB::connection('sophia')->table($bible_id.'_vpl')->select('book','chapter')->distinct()->get()->groupBy('book');
		return view('bibles.reader.bibleNav',compact('bibleNavigation','bible_id'));
	}

}
