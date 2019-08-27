<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\APIController;

use App\Models\Language\NumeralSystem;
use App\Models\Language\NumeralSystemGlyph;
use App\Transformers\NumbersTransformer;

class NumbersController extends APIController
{

    /**
     *
     * @OA\Get(
     *     path="/numbers/range",
     *     tags={"Languages"},
     *     summary="Return a range of numbers",
     *     description="This route returns the vernacular numbers for a set range.",
     *     operationId="v4_numbers.range",
     *     @OA\Parameter(
     *          name="script_id",
     *          in="query",
     *          required=true,
     *          description="The script_id to return numbers for",
     *          @OA\Schema(ref="#/components/schemas/NumeralSystem/properties/id")
     *     ),
     *     @OA\Parameter(
     *          name="start",
     *          in="query",
     *          required=true,
     *          description="The start of the range to select for",
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          name="end",
     *          in="query",
     *          required=true,
     *          description="The end of the range to select for",
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_numbers_range")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_numbers_range")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_numbers_range")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_numbers_range"))
     *     )
     * )
     *
     * @return mixed
     *
     * @OA\Schema (
     *     type="array",
     *     schema="v4_numbers_range",
     *     description="The numbers range return",
     *     title="The numbers range return",
     *     @OA\Xml(name="v4_numbers_range"),
     *     @OA\Items(
     *        @OA\Property(property="numeral", type="string"),
     *        @OA\Property(property="numeral_vernacular", type="string")
     *     )
     * )
     *
     */
    public function customRange()
    {
        $script = checkParam('script|script_id', true);
        $start  = checkParam('start') ?? 0;
        $end    = checkParam('end');
        if (($end - $start) > 200) {
            return $this->replyWithError(trans('api.numerals_range_error', ['num' => $end]));
        }

        // Fetch Numbers By Iso Or Script Code
        $numbers = NumeralSystemGlyph::where('numeral_system_id', $script)
                    ->where('value', '>=', $start)
                    ->where('value', '<=', $end)->select('value as numeral', 'glyph as numeral_vernacular')->get();

        return $this->reply($numbers);
    }

    /**
     *
     * @OA\Get(
     *     path="/numbers",
     *     tags={"Languages"},
     *     summary="Return all Alphabets that have a custom number sets",
     *     description="Returns a range of numbers",
     *     operationId="v4_numbers.index",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/v4_alphabets_all_response")),
     *         @OA\MediaType(mediaType="application/xml",
     *         @OA\Schema(ref="#/components/schemas/v4_alphabets_all_response")),
     *         @OA\MediaType(mediaType="text/x-yaml",
     *         @OA\Schema(ref="#/components/schemas/v4_alphabets_all_response")),
     *         @OA\MediaType(mediaType="text/csv",
     *         @OA\Schema(ref="#/components/schemas/v4_alphabets_all_response"))
     *     )
     * )
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        if (!$this->api) {
            return view('wiki.languages.alphabets.numerals.index');
        }
        $cache_string = 'v4_numbers_index';
        $numeral_systems = \Cache::remember($cache_string, now()->addDay(), function () {
            $numeral_systems = NumeralSystem::with('alphabets')->get();
            return fractal($numeral_systems, new NumbersTransformer())->serializeWith($this->serializer);
        });

        return $this->reply($numeral_systems);
    }

    /**
     *
     * @OA\Get(
     *     path="/numbers/{id}",
     *     tags={"Languages"},
     *     summary="Return a single custom number set",
     *     description="Returns a range of numbers",
     *     operationId="v4_numbers.show",
     *     @OA\Parameter(name="id", in="path", required=true, description="The NumeralSystem id",
     *          @OA\Schema(ref="#/components/schemas/NumeralSystem/properties/id")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/v4_alphabets_one_response")),
     *         @OA\MediaType(mediaType="application/xml",
     *              @OA\Schema(ref="#/components/schemas/v4_alphabets_one_response")),
     *         @OA\MediaType(mediaType="text/x-yaml",
     *              @OA\Schema(ref="#/components/schemas/v4_alphabets_one_response")),
     *         @OA\MediaType(mediaType="text/csv",
     *              @OA\Schema(ref="#/components/schemas/v4_alphabets_one_response"))
     *     )
     * )
     *
     * @param $system
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($system)
    {
        if (!$this->api) {
            return view('wiki.languages.alphabets.numerals.show');
        }

        $numerals = NumeralSystem::where('id', $system)->first();
        if (!$numerals) {
            $error_message = trans('api.alphabet_numerals_errors_404', ['script' => $system], $GLOBALS['i18n_iso']);
            return $this->setStatusCode(404)->replyWithError($error_message);
        }

        $cache_string = strtolower('v4_numbers_show:'.$system);

        $numerals = \Cache::remember($cache_string, now()->addDay(), function () use ($numerals) {
            $numerals->load('alphabets', 'numerals');
            return fractal($numerals, new NumbersTransformer())->serializeWith($this->serializer);
        });

        return $this->reply($numerals);
    }
}
