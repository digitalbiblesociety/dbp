<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\Text;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	// Fetch and Assign $_GET params
    	$bible_id = checkParam('bible_id');
	    $book_id = checkParam('book_id');
	    $chapter = checkParam('chapter');
    	$verse_start = checkParam('verse_start', null, 'optional') ?? 1;
	    $verse_end = checkParam('verse_end', null, 'optional');

	    // Fetch Bible for Book Translations
	    $bible = Bible::find($bible_id);

	    // Fetch Verses
		$verses = Text::with('book.osis')->with(['book.translations' => function ($query) use ($bible,$book_id) {
			$query->where('iso', $bible->iso);
			$query->where('book_id', $book_id);
		}])
		->where([
			['bible_id',$bible_id],
			['book_id',$book_id],
			['chapter_number',$chapter],
			['verse_start', '>=', $verse_start],
		])->when($verse_end, function ($query) use ($verse_end) {
			return $query->where('verse_end', '>=', $verse_end);
		})->get();
		if(count($verses) == 0) return $this->setStatusCode(404)->replyWithError("No Verses Were found with the provided params");
		return $this->reply(fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer)->toArray());
    }

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

}
