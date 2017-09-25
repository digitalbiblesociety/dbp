<?php

namespace App\Http\Controllers;

use App\Http\Controllers\APIController;
use App\Models\Language\Alphabet;
use App\Transformers\AlphabetTransformer;
use Auth;
use Illuminate\Http\Request;

class AlphabetsController extends APIController
{
    /**
     * Handles Alphabets index and api routes
     *
     * @return JSON|View
     */
    public function index()
    {
    	if(!$this->api) return view('languages.alphabets.index');

		$alphabets = Alphabet::select('script','name','family','type','direction')->get();
		return $this->reply(fractal()->collection($alphabets)->transformWith(new AlphabetTransformer())->serializeWith($this->serializer)->toArray());
    }

    /**
     * Single Alphabet Route for API or view
     *
     * @param string $id
     * @return JSON|View
     */
    public function show($id)
    {
	    $alphabet = Alphabet::with('fonts','languages')->find($id);
    	if(!$this->api) return view('languages.alphabets.show', compact('alphabet'));

	    if(!isset($alphabet)) return $this->setStatusCode(404)->replyWithError(trans('languages.alphabets_errors_404'));
        return $this->reply(fractal()->item($alphabet)->transformWith(AlphabetTransformer::class)->serializeWith($this->serializer)->ToArray());
    }


    /**
     * Create a brand new Alphabet
     *
     * @return JSON|View
     */
    public function create()
    {
        $user = Auth::user();
        if(!$user->role('archivist')) return $this->setStatusCode(403)->replyWithError("You are not an archivist");
        return view('languages.alphabets.create');
    }

	/**
	 * Store a brand new Alphabet
	 *
	 * @return JSON|View
	 */
    public function store(Request $request)
    {
	    $validator = Validator::make($request->all(), [
		    'script'                  => 'required|unique:alphabets,script|max:4|min:4',
		    'name'                    => 'required|unique:alphabets,name'
	    ]);

	    $alphabet = \DB::transaction(function () use($request) {
		    $alphabet = new Alphabet();
		    $alphabet = $alphabet->create($request->all());

		    return $alphabet;
	    });



    }

    /**
     * Edit an Existing Alphabet
     *
     * @param string $id
     * @return JSON|View
     */
    public function edit($id)
    {
        $user = Auth::user();
        if(!$user->hasRole('archivist')) return $this->setStatusCode(403)->replyWithError("You are not an archivist");
        $alphabet = Alphabet::find($id);
        return view('languages.alphabets.edit',compact('alphabet'));
    }


}