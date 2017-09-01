<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\BibleOrganization;
use App\Models\Bible\Book;
use App\Models\Bible\BookCode;
use App\Models\Language\Language;
use \database\seeds\SeederHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use App\Transformers\BibleTransformer;
use Spatie\Fractalistic\ArraySerializer;
use Symfony\Component\Yaml\Yaml;
class BiblesController extends APIController
{

    /**
     * Display a listing of the bibles.
     *
     * @return JSON|View
     */
    public function index(Language $language, $country = null, $publisher = null)
    {
    	// First Handle API
	    if($this->api) {
		    $language = $language->fetchByID();
		    $organization = checkParam('organization',null,'optional');
	    	if($organization) {
			    $bibles = Bible::when($language, function ($query) use ($language) {
				    return $query->where('glotto_id', $language->id);
			    })->whereHas('organizations', function($q) use ($organization){
				    $q->where('organization_id', '>=', $organization);
			    })->get();
		    } else {
			    $bibles = Bible::with('translations')->when($language, function ($query) use ($language) {
				    return $query->where('glotto_id', $language->id);
			    })->get();
		    }

		    return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer)->toArray());
	    }

	    // If it's not an API request check to see if the user is logged in
	    $user = \Auth::user();
	    if($user) return view('bibles.index',compact('user'));

	    // At last fall back to public view
	    return view('bibles.index');
    }

	/**
	 * A Route to Review The Last 500 Recent Changes to The Bible Resources
	 *
	 * @return JSON|View
	 */
	public function history()
    {
    	if($this->api) {
		    $limit = $_GET['limit'] ?? 500;
		    $bibles = Bible::select('id','updated_at')->take($limit)->get();
		    return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith(new ArraySerializer())->toArray());
	    }

        return view('bibles.history');
    }

	/**
	 * Language Names
	 *
	 * @return JSON|View
	 */
	public function languageNames()
    {
    	$languageNames = checkParam('language_names');
    	$languageNames = explode(',',$languageNames);
    	foreach($languageNames as $language_name) {
    		$language = Language::where('name',$language_name)->first();
    		if(!$language) continue;
    		foreach ($language->bibles as $bible) {
    			foreach ($bible->dbp as $connection) {
    				$dbp[$language->name][] = $connection->equivalent_id;
			    }
		    }
	    }
		return $dbp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
    }

    /**
     * Description:
     * Display the bible meta data for the specified ID.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    if($this->api) {
		    $bible = Bible::find($id);
		    if(!$bible) return $this->setStatusCode(404)->replyWithError("Bible not found for ID: $id");
		    return $this->reply(fractal()->collection($bible)->transformWith(new BibleTransformer())->toArray());
	    }
	    return view('bibles.show');
    }

	public function books()
	{
		$books = Book::select('id','book_order','name')->orderBy('book_order')->get();
		if($this->api) return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer)->toArray());
		return view('bibles.books.index');
	}


	/**
	 * Display the equivalents for this resource.
	 *
	 * @param  string $id
	 * @return \Illuminate\Http\Response
	 */
	public function equivalents(string $id)
	{
		$equivalents = BibleEquivalent::where('abbr',$id)->get();
		return $this->reply($equivalents);
		//return view('bibles.books.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function book($id)
	{
		$book = Book::with('codes','translations')->find($id);
		if($this->api) return $this->reply(fractal()->item($book)->transformWith(new BooksTransformer)->toArray());
		return view('bibles.books.show');
	}

	/**
	 * Description:
	 * Display the bible meta data for the specified ID.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$bible = Bible::find($id);
		if($this->api) return $this->reply(fractal()->collection($bible)->transformWith(new BibleTransformer())->toArray());
		return view('bibles.edit',compact('bible'));
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
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('bibles.create');
	}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
