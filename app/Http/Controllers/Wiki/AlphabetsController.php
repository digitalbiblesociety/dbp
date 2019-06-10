<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\APIController;

use App\Models\Language\Alphabet;
use App\Transformers\AlphabetTransformer;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use mysql_xdevapi\Exception;

class AlphabetsController extends APIController
{

    /**
     * Returns Alphabets
     *
     * @version 4
     * @category v4_alphabets.all
     * @link http://bible.build/alphabets - V4 Access
     * @link https://api.dbp.test/alphabets?key=1234&v=4&pretty - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_alphabets_all - V4 Test Docs
     *
     * @OA\Get(
     *     path="/alphabets",
     *     tags={"Languages"},
     *     summary="Returns Alphabets",
     *     description="Returns a list of the world's known scripts. This route might be useful to you if you'd like to
     *     you if you'd like to query information about fonts, alphabets, and the world's writing systems. Some fileset
     *     returns may not display correctly without a font delivered by these via the `alphabets/{id}` routes.",
     *     operationId="v4_alphabets.all",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/v4_alphabets_all_response")
     *         ),
     *         @OA\MediaType(
     *             mediaType="application/xml",
     *             @OA\Schema(ref="#/components/schemas/v4_alphabets_all_response")
     *         ),
     *         @OA\MediaType(
     *             mediaType="text/x-yaml",
     *             @OA\Schema(ref="#/components/schemas/v4_alphabets_all_response"
     *         )),
     *         @OA\MediaType(
     *             mediaType="text/csv",
     *             @OA\Schema(ref="#/components/schemas/v4_alphabets_all_response"
     *         ))
     *     )
     * )
     *
     * @return mixed $alphabets string - A JSON string that contains the status code and error messages if applicable.
     *
     */
    public function index()
    {
        $cache_string = 'alphabets';
        $alphabets = \Cache::remember($cache_string, now()->addDay(), function () {
            $alphabets = Alphabet::select(['name', 'script', 'family', 'direction', 'type'])->get();
            return fractal($alphabets, new AlphabetTransformer(), $this->serializer);
        });

        return $this->reply($alphabets);
    }


    /**
     * Returns Single Alphabet
     *
     * @version  4
     * @category v4_alphabets.one
     * @link     http://bible.build/alphabets - V4 Access
     * @link     https://api.dbp.test/alphabets/Latn?key=1234&v=4&pretty - V4 Test Access
     * @link     https://dbp.test/eng/docs/swagger/v4#/Wiki/v4_alphabets_one - V4 Test Docs
     *
     *
     * @OA\Get(
     *     path="/alphabets/{id}",
     *     tags={"Languages"},
     *     summary="Return a single Alphabets",
     *     description="Returns a single alphabet along with whatever bibles and languages using it.",
     *     operationId="v4_alphabets.one",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="The alphabet ID",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/Alphabet/properties/script")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/v4_alphabets_one_response")
     *         ),
     *         @OA\MediaType(
     *             mediaType="application/xml",
     *             @OA\Schema(ref="#/components/schemas/v4_alphabets_one_response")
     *         ),
     *         @OA\MediaType(
     *             mediaType="text/x-yaml",
     *             @OA\Schema(ref="#/components/schemas/v4_alphabets_one_response"
     *         )),
     *         @OA\MediaType(
     *             mediaType="text/csv",
     *             @OA\Schema(ref="#/components/schemas/v4_alphabets_one_response"
     *         ))
     *     )
     * )
     *
     * @param string $id
     * @return mixed $alphabets string - A JSON string that contains the status code and error messages if applicable.
     *
     */
    public function show($id)
    {
        $cache_string = 'alphabet:' . strtolower($id);
        $alphabet = \Cache::remember($cache_string, now()->addDay(), function () use ($id) {
            $alphabet = Alphabet::with('fonts', 'languages', 'bibles.currentTranslation')->find($id);
            return fractal($alphabet, AlphabetTransformer::class, $this->serializer);
        });
        if (!$alphabet) {
            return $this->setStatusCode(404)->replyWithError(trans('api.alphabets_errors_404'));
        }
        return $this->reply($alphabet);
    }
}
