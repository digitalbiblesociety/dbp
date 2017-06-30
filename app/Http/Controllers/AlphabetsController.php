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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        if($this->api) {
            $alphabets = Alphabet::select('script','name','family','type','direction')->get();
            return $this->reply(fractal()->collection($alphabets)->transformWith(new AlphabetTransformer())->toArray());
        }
        return view('wiki.alphabets.index');
    }

    /**
     * Single Alphabet Route for API or view
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $alphabet = Alphabet::with('fonts','languages')->find($id);
        if(!isset($alphabet)) return $this->setStatusCode(404)->replyWithError(trans('wiki.alphabets_errors_404'));
        if($this->api) return $this->reply($alphabet);
        return view('wiki.alphabets.show', compact('alphabet'));
    }


    /**
     * Create a brand new Alphabet
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        if(!$user->hasRole('archivist')) return $this->setStatusCode(403)->replyWithError("You are not an archivist");
        return view('wiki.alphabets.create');
    }

    /**
     * Edit an Existing Alphabet
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = Auth::user();
        if(!$user->hasRole('archivist')) return $this->setStatusCode(403)->replyWithError("You are not an archivist");
        $alphabet = Alphabet::find($id);
        return view('wiki.alphabets.edit',compact('alphabet'));
    }


}