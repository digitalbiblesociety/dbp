<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFileset;
use App\Models\User\Study\Highlight;
use App\Traits\CheckProjectMembership;
use Illuminate\Http\Request;
use Validator;
use App\Transformers\UserHighlightsTransformer;

use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class UserHighlightsController extends APIController
{

	use CheckProjectMembership;

	/**
	 * Display a listing of the resource.
	 *
	 * @OA\Get(
	 *     path="/users/{user_id}/highlights",
	 *     tags={"Users"},
	 *     summary="Get a list of highlights for a user/project combination",
	 *     description="",
	 *     operationId="v4_highlights.index",
	 *     @OA\Parameter(name="fileset_id",    in="query", description="The fileset to filter highlights by", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Parameter(name="book_id",       in="query", description="The book to filter highlights by", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OA\Parameter(name="chapter",       in="query", description="The chapter to filter highlights by", @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OA\Parameter(name="limit",         in="query", description="The number of highlights to include in each return", @OA\Schema(type="integer",example=15,default=15)),
	 *     @OA\Parameter(name="prefer_color",  in="query", description="Choose the format that highlighted colors will be returned in", @OA\Schema(type="string",example="hex",enum={"hex","rgba","rgb","full"}),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_highlights_index"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($user_id)
	{
		$user_is_member = $this->compareProjects($user_id);
		if(!$user_is_member) return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));

		$fileset_id   = checkParam('fileset_id', null, 'optional');
		$book_id      = checkParam('book_id', null, 'optional');
		$chapter_id   = checkParam('chapter', null, 'optional');
		$limit        = intval(checkParam('limit', null, 'optional') ?? 25);

		$highlights = Highlight::with('color')->where('user_id', $user_id)
			->join(env('DBP_DATABASE').'.bible_filesets as fileset', 'fileset.id', '=', env('DBP_USERS_DATABASE').'.user_highlights.fileset_id')
			->join(env('DBP_DATABASE').'.bible_fileset_connections as connection', 'connection.hash_id', 'fileset.hash_id')
			->join(env('DBP_DATABASE').'.bible_books as book', function ($join) {
				$join->on('connection.bible_id', '=', 'book.bible_id')
				     ->on('book.book_id', '=', 'user_highlights.book_id');
			})
		    ->when($fileset_id, function ($q) use ($fileset_id) {
				$q->where('fileset_id', $fileset_id);
		    })->when($book_id, function ($q) use ($book_id) {
				$q->where('user_highlights.book_id', $book_id);
			})->when($chapter_id, function ($q) use ($chapter_id) {
				$q->where('chapter', $chapter_id);
			})->select([
				'user_highlights.id',
				'user_highlights.fileset_id',
				'user_highlights.book_id',
				'book.name as book_name',
				'user_highlights.chapter',
				'user_highlights.verse_start',
				'user_highlights.highlight_start',
				'user_highlights.highlighted_words',
				'user_highlights.highlighted_color'
			])->orderBy('user_highlights.updated_at')->paginate($limit);

		//->paginate($limit)

		return $this->reply(fractal($highlights->getCollection(), UserHighlightsTransformer::class)->paginateWith(new IlluminatePaginatorAdapter($highlights)));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('dashboard.highlights.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @OA\Post(
	 *     path="/users/{user_id}/highlights",
	 *     tags={"Users"},
	 *     summary="Create a user highlight",
	 *     description="",
	 *     operationId="v4_highlights.store",
	 *     @OA\Parameter(name="fileset_id",   in="query", description="", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OA\Parameter(name="book_id",    in="query", description="", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OA\Parameter(name="chapter",    in="query", description="", @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OA\Parameter(name="paginate",   in="query", description="", @OA\Schema(type="integer",example=15,default=15)),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(required=true, description="Fields for User Highlight Creation", @OA\MediaType(mediaType="application/json",
	 *          @OA\Schema(
	 *              @OA\Property(property="fileset_id",                  ref="#/components/schemas/Bible/properties/id"),
	 *              @OA\Property(property="user_id",                   ref="#/components/schemas/User/properties/id"),
	 *              @OA\Property(property="book_id",                   ref="#/components/schemas/Book/properties/id"),
	 *              @OA\Property(property="chapter",                   ref="#/components/schemas/Highlight/properties/chapter"),
	 *              @OA\Property(property="verse_start",               ref="#/components/schemas/Highlight/properties/verse_start"),
	 *              @OA\Property(property="reference",                 ref="#/components/schemas/Highlight/properties/reference"),
	 *              @OA\Property(property="highlight_start",           ref="#/components/schemas/Highlight/properties/highlight_start"),
	 *              @OA\Property(property="highlighted_words",         ref="#/components/schemas/Highlight/properties/highlighted_words"),
	 *              @OA\Property(property="highlighted_color",         ref="#/components/schemas/Highlight/properties/highlighted_color"),
	 *          )
	 *     )),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_highlights_index"))
	 *     )
	 * )
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$user_is_member = $this->compareProjects($request->user_id);
		if(!$user_is_member) return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));

		$validator = Validator::make($request->all(), [
			'fileset_id'        => 'required|exists:dbp.bible_filesets,id',
			'user_id'           => 'required|exists:dbp_users.users,id',
			'book_id'           => 'required|exists:dbp.books,id',
			'chapter'           => 'required|max:150|min:1|integer',
			'verse_start'       => 'required|max:177|min:1|integer',
			'reference'         => 'string',
			'highlight_start'   => 'required|min:0|integer',
			'highlighted_words' => 'required|min:1|integer',
			'highlighted_color' => 'max:16|min:3',
		]);
		if ($validator->fails()) {
			return ['errors' => $validator->errors()];
		}

		Highlight::create([
			'user_id'           => $request->user_id,
			'fileset_id'        => $request->fileset_id,
			'book_id'           => $request->book_id,
			'chapter'           => $request->chapter,
			'verse_start'       => $request->verse_start,
			'reference'         => $request->reference,
			'highlight_start'   => $request->highlight_start,
			'highlighted_words' => $request->highlighted_words,
			'highlighted_color' => $request->highlighted_color ?? '80,165,220,.5',
		]);

		return $this->reply(["success" => "Highlight created"]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @OA\Get(
	 *     path="/users/{user_id}/highlights/{highlight_id}",
	 *     tags={"Users"},
	 *     summary="Show a user highlight",
	 *     description="",
	 *     operationId="v4_highlights.show",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_highlights_index"))
	 *     )
	 * )
	 *
	 * @param $user_id
	 * @param $highlight_id
	 *
	 * @return \Illuminate\Http\Response
	 * @internal param int $id
	 *
	 */
	public function show($user_id,$highlight_id)
	{
		$user_is_member = $this->compareProjects($user_id);
		if(!$user_is_member) return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));

		$highlight  = Highlight::where('id', $highlight_id)->first();
		if (!$highlight) return $this->setStatusCode(404)->replyWithError("No Note found for the specified ID");

		return $this->reply($highlight);
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @OA\Put(
	 *     path="/users/{user_id}/highlights/{highlight_id}",
	 *     tags={"Users"},
	 *     summary="Show a user highlight",
	 *     description="",
	 *     operationId="v4_highlights.update",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_highlights_index"))
	 *     )
	 * )
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param                           $user_id
	 * @param  int                      $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $user_id, $id)
	{
		$user_is_member = $this->compareProjects($user_id);
		if(!$user_is_member) return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));

		$highlight = Highlight::where('user_id', $user_id)->where('id', $id)->first();
		if (!$highlight) return $this->setStatusCode(404)->replyWithError('Sorry The Highlight was not found');

		$highlight->fill($request->all())->save();

		return $this->reply(["success" => "Highlight Updated"]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @OA\Delete(
	 *     path="/users/{user_id}/highlights/{highlight_id}",
	 *     tags={"Users"},
	 *     summary="Show a user highlight",
	 *     description="",
	 *     operationId="v4_highlights.delete",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_highlights_index"))
	 *     )
	 * )
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($user_id, $id)
	{
		$user_is_member = $this->compareProjects($user_id);
		if(!$user_is_member) return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));

		$highlight  = Highlight::where('id', $id)->first();
		if (!$highlight) return $this->setStatusCode(404)->replyWithError("Highlight not found");
		$highlight->delete();

		return $this->reply(["success" => "Highlight Deleted"]);
	}
}
