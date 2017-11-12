<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\Book;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\OrganizationTranslation;
use App\Transformers\BibleTransformer;
use App\Transformers\BooksTransformer;

class BiblesController extends APIController
{


	/**
	 *
	 * Display a listing of the bibles.
	 *
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
    {
	    // Return the documentation if it's not an API request
	    if(!$this->api) return view('bibles.index');

		$language = fetchLanguage(checkParam('language',null,'optional'));
		$organization = checkParam('organization',null,'optional');
		$bible_id = checkParam('dam_id',null,'optional');

	    $bibles = Bible::with('translations','language.parent','alphabet','dbp')->when($language, function ($query) use ($language) {
			    return $query->where('glotto_id', $language->id);
		    })->when($organization, function($q) use ($organization){
			    $q->where('organization_id', '>=', $organization);
		    })->when($bible_id, function($q) use ($bible_id){
		        $q->where('id', '=', $bible_id);
	        })->get();

		return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer)->toArray());

    }


	/**
	 *
	 * A Route to Review The Last 500 Recent Changes to The Bible Resources
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function history()
    {
    	if(!$this->api) return view('bibles.history');

		$limit = checkParam('limit',null,'optional') ?? 500;
		$bibles = Bible::select(['id','updated_at'])->take($limit)->get();
		return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer)->toArray());
    }


	/**
	 * Language Names
	 *
	 * @return array
	 */
	public function languageNames()
    {
    	$languageNames = checkParam('language_names');
    	$languageNames = explode(',',$languageNames);
    	$dbp = [];
    	foreach($languageNames as $language_name) {
    		$language = Language::where('name',$language_name)->first();
    		if(!$language) continue;
    		foreach ($language->bibles as $bible) {
    			foreach ($bible->dbp as $connection) $dbp[$language->name][] = $connection->equivalent_id;
		    }
	    }
		return $dbp;
    }

	/**
	 * @return mixed
	 */
	public function libraryVersion()
	{
		return $this->reply(json_decode(file_get_contents(public_path('static/version_listing.json'))));
	}

	/**
	 * @return mixed
	 */
	public function libraryMetadata()
	{
		$dam_id = checkParam('dam_id', null, 'optional');
		$bible = Bible::has('text')->when(
			$dam_id, function ($query) use ($dam_id) {
			return $query->where('id', $dam_id);
		})->get();
		return $this->reply(fractal()->collection($bible)->serializeWith($this->serializer)->transformWith(new BibleTransformer())->toArray());
	}

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

	    request()->validate([
		    'id'                      => 'required|unique:bibles,id|max:24',
		    'iso'                     => 'required|exists:languages,iso',
		    'translations.*.name'     => 'required',
		    'translations.*.iso'      => 'required|exists:languages,iso',
		    'date'                    => 'integer',
	    ]);

	    $bible = \DB::transaction(function () {
		    $bible = new Bible();
		    $bible = $bible->create(request()->only(['id','date','script','portions','copyright','derived','in_progress','notes','iso']));
		    $bible->translations()->createMany(request()->translations);
		    $bible->organizations()->attach(request()->organizations);
		    $bible->equivalents()->createMany(request()->equivalents);
		    $bible->links()->createMany(request()->links);
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
	    $bible = Bible::with('filesets')->find($id);
	    if(!$bible) return $this->setStatusCode(404)->replyWithError("Bible not found for ID: $id");

    	if(!$this->api) return view('bibles.show',compact('bible'));
	    
		return $this->reply(fractal()->collection($bible)->transformWith(new BibleTransformer())->toArray());
    }

	public function books()
	{
		if(!$this->api) return view('bibles.books.index');

		$books = Book::select(['id','book_order','name'])->orderBy('book_order')->get();
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

		$equivalents = BibleEquivalent::where('bible_id',$id)->get();
		return $this->reply($equivalents);
	}


	public function book($id)
	{
		if(!$this->api) return view('bibles.books.show');

		$book = Book::with('codes','translations')->find($id);
		return $this->reply(fractal()->item($book)->transformWith(new BooksTransformer)->toArray());
	}


	public function edit($id)
	{
		$bible = Bible::with('translations.language')->find($id);
		if(!$this->api) {
			$languages = Language::select(['iso','name'])->orderBy('iso')->get();
			$organizations = OrganizationTranslation::select(['name','organization_id'])->where('language_iso','eng')->get();
			$alphabets = Alphabet::select('script')->get();
			return view('bibles.edit',compact('languages', 'organizations', 'alphabets','bible'));
		}

		return $this->reply(fractal()->collection($bible)->transformWith(new BibleTransformer())->toArray());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$languages = Language::select(['iso','name'])->get();
		$organizations = OrganizationTranslation::select(['name','organization_id'])->where('language_iso','eng')->get();
		$alphabets = Alphabet::select('script')->get();
		return view('bibles.create',compact('languages', 'organizations', 'alphabets'));
	}


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

	    request()->validate([
		    'id'                      => 'required|max:24',
		    'iso'                     => 'required|exists:languages,iso',
		    'translations.*.name'     => 'required',
		    'translations.*.iso'      => 'required|exists:languages,iso',
		    'date'                    => 'integer',
	    ]);

	    $bible = \DB::transaction(function () use($id) {
		    $bible = Bible::with('translations','organizations','equivalents','links')->find($id);
		    $bible->update(request()->only(['id','date','script','portions','copyright','derived','in_progress','notes','iso']));

			if(request()->translations) {
				foreach ($bible->translations as $translation) $translation->delete();
				foreach (request()->translations as $translation) if($translation['name']) $bible->translations()->create($translation);
			}

		    if(request()->organizations) $bible->organizations()->sync(request()->organizations);

		    if(request()->equivalents) {
			    foreach ($bible->equivalents as $equivalent) $equivalent->delete();
			    foreach (request()->equivalents as $equivalent) if($equivalent['equivalent_id']) $bible->equivalents()->create($equivalent);
		    }

		    if(request()->links) {
			    foreach ($bible->links as $link) $link->delete();
			    foreach (request()->links as $link) if($link['url']) $bible->links()->create($link);
		    }

		    return $bible;
	    });

	    return redirect()->route('view_bibles.show', ['id' => $bible->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: Generate Delete Model for Bible
    }
}
