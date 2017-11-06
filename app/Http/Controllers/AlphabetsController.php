<?php

namespace App\Http\Controllers;

use App\Models\Language\Alphabet;
use App\Transformers\AlphabetTransformer;
use Auth;

class AlphabetsController extends APIController
{

	/**
	 * Lists Alphabets for View or API
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
    {
	    $alphabets = Alphabet::all();
    	if(!$this->api) return view('languages.alphabets.index', compact('alphabets'));

		return $this->reply(fractal()->collection($alphabets)->transformWith(new AlphabetTransformer())->serializeWith($this->serializer)->toArray());
    }


	/**
	 * Single Alphabet Route for API or view
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function show($id)
    {
	    $alphabet = Alphabet::with('fonts','languages')->find($id);
	    if(!isset($alphabet)) return $this->setStatusCode(404)->replyWithError(trans('languages.alphabets_errors_404'));
    	if(!$this->api) return view('languages.alphabets.show', compact('alphabet'));
        return $this->reply(fractal()->item($alphabet)->transformWith(AlphabetTransformer::class)->serializeWith($this->serializer)->ToArray());
    }


	/**
	 *
	 * Create a brand new Alphabet
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create()
    {
        $user = Auth::user();
        if(!$user->role('archivist')) return $this->setStatusCode(403)->replyWithError("You are not an archivist");
        return view('languages.alphabets.create');
    }


	/**
	 * Store an Alphabet
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
    {
	    $alphabet = request()->validate([
		    'script'              => 'required|unique:alphabets,script|max:4|min:4',
		    'name'                => 'required|unique:alphabets,name|max:191',
		    'unicode_pdf'         => 'url|nullable',
		    'family'              => 'string|max:191|nullable',
		    'type'                => 'string|max:191|nullable',
			'white_space'         => 'string|max:191|nullable',
			'open_type_tag'       => 'string|max:191|nullable',
			'complex_positioning' => 'boolean',
			'requires_font'       => 'boolean',
			'unicode'             => 'boolean',
			'diacritics'          => 'boolean',
			'contextual_forms'    => 'boolean',
			'reordering'          => 'boolean',
			'case'                => 'boolean',
			'split_graphs'        => 'boolean',
			'status'              => 'string|max:191|nullable',
			'baseline'            => 'string|max:191|nullable',
			'ligatures'           => 'string|max:191|nullable',
			'direction'           => 'alpha|min:3|max:3',
			'sample'              => 'max:2024',
			'sample_img'          => 'image|nullable'
	    ]);
		Alphabet::create($alphabet);
		return redirect()->route('view_alphabets.show', ['id' => request()->id]);
    }


	/**
	 *
	 * Edit an Existing Alphabet
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit($id)
    {
	    $user = Auth::user();
        if(!$user->archivist) return $this->setStatusCode(403)->replyWithError("You are not an archivist");
        $alphabet = Alphabet::find($id);
        return view('languages.alphabets.edit',compact('alphabet'));
    }


}