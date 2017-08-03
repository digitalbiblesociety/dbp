<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleEquivalent;
use App\Models\Language\Language;
use \database\seeds\SeederHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use App\Transformers\BibleTransformer;
class BiblesController extends APIController
{

    /**
     * Display a listing of the bibles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Language $language,$country = null, $publisher = null)
    {
    	$language = $language->fetchByID();

	    if($this->api) {
		    $bibles = Bible::when($language, function ($query) use ($language) {
			    return $query->where('glotto_id', $language->id);
		    })->get();
		    //dd($bibles);
		    return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->toArray());
	    }
	    return view('bibles.index');
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
