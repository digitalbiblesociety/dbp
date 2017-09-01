<?php

namespace App\Http\Controllers;

use App\Http\Controllers\APIController;
use App\Models\Language\Alphabet;
use App\Transformers\AlphabetTransformer;
use Auth;

class AlphabetsController extends APIController
{
    /**
     * Handles Alphabets index and api routes
     *
     * @return JSON|View
     */
    public function index()
    {
        if($this->api) {
            $alphabets = Alphabet::select('script','name','family','type','direction')->get();
            return $this->reply(fractal()->collection($alphabets)->transformWith(new AlphabetTransformer())->serializeWith($this->serializer)->toArray());
        }
        return view('languages.alphabets.index');
    }

    /**
     * Single Alphabet Route for API or view
     *
     * @param string $id
     * @return JSON|View
     */
    public function show($id)
    {
        if($this->api) {
	        $alphabet = Alphabet::with('fonts','languages')->find($id);
	        if(!isset($alphabet)) return $this->setStatusCode(404)->replyWithError(trans('languages.alphabets_errors_404'));
        	return $this->reply(fractal()->item($alphabet)->transformWith(AlphabetTransformer::class)->serializeWith($this->serializer)->ToArray());
        }
        return view('languages.alphabets.show', compact('alphabet'));
    }


    /**
     * Create a brand new Alphabet
     *
     * @return JSON|View
     */
    public function create()
    {
        $user = Auth::user();
        if(!$user->hasRole('archivist')) return $this->setStatusCode(403)->replyWithError("You are not an archivist");
        return view('languages.alphabets.create');
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