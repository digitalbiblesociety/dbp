<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\APIController;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Resource\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;
use \Illuminate\Http\Response;

use App\Transformers\ResourcesTransformer;

class ResourcesController extends APIController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (!$this->api) return view('resources.index');
		$iso             = checkParam('iso', null, 'optional');
		$language        = null;
		if($iso) {
			$language        = Language::where('iso',$iso)->with('dialects')->first();
			if(!$language)   return $this->setStatusCode(404)->replyWithError(trans('api.languages_errors_404'));
		}
		$limit           = checkParam('limit', null, 'optional') ?? 2000;
		$organization    = checkParam('organization_id', null, 'optional');
        $dialects        = checkParam('include_dialects', null, 'optional');

		if($organization !== null) {
			$organization = Organization::where('id',$organization)->orWhere('slug',$organization)->first();
			if(!$organization) return $this->setStatusCode(404)->replyWithError(trans('api.organizations_errors_404'));
		}

		$resources = Resource::with('translations', 'links', 'organization.translations','language')
			->when($language, function ($q) use ($language, $dialects) {
				$q->where('language_id', $language->id);
			    if($dialects) $q->orWhereIn('language_id',$language->dialects->pluck('dialect_id'));
			})
			->when($organization, function ($q) use ($organization) {
			    $q->where('organization_id', $organization->id);
			})->take($limit)->get();

		return $this->reply(fractal($resources,new ResourcesTransformer(),$this->serializer));

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return View
	 */
	public function create()
	{
		return view('resources.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return View
	 */
	public function store(Request $request)
	{
		$invalidResource = $this->invalidResource($request);
		if($invalidResource) return $invalidResource;
		return null;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return View
	 */
	public function show($id)
	{
		if (!$this->api) return view('resources.show');
		$resource = Resource::with('translations', 'links', 'organization.translations')->find($id);

		return $this->reply(fractal($resource,new ResourcesTransformer()));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$resource = Resource::findOrFail($id);
		return view('resources.edit',compact('resource'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return View
	 */
	public function update(Request $request, $id)
	{
		$invalidResource = $this->invalidResource($request);
		if($invalidResource) return $invalidResource;

		$resource = Resource::findOrFail($id);

		return view('resources.show',compact('resource'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return View
	 */
	public function destroy($id)
	{
		$resource = Resource::findOrFail($id);
		return view('resources.index');
	}

	private function invalidResource(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'unicode_pdf'         => 'url|nullable',
			'slug'                => 'required|unique:dbp.resources,slug|string|maxLength:191|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
			'language_id'         => 'required|exists:dbp.languages,id',
			'organization_id'     => 'required|exists:dbp.organizations,id',
			'source_id'           => 'string|maxLength:191',
			'cover'               => 'string|maxLength:191',
			'cover_thumbnail'     => 'string|maxLength:191',
			'date'                => 'date',
			'type'                => 'string',
			'translations.*.name' => 'required|unique:dbp.resource_translations,title|maxLength:191',
			'translations.*.tag'  => 'boolean',
			'links.*.url'         => 'required|url',
			'links.*.title'       => 'string|maxLength:191'
		]);

		if ($validator->fails()) {
			if (!$this->api) return redirect('dashboard/resources/create')->withErrors($validator)->withInput();
			return $this->setStatusCode(422)->replyWithError($validator->errors());
		}

		return null;
	}

	/**
	 *
	 * dam_id: DAM ID for the Jesus Film volume desired.
	 * encoding: [mp4|m3u8] The video encoding format desired.
	 * book_id (optional): OSIS book code to filter segments by references to book desired.
	 * chapter_id (optional): Chapter id to filter segments by references based on book and chapter.
	 * verse_id (optional): Verse id to filter segments by references based on book, chapter and verse.
	 *
	 */
	public function jesusFilmListing()
	{

		$id         = checkParam('dam_id');
		$encoding   = checkParam('encoding', null, 'optional');
		$book_id    = checkParam('book_id', null, 'optional');
		$chapter_id = checkParam('chapter_id', null, 'optional');
		$verse_id   = checkParam('verse_id', null, 'optional');

		$organization = Organization::where('slug', 'the-jesus-film-project')->first();
		$iso          = strtolower(substr($id, 0, 3));
		$language = false;
		if($iso !== null) {
			$language = Language::where('iso',$iso)->first();
			if(!$language) return $this->setStatusCode(404)->replyWithError("Language not found for provided iso");
		}

		$jesusFilm = Resource::with('translations')
			->when($language, function ($q) use ($language) {
				$q->where('language_id', $language->id);
			})->where('organization_id', $organization->id)->first();

		return $jesusFilm;
	}
}
