<?php

namespace App\Http\Controllers;

use App\Models\User\User;
use Auth;
use Validator;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Language\Alphabet;
use App\Transformers\AlphabetTransformer;
use App\Models\User\Key;

class AlphabetsController extends APIController
{

	/**
	 * Returns Alphabets
	 *
	 * @version 4
	 * @category v4_alphabets.all
	 * @link http://bible.build/alphabets - V4 Access
	 * @link https://api.dbp.dev/alphabets?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_alphabets_all - V4 Test Docs
	 *
	 * @return mixed $alphabets string - A JSON string that contains the status code and error messages if applicable.
	 *
	 * @OAS\Get(
	 *     path="/alphabets",
	 *     tags={"Languages"},
	 *     summary="Returns Alphabets",
	 *     description="Returns a list of the world's known scripts. This route will be useful to you if you'd like to query information about fonts, alphabets, and the world's writing systems. Some `BibleFileset` models may not display correctly without a font delivered by these via the `alphabets/{id}` route.",
	 *     operationId="v4_alphabets.all",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_alphabets_all_response")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_alphabets_all_response")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_alphabets_all_response"))
	 *     )
	 * )
	 *
	 */
	public function index()
	{
		if (!$this->api) return view('wiki.languages.alphabets.index');

		$alphabets = \Cache::remember('alphabets', 1600, function () {
			$alphabets = Alphabet::select(['name', 'script', 'family', 'direction', 'type'])->get();
			return fractal($alphabets, new AlphabetTransformer())->serializeWith($this->serializer);
		});

		return $this->reply($alphabets);
	}


	/**
	 * Returns Single Alphabet
	 *
	 * @version 4
	 * @category v4_alphabets.one
	 * @link http://bible.build/alphabets - V4 Access
	 * @link https://api.dbp.dev/alphabets/Latn?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_alphabets_one - V4 Test Docs
	 *
	 * @return mixed $alphabets string - A JSON string that contains the status code and error messages if applicable.
	 *
	 * @OAS\Get(
	 *     path="/alphabets/{id}",
	 *     tags={"Languages"},
	 *     summary="Return a single Alphabets",
	 *     description="Returns a single alphabet along with whatever bibles are written with it and languages using it ",
	 *     operationId="v4_alphabets.one",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Parameter(name="id", in="path", description="The alphabet ID", required=true, @OAS\Schema(ref="#/components/schemas/Alphabet/properties/script")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_alphabets_one_response")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_alphabets_one_response")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_alphabets_one_response"))
	 *     )
	 * )
	 *
	 */
	public function show($id)
	{
		$alphabet = \Cache::remember('alphabet_' . $id, 1600, function () use ($id) {
			return Alphabet::with('fonts', 'languages', 'bibles.currentTranslation')->where('script', $id)->first();
		});
		if (!isset($alphabet)) return $this->setStatusCode(404)->replyWithError(trans('languages.alphabets_errors_404'));
		if (!$this->api) return view('wiki.languages.alphabets.show', compact('alphabet'));

		return $this->reply(fractal()->item($alphabet)->transformWith(AlphabetTransformer::class)->serializeWith($this->serializer));
	}


	/**
	 * Create an Alphabet
	 *
	 * @version 4
	 * @category ui_alphabets.create
	 * @link http://dbp.dev/alphabets/create - V4 Access
	 *
	 * @return View
	 *
	 */
	public function create()
	{
		$validatedUser = $this->validateUser();
		if (!is_a($validatedUser, User::class)) {
		}

		return $validatedUser;

		return view('wiki.languages.alphabets.create');
	}

	/**
	 * Stores a Single Alphabet
	 *
	 * @version 4
	 * @category v4_alphabets.store
	 * @link http://bible.build/alphabets - V4 Access
	 * @link https://api.dbp.dev/alphabets?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_alphabets_store - V4 Test Docs
	 *
	 * @return mixed View|$alphabets
	 *
	 * @OAS\Post(
	 *     path="/alphabets/",
	 *     tags={"Languages"},
	 *     summary="Store a single Alphabet",
	 *     description="Store a single alphabet",
	 *     operationId="v4_alphabets.store",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\RequestBody(required=true, description="Fields for Alphabet Creation", @OAS\MediaType(mediaType="application/json",
	 *          @OAS\Schema(ref="#/components/schemas/Alphabet")
	 *     )),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_alphabets_one_response")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_alphabets_one_response")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_alphabets_one_response"))
	 *     )
	 * )
	 *
	 */

	public function store(Request $request)
	{
		($this->api) ? $this->validateUser() : $this->validateUser(Auth::user());
		$this->validateAlphabet($request);

		Alphabet::create($request->all());
		if (!$this->api) return redirect()->route('view_alphabets.show', ['id' => request()->id]);

		return $this->reply(["message" => "Alphabet Successfully Created"]);
	}

	/**
	 * Update a Single Alphabet
	 *
	 * @version 4
	 * @category v4_alphabets.update
	 * @link http://bible.build/alphabets - V4 Access
	 * @link https://api.dbp.dev/alphabets/Latn?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Wiki/v4_alphabets_store - V4 Test Docs
	 *
	 * @param string $script_id - The ID of the alphabet currently being edited
	 * @param Request $request - The form body
	 *
	 * @return mixed View|$alphabet
	 *
	 * @OAS\Put(
	 *     path="/alphabets/{id}",
	 *     tags={"Languages"},
	 *     summary="Store a single Alphabet",
	 *     description="Store a single alphabet",
	 *     operationId="v4_alphabets.update",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Parameter(name="id", in="path", description="The alphabet ID", required=true, @OAS\Schema(ref="#/components/schemas/Alphabet/properties/script")),
	 *     @OAS\RequestBody(required=true, description="Fields for Alphabet Update", @OAS\MediaType(mediaType="application/json",
	 *          @OAS\Schema(ref="#/components/schemas/Alphabet")
	 *     )),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_alphabets_one_response")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_alphabets_one_response")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_alphabets_one_response"))
	 *     )
	 * )
	 *
	 */
	public function update(string $script_id, Request $request)
	{
		($this->api) ? $this->validateUser() : $this->validateUser(Auth::user());
		$this->validateAlphabet($request);

		Alphabet::find($script_id)->fill($request->all())->save();
		if(!$this->api) return redirect()->route('view_alphabets.show', ['id' => $request->id]);

		return $this->reply("Alphabet Successfully Updated");
	}

	/**
	 * Edit a Single Alphabet
	 *
	 * @version 4
	 * @category ui_alphabets.edit
	 * @link http://bible.build/alphabets/Latn/edit - V4 Access
	 *
	 * @param $script_id - The ID of the alphabet currently being edited
	 *
	 * @return View
	 */
	public function edit(string $script_id)
	{
		$this->validateUser(Auth::user());

		$alphabet = Alphabet::find($script_id);

		return view('wiki.languages.alphabets.edit', compact('alphabet'));
	}

	/**
	 * Ensure the current User has permissions to alter the alphabets
	 *
	 * @return \App\Models\User\User|mixed|null
	 */
	private function validateUser()
	{
		$user = Auth::user();
		if (!$user) {
			$key = Key::where('key', $this->key)->first();
			if (!isset($key)) return $this->setStatusCode(403)->replyWithError('No Authentication Provided or invalid Key');
			$user = $key->user;
		}
		if (!$user->archivist AND !$user->admin) return $this->setStatusCode(401)->replyWithError("You don't have permission to edit the wiki");

		return $user;
	}

	/**
	 * Ensure the current alphabet change is valid
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	private function validateAlphabet(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'script'              => ($request->method() == "POST") ? 'required|unique:alphabets,script|max:4|min:4' : 'required|exists:alphabets,script|max:4|min:4',
			'name'                => ($request->method() == "POST") ? 'required|unique:alphabets,name|max:191' : 'required|exists:alphabets,name|max:191',
			'unicode_pdf'         => 'url|nullable',
			'family'              => 'string|max:191|nullable',
			'type'                => 'string|max:191|nullable',
			'white_space'         => 'string|max:191|nullable',
			'open_type_tag'       => 'string|max:191|nullable',
			'status'              => 'string|max:191|nullable',
			'baseline'            => 'string|max:191|nullable',
			'ligatures'           => 'string|max:191|nullable',
			'complex_positioning' => 'boolean',
			'requires_font'       => 'boolean',
			'unicode'             => 'boolean',
			'diacritics'          => 'boolean',
			'contextual_forms'    => 'boolean',
			'reordering'          => 'boolean',
			'case'                => 'boolean',
			'split_graphs'        => 'boolean',
			'direction'           => 'alpha|min:3|max:3',
			'sample'              => 'max:2024',
			'sample_img'          => 'image|nullable',
		]);

		if ($validator->fails()) {
			if (!$this->api) return redirect('dashboard/alphabets/create')->withErrors($validator)->withInput();
			return $this->setStatusCode(422)->replyWithError($validator->errors());
		}

	}


}