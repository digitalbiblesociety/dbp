<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\Book;
use App\Models\Bible\Text;
use App\Models\Bible\TextConcordance;
use App\Models\Language\AlphabetFont;
use App\Transformers\FontsTransformer;
use App\Transformers\TextTransformer;

use Illuminate\Http\Request;
use App\Http\Controllers\APIController;

class TextController extends APIController
{
    /**
     * Display a listing of the Verses
     *
     * @return JSON|View
     */
    public function index()
    {
    	// Fetch and Assign $_GET params
    	$bible_id = checkParam('dam_id');
	    $book_id = checkParam('book_id');
	    $chapter = checkParam('chapter_id');
    	$verse_start = checkParam('verse_start', null, 'optional') ?? 1;
	    $verse_end = checkParam('verse_end', null, 'optional');

	    // Fetch Bible for Book Translations
	    $bibleEquivalent = BibleEquivalent::where('equivalent_id',$bible_id)->first();
	    if(!isset($bibleEquivalent)) $bibleEquivalent = BibleEquivalent::where('equivalent_id',substr($bible_id,0,7))->first();
	    if(!isset($bibleEquivalent)) $bible = Bible::find($bible_id);
	    if(isset($bibleEquivalent) AND !isset($bible)) $bible = $bibleEquivalent->bible;

	    $book = Book::where('id',$book_id)->orWhere('id_usfx',$book_id)->orWhere('id_osis',$book_id)->first();

	    // Fetch Verses
		$verses = Text::with('book')->with(['book.translations' => function ($query) use ($bible,$book) {
			$query->where('iso', $bible->iso);
			$query->where('book_id', $book->id);
		}])
		->where([
			['bible_id',$bible_id],
			['book_id',$book->id],
			['chapter_number',$chapter],
			['verse_start', '>=', $verse_start],
		])
		->where([
			['bible_variation_id',$bible_id],
			['book_id',$book->id],
			['chapter_number',$chapter],
			['verse_start', '>=', $verse_start],
		])->when($verse_end, function ($query) use ($verse_end) {
			return $query->where('verse_end', '<=', $verse_end);
		})->get();
		return $verses;
		if(count($verses) == 0) return $this->setStatusCode(404)->replyWithError("No Verses Were found with the provided params");
		return $this->reply(fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer)->toArray());
    }

	public function text($id,$book,$chapter)
	{
		// Allow Users to pick the format of response they'd like to have
		$format = @$_GET['format'];

		$table = strtoupper($id).'_vpl';

		// if chapter value is a range, handle that
		if(str_contains($chapter, '-')) {
			$range = explode('-',$chapter);
			$verses = \DB::connection('dbp')->table($table)
			             ->where('book',$book)
			             ->where('chapter','>=',$range[0])
			             ->where('chapter','<=',$range[1])->get();
		} else {
			$verses = \DB::connection('dbp')->table($table)
			             ->where('book',$book)
			             ->where('chapter',$chapter)->get();
		}

		// format the response
		switch($format) {
			case "HTML":
				$output['data'] = $this->textHTML($verses);
				break;
			case "JSON":
				$output['data'] = $verses;
				break;
			default:
				$output['data'] = $this->textDefault($verses);
		}

		// mix in some meta data
		$output['metadata'] = [
			'bible_id' => $id,
			'book_id' => $book,
			'chapter' => array_unique($verses->pluck('chapter')->ToArray())
		];

		// reply
		return $this->reply($output);
	}

	private function textDefault($verses) {
		foreach ($verses as $verse) {
			if($verse->verse_start != $verse->verse_end) {
				$output[] = $verse->verse_start."-".$verse->verse_end." ".$verse->verse_text;
			} else {
				$output[] = $verse->verse_start." ".$verse->verse_text;
			}
		}
		return implode($output);
	}

	private function textHTML($verses) {
		foreach ($verses as $verse) {
			if($verse->verse_start != $verse->verse_end) {
				$output[] = "<sup>".$verse->verse_start."-".$verse->verse_end."&nbsp;</sup><p>".$verse->verse_text."</p>";
			} else {
				$output[] = "<sup>".$verse->verse_start."&nbsp;</sup><p>".$verse->verse_text."</p>";
			}
		}

		return implode($output);
	}

	/**
	 * Display a listing of the Fonts
	 *
	 * @return JSON|View
	 */
    public function fonts()
    {
	    $id = checkParam('id', null, 'optional'); //(optional) The numeric ID of the font to retrieve
		$name = checkParam('name', null, 'optional'); //(optional) Search for a specific font by name
		$platform = checkParam('platform', null, 'optional') ?? 'all'; //(optional) Only return fonts that have been authorized for the specified platform. Available values are: "android", "ios", "web", or "all"

	    if($name) {
	    	$font = AlphabetFont::where('name',$name)->first();
	    } else {
		    $font = ($id) ? AlphabetFont::find($id) : false;
	    }
	    if($font) return $this->reply(fractal()->item($font)->transformWith(new FontsTransformer())->serializeWith($this->serializer)->toArray());

		$fonts = AlphabetFont::all();
		return $this->reply(fractal()->collection($fonts)->transformWith(new FontsTransformer())->serializeWith($this->serializer)->toArray());
    }

	/**
	 *
	 *
	 * @return View|JSON
	 */
	public function search()
    {
	    // If it's not an API route send them to the documentation
	    if(!$this->api) return view('docs.v2.text_search');

	    $query = checkParam('query');
	    $exclude = checkParam('exclude', null, 'optional');
	    $bible_id = checkParam('dam_id');
	    $limit = checkParam('limit', null, 'optional') ?? 15;

/*
	    if (strpos($query, ' ') !== false) {
	    	$query = explode(' ', $query);
	    	foreach($query as $word) $sql_search[] = ['verse_text', 'LIKE', '%'.$word.'%'];

	    	// Handle Possible Excludes

	    	if($exclude) {
	    		if(strpos($exclude, ' ') !== false) {
				    $excludes = explode(' ', $exclude);
				    foreach($excludes as $excluded) $sql_search[] = ['verse_text', 'NOT LIKE', '%'.$excluded.'%'];
			    } else {
				    $sql_search[] = ['verse_text', 'NOT LIKE', '%'.$exclude.'%'];
			    }
		    }

		    $verses = Text::with('book')->where('bible_id',$bible_id)->where($sql_search)->take($limit)->get();
	    } else {
		    $verses = Text::with('book')->where('bible_id',$bible_id)->where('verse_text', 'LIKE', '%'.$query.'%')->take($limit)->get();
	    }
*/

		$query = \DB::connection()->getPdo()->quote('+'.str_replace(' ',' +',$query).' -'.$exclude);
	    $verses = Text::with('book')->where('bible_id',$bible_id)->whereRaw(\DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))->limit($limit)->get();

		return $this->reply(fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer));
    }

	/**
	 * This one actually departs from Version 2 and only returns the book ID and the integer count
	 *
	 * @return View|JSON
	 */
	public function searchGroup()
    {
	    // If it's not an API route send them to the documentation
	    if(!$this->api) return view('docs.v2.text_search_group');

	    $exclude = checkParam('exclude', null, 'optional');
	    $query = checkParam('query');
	    $bible_id = checkParam('dam_id');

	    if($exclude) $exclude = ' -'.$exclude;
	    $query = \DB::connection()->getPdo()->quote('+'.str_replace(' ',' +',$query).$exclude);

	    $verses = Text::with('book')->where('bible_id',$bible_id)->whereRaw(\DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))->select('book_id')->get();

	    foreach($verses->groupBy('book_id') as $key => $verse) $verseCount[$key] = $verse->count();

	    return $this->reply($verseCount);
    }


}
