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
	    $bibleEquivalent = BibleEquivalent::where('equivalent_id',substr($bible_id,0,7))->first();
	    if(!$bibleEquivalent) {
	    	$bible = Bible::find($bible_id);
	    } else {
	    	$bible = $bibleEquivalent->bible;
	    }

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
		])->when($verse_end, function ($query) use ($verse_end) {
			return $query->where('verse_end', '<=', $verse_end);
		})->get();
		return $verses;
		if(count($verses) == 0) return $this->setStatusCode(404)->replyWithError("No Verses Were found with the provided params");
		return $this->reply(fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer)->toArray());
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

	    if($name) $font = AlphabetFont::where('name',$name)->first();
	    if($id) $font = AlphabetFont::find($id);
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
	    $bible_id = checkParam('dam_id');
	    $limit = checkParam('limit', null, 'optional') ?? 50;

	    $verses = Text::with('book')->where('bible_id',$bible_id)->where('verse_text', 'LIKE', '%'.$query.'%')->take($limit)->get();
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

	    $query = checkParam('query');
	    $bible_id = checkParam('dam_id');

	    $verses = Text::with('book')->where('bible_id',$bible_id)->where('verse_text', 'LIKE', "%$query%")->select('book_id')->get();
	    foreach($verses->groupBy('book_id') as $key => $verse) $verseCount[$key] = $verse->count();

	    return $this->reply($verseCount);
    }


}