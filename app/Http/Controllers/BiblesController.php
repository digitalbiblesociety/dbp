<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\BibleOrganization;
use App\Models\Bible\Book;
use App\Models\Bible\BookCode;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationTranslation;
use \database\seeds\SeederHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use App\Transformers\BibleTransformer;
use Spatie\Fractalistic\ArraySerializer;
use Symfony\Component\Yaml\Yaml;
use Validator;

class BiblesController extends APIController
{

    /**
     * Display a listing of the bibles.
     *
     * @return JSON|View
     */
    public function index(Language $language, $country = null, $publisher = null)
    {
	    // Return the documentation if it's not an API request
	    if(!$this->api) return view('bibles.index');

		$language = fetchLanguage(checkParam('language',null,'optional'));
		$organization = checkParam('organization',null,'optional');

		$bibles = Bible::with('translations','language.parent','alphabet','dbp')->when($language, function ($query) use ($language) {
				return $query->where('glotto_id', $language->id);
		    })->when($organization, function($q) use ($organization){
			    $q->where('organization_id', '>=', $organization);
		    })->get();
		return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer)->toArray());

    }

	/**
	 * A Route to Review The Last 500 Recent Changes to The Bible Resources
	 *
	 * @return JSON|View
	 */
	public function history()
    {
    	if(!$this->api) return view('bibles.history');

		$limit = checkParam('limit',null,'optional') ?? 500;
		$bibles = Bible::select('id','updated_at')->take($limit)->get();
		return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith(new ArraySerializer())->toArray());
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
    			foreach ($bible->dbp as $connection) $dbp[$language->name][] = $connection->equivalent_id;
		    }
	    }
		return $dbp;
    }

	public function libraryVersion()
	{
		return $this->reply(json_decode(file_get_contents(public_path('static/version_listing.json'))));
	}

	public function libraryMetadata()
	{
		$dam_id = checkParam('dam_id', null, 'optional');
		$bible = Bible::has('text')->when($dam_id, function ($query) use ($dam_id) {
			return $query->where('id', $dam_id);
		})->get();
		return $this->reply(fractal()->collection($bible)->transformWith(new BibleTransformer())->toArray());
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

	    $validator = Validator::make($request->all(), [
		    'id'                      => 'required|unique:bibles,id|max:24',
		    'iso'                     => 'required|exists:languages,iso',
		    'translations.*.name'     => 'required',
		    'translations.*.iso'      => 'required|exists:languages,iso',
		    'date'                    => 'integer',
	    ]);

	    if($validator->fails()) return redirect('bibles/create')->withErrors($validator)->withInput();

	    $bible = \DB::transaction(function () use($request) {
		    $bible = new Bible();
		    $bible = $bible->create($request->only(['id','date','script','portions','copyright','derived','in_progress','notes','iso']));
		    $bible->translations()->createMany($request->translations);
		    $bible->organizations()->attach($request->organizations);
		    $bible->equivalents()->createMany($request->equivalents);
		    $bible->links()->createMany($request->links);

		    return $bible;
	    });

	    return redirect()->route('view_bibles.show', ['id' => $bible->id]);
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
	    $bible = Bible::find($id);
	    if(!$bible) return $this->setStatusCode(404)->replyWithError("Bible not found for ID: $id");

    	if(!$this->api) return view('bibles.show',compact('bible'));
	    
		return $this->reply(fractal()->collection($bible)->transformWith(new BibleTransformer())->toArray());
    }

	public function books()
	{
		if(!$this->api) return view('bibles.books.index');

		$books = Book::select('id','book_order','name')->orderBy('book_order')->get();
		return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer)->toArray());
	}


	/**
	 * Display the equivalents for this resource.
	 *
	 * @param  string $id
	 * @return \Illuminate\Http\Response
	 */
	public function equivalents(string $id)
	{
		if(!$this->api) return view('bibles.books.index');

		$equivalents = BibleEquivalent::where('abbr',$id)->get();
		return $this->reply($equivalents);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return View|JSON
	 */
	public function book($id)
	{
		if(!$this->api) return view('bibles.books.show');

		$book = Book::with('codes','translations')->find($id);
		return $this->reply(fractal()->item($book)->transformWith(new BooksTransformer)->toArray());
	}

	/**
	 * Description:
	 * Display the bible meta data for the specified ID.
	 *
	 * @param  int  $id
	 * @return View|JSON
	 */
	public function edit($id)
	{
		if(!$this->api) return view('bibles.edit');

		$bible = Bible::find($id);
		return $this->reply(fractal()->collection($bible)->transformWith(new BibleTransformer())->toArray());
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
		$languages = Language::select('iso','name')->get();
		$organizations = OrganizationTranslation::select('name','organization_id')->where('language_iso','eng')->get();
		$alphabets = Alphabet::select('script')->get();
		return view('bibles.create',compact('languages', 'organizations', 'alphabets'));
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
