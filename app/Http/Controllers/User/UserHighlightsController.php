<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\User;
use App\Models\User\Study\HighlightColor;
use App\Traits\AnnotationTags;
use App\Transformers\UserHighlightsTransformer;
use App\Models\User\Study\Highlight;
use App\Traits\CheckProjectMembership;
use App\Transformers\V2\Annotations\HighlightTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Validator;

use Illuminate\Http\Request;

class UserHighlightsController extends APIController
{

	use AnnotationTags;
	use CheckProjectMembership;

	/**
	 * Display a listing of the resource.
	 *
	 * @OA\Get(
	 *     path="/users/{user_id}/highlights",
	 *     tags={"Users"},
	 *     summary="Get a list of highlights for a user/project combination",
	 *     description="The highlights index response: Note the bible_id is being used to identify the item instead of the bible_id. This is important as different bibles may have different numbers for the highlighted words field depending on their revision.",
	 *     operationId="v4_highlights.index",
	 *     @OA\Parameter(name="bible_id",      in="query", description="The bible to filter highlights by", @OA\Schema(ref="#/components/schemas/Bible/properties/id")),
	 *     @OA\Parameter(name="book_id",       in="query", description="The book to filter highlights by", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OA\Parameter(name="chapter",       in="query", description="The chapter to filter highlights by", @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OA\Parameter(name="limit",         in="query", description="The number of highlights to include in each return", @OA\Schema(type="integer",example=15,default=15)),
	 *     @OA\Parameter(name="prefer_color",  in="query", description="Choose the format that highlighted colors will be returned in", @OA\Schema(type="string",example="hex",enum={"hex","rgba","rgb","full"})),
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
	 *
	 * @return mixed
	 */
	public function index($user_id)
	{
		// Validate Project / User Connection
		$user = User::where('id',$user_id)->select('id')->first();
		if(!$user) return $this->replyWithError(trans('api.users_errors_404', ['param' => $user_id]));
		$user_is_member = $this->compareProjects($user_id, $this->key);
		if(!$user_is_member) return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));

		$bible_id     = checkParam('bible_id', null, 'optional');
		$book_id      = checkParam('book_id', null, 'optional');
		$chapter_id   = checkParam('chapter', null, 'optional');
		$limit        = (int) (checkParam('limit', null, 'optional') ?? 25);

		$highlights = Highlight::with('color')->with('tags')->where('user_id', $user_id)
			->join(env('DBP_DATABASE').'.bibles as bibles', 'bibles.id', '=', env('DBP_USERS_DATABASE').'.user_highlights.bible_id')
			->join(env('DBP_DATABASE').'.bible_books as book', function ($join) {
				$join->on('bibles.id', '=', 'book.bible_id')
				     ->on('book.book_id', '=', 'user_highlights.book_id');
			})
		    ->when($bible_id, function ($q) use ($bible_id) {
				$q->where('user_highlights.bible_id', $bible_id);
		    })->when($book_id, function ($q) use ($book_id) {
				$q->where('user_highlights.book_id', $book_id);
			})->when($chapter_id, function ($q) use ($chapter_id) {
				$q->where('chapter', $chapter_id);
			})->select([
				'user_highlights.id',
				'user_highlights.bible_id',
				'user_highlights.book_id',
				'book.name as book_name',
				'user_highlights.chapter',
				'user_highlights.verse_start',
				'user_highlights.highlight_start',
				'user_highlights.highlighted_words',
				'user_highlights.highlighted_color'
			])->orderBy('user_highlights.updated_at')->paginate($limit);

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
	 *     @OA\Parameter(name="bible_id",   in="query", description="", @OA\Schema(ref="#/components/schemas/Bible/properties/id")),
	 *     @OA\Parameter(name="book_id",    in="query", description="", @OA\Schema(ref="#/components/schemas/Book/properties/id")),
	 *     @OA\Parameter(name="chapter",    in="query", description="", @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
	 *     @OA\Parameter(name="paginate",   in="query", description="", @OA\Schema(type="integer",example=15,default=15)),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(required=true, description="Fields for User Highlight Creation", @OA\MediaType(mediaType="application/json",
	 *          @OA\Schema(
	 *              @OA\Property(property="bible_id",                  ref="#/components/schemas/Bible/properties/id"),
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
	 * @return \Illuminate\Http\Response|array
	 */
	public function store()
	{
		// Validate Project / User Connection
		$user_is_member = $this->compareProjects(request()->user_id, $this->key);
		if(!$user_is_member) return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));

		// Validate Highlight
		$highlight_validation = $this->validateHighlight();
		if(\is_array($highlight_validation)) return $highlight_validation;

		request()->highlighted_color = $this->selectColor(request()->highlighted_color);
		$highlight = Highlight::create([
			'user_id'           => request()->user_id,
			'bible_id'          => request()->bible_id,
			'book_id'           => request()->book_id,
			'chapter'           => request()->chapter,
			'verse_start'       => request()->verse_start,
			'highlight_start'   => request()->highlight_start,
			'highlighted_words' => request()->highlighted_words,
			'highlighted_color' => request()->highlighted_color,
		]);

		$this->handleTags(request(), $highlight);

		return $this->reply(fractal($highlight, new HighlightTransformer())->addMeta(['success' => trans('api.users_highlights_create_200')]));
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
	 * @param Request $request
	 * @param         $user_id
	 * @param  int    $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $user_id, $id)
	{
		// Validate Project / User Connection
		$user_is_member = $this->compareProjects($user_id, $this->key);
		if(!$user_is_member) return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));

		// Validate Highlight
		$highlight_validation = $this->validateHighlight();
		if(\is_array($highlight_validation)) return $highlight_validation;

		$highlight = Highlight::where('user_id', $user_id)->where('id', $id)->first();
		if(!$highlight) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_highlights'));

		if($request->highlighted_color) {
			$color = $this->selectColor($request->highlighted_color);
			$highlight->fill(array_add($request->except('highlighted_color'), 'highlighted_color',$color))->save();
		} else {
			$highlight->fill($request->all())->save();
		}

		$this->handleTags(request(), $highlight);

		return $this->reply(fractal($highlight, new HighlightTransformer())->addMeta([trans('api.success') => trans('api.users_highlights_update_200')]));
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
	 * @param  int $user_id
	 * @param  int $id
	 *
	 * @return array|\Illuminate\Http\Response
	 */
	public function destroy($user_id, $id)
	{
		// Validate Project / User Connection
		$user_is_member = $this->compareProjects($user_id, $this->key);
		if(!$user_is_member) return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));

		$highlight  = Highlight::where('id', $id)->first();
		if(!$highlight) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_highlights'));
		$highlight->delete();

		return $this->reply([trans('api.success') => trans('api.users_highlights_create_200')]);
	}

	private function validateHighlight()
	{
		$validator = Validator::make(request()->all(), [
			'bible_id'          => ((request()->method() === 'POST') ? 'required|' : '') . 'exists:dbp.bibles,id',
			'user_id'           => ((request()->method() === 'POST') ? 'required|' : '') . 'exists:dbp_users.users,id',
			'book_id'           => ((request()->method() === 'POST') ? 'required|' : '') . 'exists:dbp.books,id',
			'chapter'           => ((request()->method() === 'POST') ? 'required|' : '') . 'max:150|min:1|integer',
			'verse_start'       => ((request()->method() === 'POST') ? 'required|' : '') . 'max:177|min:1|integer',
			'reference'         => 'string',
			'highlight_start'   => ((request()->method() === 'POST') ? 'required|' : '') . 'min:0|integer',
			'highlighted_words' => ((request()->method() === 'POST') ? 'required|' : '') . 'min:1|integer',
			'highlighted_color' => (request()->method() === 'POST') ? 'required' : '',
		]);
		if($validator->fails()) return ['errors' => $validator->errors()];
		return true;
	}

	/**
	 * @param $color
	 *
	 * @return mixed
	 */
	private function selectColor($color)
	{
		$matches = [];
		$selectedColor = null;

		// Try Hex
		preg_match_all('/#[a-zA-Z0-9]{6}/i', request()->highlighted_color, $matches, PREG_SET_ORDER);
		if(isset($matches[0][0])) $selectedColor = $this->hexToRgb($color);

		// Try RGB
		if(!$selectedColor) {
			preg_match_all('/rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)|rgba\((\s*\d+\s*,){3}[\d\.]+\)/i', request()->highlighted_color, $matches, PREG_SET_ORDER);
			if(isset($matches[0][0])) $selectedColor = $this->rgbParse($color);
		}

		// Try HSL
		if(!$selectedColor) {
			preg_match_all('/hsl\(\s*\d+\s*(\s*\,\s*\d+\%){2}\)|hsla\(\s*\d+(\s*,\s*\d+\s*\%){2}\s*\,\s*[\d\.]+\)/i', request()->highlighted_color, $matches, PREG_SET_ORDER);
			if(isset($matches[0][0])) $selectedColor = $this->hslToRgb($color, 1, 1);
		}

		$highlightColor = HighlightColor::where($selectedColor)->first();
		if(!$highlightColor) {
			$selectedColor['color'] = 'generated_'.unique_random('user_highlight_colors','color','8');
			$selectedColor['hex'] = dechex($selectedColor['red']).dechex($selectedColor['green']).dechex($selectedColor['blue']);
			$highlightColor = HighlightColor::create($selectedColor);
		}
		return $highlightColor->id;
	}

	/**
	 * @param $rgb
	 *
	 * @return array|mixed
	 */
	private function rgbParse($rgb) {
		$removals = ['rgba','rgb','(',')'];
		$rgb = str_replace($removals,'',$rgb);
		$rgb = explode(',',$rgb);
		$rgb = ['red'=>$rgb[0],'green'=>$rgb[1],'blue'=>$rgb[2],'opacity'=>$rgb[3] ?? 1];
		return $rgb;
	}

	/**
	 * @param     $hex
	 * @param int $alpha
	 *
	 * @return mixed
	 */
	private function hexToRgb($hex, $alpha = 1) {
		$hex            = str_replace('#', '', $hex);
		$length         = \strlen($hex);
		$rgba['red']     = hexdec($length === 6 ? substr($hex, 0, 2) : ($length === 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
		$rgba['green']   = hexdec($length === 6 ? substr($hex, 2, 2) : ($length === 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
		$rgba['blue']    = hexdec($length === 6 ? substr($hex, 4, 2) : ($length === 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
		$rgba['opacity'] = $alpha;
		return $rgba;
	}

	/**
	 * @param $hue
	 * @param $saturation
	 * @param $lightness
	 *
	 * @return array
	 */
	private function hslToRgb( $hue, $saturation, $lightness ){
		$c = ( 1 - abs( 2 * $lightness - 1 ) ) * $saturation;
		$x = $c * ( 1 - abs(fmod( $hue / 60, 2 ) - 1 ) );
		$m = $lightness - ( $c / 2 );
		if ( $hue < 60 ) {
			$red = $c;
			$green = $x;
			$blue = 0;
		} else if ( $hue < 120 ) {
			$red = $x;
			$green = $c;
			$blue = 0;
		} else if ( $hue < 180 ) {
			$red = 0;
			$green = $c;
			$blue = $x;
		} else if ( $hue < 240 ) {
			$red = 0;
			$green = $x;
			$blue = $c;
		} else if ( $hue < 300 ) {
			$red = $x;
			$green = 0;
			$blue = $c;
		} else {
			$red = $c;
			$green = 0;
			$blue = $x;
		}
		$red = ( $red + $m ) * 255;
		$green = ( $green + $m ) * 255;
		$blue = ( $blue + $m  ) * 255;
		return ['red' => floor( $red ), 'green' => floor( $green ), 'blue' => floor( $blue ), 'alpha' => 1];
	}


}
