<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\Bible\Book;
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
use Illuminate\Support\Arr;

class HighlightsController extends APIController
{
    use AnnotationTags;
    use CheckProjectMembership;

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/users/{user_id}/highlights",
     *     tags={"Annotations"},
     *     summary="List a user's highlights",
     *     description="The bible_id, book_id, and chapter parameters are optional but
     *          will allow you to specify which specific highlights you wish returned.",
     *     operationId="v4_highlights.index",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/User/properties/id"),
     *          description="The user who created the highlights"
     *     ),
     *     @OA\Parameter(
     *          name="bible_id",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Bible/properties/id"),
     *          description="The bible to filter highlights by"
     *     ),
     *     @OA\Parameter(
     *          name="book_id",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Book/properties/id"),
     *          description="The book to filter highlights by. For a complete list see the `book_id` field in the `/bibles/books` route."
     *     ),
     *     @OA\Parameter(
     *          name="chapter",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start"),
     *          description="The chapter to filter highlights by"
     *     ),
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          @OA\Schema(type="integer",default=25),
     *          description="The number of highlights to include in each return"
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          @OA\Schema(type="integer",default=1),
     *          description="The current page of the results"
     *     ),
     *     @OA\Parameter(
     *          name="prefer_color",
     *          in="query",
     *          @OA\Schema(type="string",default="hex",enum={"hex","rgba","rgb","full"}),
     *          description="Choose the format that highlighted colors will be returned in. If no color
     *          is not specified than the default is a six letter hexadecimal color."
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_highlights_index"))
     *     )
     * )
     *
     * @param $user_id
     *
     * @return mixed
     */
    public function index(Request $request, $user_id)
    {
        $user = $request->user();
        $user_id = $user ? $user->id : $request->user_id;
       
        // Validate Project / User Connection
        $user = User::where('id', $user_id)->select('id')->first();

        if (!$user) {
            return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404'));
        }

        $user_is_member = $this->compareProjects($user_id, $this->key);

        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $bible_id     = checkParam('bible_id');
        $book_id      = checkParam('book_id');
        $chapter_id   = checkParam('chapter|chapter_id');
        $limit        = (int) (checkParam('limit') ?? 25);

        $highlights = Highlight::with('color')->with('tags')->where('user_id', $user_id)
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
                'user_highlights.chapter',
                'user_highlights.verse_start',
                'user_highlights.highlight_start',
                'user_highlights.highlighted_words',
                'user_highlights.highlighted_color'
            ])->orderBy('user_highlights.updated_at')->paginate($limit);

        $highlight_collection = $highlights->getCollection();

        $highlight_pagination = new IlluminatePaginatorAdapter($highlights);

        return $this->reply(fractal($highlight_collection, UserHighlightsTransformer::class)->paginateWith($highlight_pagination));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/users/{user_id}/highlights",
     *     tags={"Annotations"},
     *     summary="Create a highlight",
     *     description="",
     *     operationId="v4_highlights.store",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="user_id",  in="path", required=true, @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\RequestBody(required=true, description="Fields for User Highlight Creation", @OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="bible_id",                  ref="#/components/schemas/Bible/properties/id"),
     *              @OA\Property(property="user_id",                   ref="#/components/schemas/User/properties/id"),
     *              @OA\Property(property="book_id",                   ref="#/components/schemas/Book/properties/id"),
     *              @OA\Property(property="chapter",                   ref="#/components/schemas/Highlight/properties/chapter"),
     *              @OA\Property(property="verse_start",               ref="#/components/schemas/Highlight/properties/verse_start"),
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
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_highlights_index"))
     *     )
     * )
     *
     * @return \Illuminate\Http\Response|array
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $request['user_id'] = $user ? $user->id : $request->user_id;
        
        // Validate Project / User Connection
        $user_is_member = $this->compareProjects($request->user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        // Validate Highlight
        $highlight_validation = $this->validateHighlight();
        if (\is_array($highlight_validation)) {
            return $highlight_validation;
        }

        $request->highlighted_color = $this->selectColor($request->highlighted_color);
        $highlight = Highlight::create([
            'user_id'           => $request->user_id,
            'bible_id'          => $request->bible_id,
            'book_id'           => $request->book_id,
            'chapter'           => $request->chapter,
            'verse_start'       => $request->verse_start,
            'highlight_start'   => $request->highlight_start,
            'highlighted_words' => $request->highlighted_words,
            'highlighted_color' => $request->highlighted_color,
        ]);

        $this->handleTags($highlight);
        return $this->reply(fractal($highlight, new HighlightTransformer())->addMeta(['success' => trans('api.users_highlights_create_200')]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/users/{user_id}/highlights/{highlight_id}",
     *     tags={"Annotations"},
     *     summary="Alter a highlight",
     *     description="",
     *     operationId="v4_highlights.update",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(name="highlight_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Highlight/properties/id")),
     *     @OA\RequestBody(required=true, description="Fields for User Highlight Update", @OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="bible_id",                  ref="#/components/schemas/Bible/properties/id"),
     *              @OA\Property(property="book_id",                   ref="#/components/schemas/Book/properties/id"),
     *              @OA\Property(property="chapter",                   ref="#/components/schemas/Highlight/properties/chapter"),
     *              @OA\Property(property="verse_start",               ref="#/components/schemas/Highlight/properties/verse_start"),
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
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_highlights_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_highlights_index"))
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
        $user = $request->user();
        $user_id = $user ? $user->id : $user_id;
        // Validate Project / User Connection
        $user_is_member = $this->compareProjects($user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        // Validate Highlight
        $highlight_validation = $this->validateHighlight();
        if (\is_array($highlight_validation)) {
            return $highlight_validation;
        }

        $highlight = Highlight::where('user_id', $user_id)->where('id', $id)->first();
        if (!$highlight) {
            return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_highlights'));
        }

        if ($request->highlighted_color) {
            $color = $this->selectColor($request->highlighted_color);
            $highlight->fill(Arr::add($request->except('highlighted_color','project_id'), 'highlighted_color', $color))->save();
        } else {
            $highlight->fill($request->except(['project_id']))->save();
        }

        $this->handleTags($highlight);

        return $this->reply(fractal($highlight, new HighlightTransformer())->addMeta(['success' => trans('api.users_highlights_update_200')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/users/{user_id}/highlights/{highlight_id}",
     *     tags={"Annotations"},
     *     summary="Delete a highlight",
     *     description="",
     *     operationId="v4_highlights.delete",
     *     security={{"api_token":{}}},
     *     @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OA\Parameter(name="highlight_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/Highlight/properties/id")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(type="string"))
     *     )
     * )
     *
     * @param  int $user_id
     * @param  int $id
     *
     * @return array|\Illuminate\Http\Response
     */
    public function destroy(Request $request, $user_id, $id)
    {
        $user = $request->user();
        $user_id = $user ? $user->id : $user_id;
        // Validate Project / User Connection
        $user_is_member = $this->compareProjects($user_id, $this->key);
        if (!$user_is_member) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_not_connected'));
        }

        $highlight  = Highlight::where('id', $id)->first();
        if (!$highlight) {
            return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_highlights'));
        }
        $highlight->delete();

        return $this->reply(['success' => trans('api.users_highlights_delete_200')]);
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
        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
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
        $highlightedColor = request()->highlighted_color;

        // Try Hex
        preg_match_all('/#[a-zA-Z0-9]{6}/i', $highlightedColor, $matches, PREG_SET_ORDER);
        if (isset($matches[0][0])) {
            $selectedColor = $this->hexToRgb($color);
        }

        // Try RGB
        if (!$selectedColor) {
            $expression = '/rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)|rgba\((\s*\d+\s*,){3}[\d\.]+\)/i';
            preg_match_all($expression, $highlightedColor, $matches, PREG_SET_ORDER);
            if (isset($matches[0][0])) {
                $selectedColor = $this->rgbParse($color);
            }
        }

        // Try HSL
        if (!$selectedColor) {
            $expression = '/hsl\(\s*\d+\s*(\s*\,\s*\d+\%){2}\)|hsla\(\s*\d+(\s*,\s*\d+\s*\%){2}\s*\,\s*[\d\.]+\)/i';
            preg_match_all($expression, $highlightedColor, $matches, PREG_SET_ORDER);
            if (isset($matches[0][0])) {
                $selectedColor = $this->hslToRgb($color, 1, 1);
            }
        }

        $highlightColor = HighlightColor::where($selectedColor)->first();
        if (!$highlightColor) {
            $selectedColor['color'] = 'generated_'.unique_random('dbp_users.user_highlight_colors', 'color', '8');
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
    private function rgbParse($rgb)
    {
        $removals = ['rgba','rgb','(',')'];
        $rgb = str_replace($removals, '', $rgb);
        $rgb = explode(',', $rgb);
        $rgb = ['red'=>$rgb[0],'green'=>$rgb[1],'blue'=>$rgb[2],'opacity'=>$rgb[3] ?? 1];
        return $rgb;
    }

    /**
     * @param     $hex
     * @param int $alpha
     *
     * @return mixed
     */
    private function hexToRgb($hex, $alpha = 1)
    {
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
    private function hslToRgb($hue, $saturation, $lightness)
    {
        $c = (1 - abs(2 * $lightness - 1)) * $saturation;
        $x = $c * (1 - abs(fmod($hue / 60, 2) - 1));
        $m = $lightness - ($c / 2);
        if ($hue < 60) {
            $red = $c;
            $green = $x;
            $blue = 0;
        } elseif ($hue < 120) {
            $red = $x;
            $green = $c;
            $blue = 0;
        } elseif ($hue < 180) {
            $red = 0;
            $green = $c;
            $blue = $x;
        } elseif ($hue < 240) {
            $red = 0;
            $green = $x;
            $blue = $c;
        } elseif ($hue < 300) {
            $red = $x;
            $green = 0;
            $blue = $c;
        } else {
            $red = $c;
            $green = 0;
            $blue = $x;
        }
        $red = ($red + $m) * 255;
        $green = ($green + $m) * 255;
        $blue = ($blue + $m) * 255;
        return ['red' => floor($red), 'green' => floor($green), 'blue' => floor($blue), 'alpha' => 1];
    }
}
