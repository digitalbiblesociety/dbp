<?php

namespace App\Http\Controllers;

use App\Helpers\AWS\Bucket;
use App\Models\Bible\Bible;
use App\Models\Bible\Book;
use App\Models\Bible\BibleEquivalent;
use App\Models\Language\AlphabetFont;
use App\Transformers\FontsTransformer;
use App\Transformers\TextTransformer;
use DB;
use Illuminate\Support\Facades\Storage;

class TextController extends APIController
{
	/**
	 * Display a listing of the Verses
	 * Will either parse the path or query params to get data before passing it to the bible_equivalents table
	 *
	 * @param string|null $bible_url_param
	 * @param string|null $book_url_param
	 * @param string|null $chapter_url_param
	 *
	 * @return JSON|View
	 */
    public function index($bible_url_param = null, $book_url_param = null,$chapter_url_param = null)
    {
    	// Fetch and Assign $_GET params
    	$bible_id = checkParam('dam_id', $bible_url_param);
	    $book_id = checkParam('book_id', $book_url_param);
	    $chapter = checkParam('chapter_id', $chapter_url_param);
    	$verse_start = checkParam('verse_start', null, 'optional') ?? 1;
	    $verse_end = checkParam('verse_end', null, 'optional');
	    $formatted = checkParam('bucket_id', null, 'optional');

	    // Fetch Bible for Book Translations
	    $bibleEquivalent = BibleEquivalent::where('equivalent_id',$bible_id)->orWhere('equivalent_id',substr($bible_id,0,7))->first();
	    if(!isset($bibleEquivalent)) $bible = Bible::find($bible_id);
	    if(isset($bibleEquivalent) AND !isset($bible)) $bible = $bibleEquivalent->bible;
	    if(!$bible) {
	    	if($this->v > 4) return [];
	    	return $this->setStatusCode(404)->replyWithError("Bible ID not Found");
	    }

	    $book = Book::where('id',$book_id)->orWhere('id_usfx',$book_id)->orWhere('id_osis',$book_id)->first();
	    if(!$book) return $this->setStatusCode(422)->replyWithError('Missing or Invalid Book ID');
	    $book->push('name_vernacular', $book->translation($bible->iso)->first());

	    if($formatted) {
		    $bibleEquivalent = (isset($bibleEquivalent)) ? $bibleEquivalent : $bible->id;
		    $path = 'text/'.$bible->id.'/'.$bibleEquivalent.'/'.$book_id.$chapter.'.html';
		    $exists = Storage::disk($formatted)->exists($path);
		    if(!$exists) return $this->replyWithError("The path: $path did not result in a valid file");
	    	return $this->reply(["filepath" => Bucket::signedUrl($path)]);
	    }

	    // Fetch Verses
		$verses = DB::connection('sophia')->table($bible->id.'_vpl')
		->where([['book',$book->id_usfx], ['chapter',$chapter]])
		->when($verse_start, function ($query) use ($verse_start) {
			return $query->where('verse_end', '>=', $verse_start);
		})
		->when($verse_end, function ($query) use ($verse_end) {
			return $query->where('verse_end', '<=', $verse_end);
		})->get();
	    $this->addMetaDataToVerses($verses,$bible_id);

	    if(count($verses) == 0) return $this->setStatusCode(404)->replyWithError("No Verses Were found with the provided params");
		return $this->reply(fractal()->collection($verses)->transformWith(new TextTransformer())->serializeWith($this->serializer)->toArray());
    }

    public function formattedResponse()
    {
	    $bible_id = checkParam('dam_id');
	    $book_id = checkParam('book_id');
	    $chapter = checkParam('chapter_id');

	    $url = "https://s3-us-west-2.amazonaws.com/dbp-dev/text/".$bible_id."/".$bible_id."/".$book_id.$chapter.".html";
	    $chapter = Bucket::get($url);
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

		$query = DB::connection('sophia')->getPdo()->quote('+'.str_replace(' ',' +',$query).' -'.$exclude);
	    $verses = DB::connection('sophia')->table($bible_id.'_vpl')->whereRaw(DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))->limit($limit)->get();
	    $this->addMetaDataToVerses($verses,$bible_id);

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
	    $query = DB::connection('sophia')->getPdo()->quote('+'.str_replace(' ',' +',$query).$exclude);
	    $verses = DB::connection('sophia')->table($bible_id.'_vpl')->whereRaw(DB::raw("MATCH (verse_text) AGAINST($query IN NATURAL LANGUAGE MODE)"))->select('book_id')->get();
	    $this->addMetaDataToVerses($verses,$bible_id);

	    foreach($verses->groupBy('book_id') as $key => $verse) $verseCount[$key] = $verse->count();

	    return $this->reply($verseCount);
    }

    public function addMetaDataToVerses($verses,$bible_id)
    {
	    $books = Book::whereIn('id_usfx',$verses->pluck('book'))->get();

	    // Fetch Bible for Book Translations
	    $bibleEquivalent = BibleEquivalent::where('equivalent_id',$bible_id)->orWhere('equivalent_id',substr($bible_id,0,7))->first();
	    if(!isset($bibleEquivalent)) $bible = Bible::find($bible_id);
	    if(isset($bibleEquivalent) AND !isset($bible)) $bible = $bibleEquivalent->bible;
	    if($bible) {
		    if($bible->script != "Latn") {
			    $vernacular_numbers[] = $verses->pluck('verse_start')->ToArray();
			    $vernacular_numbers[] = $verses->pluck('verse_end')->ToArray();
			    $vernacular_numbers[] = $verses->first()->chapter;
			    $vernacular_numbers = array_unique(array_flatten($vernacular_numbers));
			    $vernacular_numbers = fetchVernacularNumbers($bible->script,$bible->iso,min($vernacular_numbers),max($vernacular_numbers));
		    }
	    }
	    // Fetch Vernacular Number
	    $verses->map(function ($verse) use ($books,$bible_id,$vernacular_numbers) {
		    $currentBook = $books->where('id_usfx',$verse->book)->first();
		    $verse->bible_id = $bible_id;
		    $verse->usfm_id = $currentBook->id;
		    $verse->osis_id = $currentBook->id_osis;
		    $verse->book_order = ltrim(substr($verse->canon_order,0,3),"0");
		    $verse->book_vernacular_name = $currentBook->name;
		    $verse->book_name = $currentBook->name;
		    $verse->chapter_vernacular =  isset($vernacular_numbers[$verse->chapter]) ? $vernacular_numbers[$verse->chapter] : $verse->chapter;
		    $verse->verse_start_vernacular =  isset($vernacular_numbers[$verse->chapter]) ? $vernacular_numbers[$verse->verse_start] : $verse->verse_start;
		    $verse->verse_end_vernacular =  isset($vernacular_numbers[$verse->chapter]) ? $vernacular_numbers[$verse->verse_end] : $verse->verse_end;
		    return $verse;
	    });
	    return $verses;
    }


}
