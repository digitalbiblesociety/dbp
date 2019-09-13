<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\APIController;

use App\Models\Language\Language;
use App\Transformers\LanguageTransformer;
use App\Traits\AccessControlAPI;

class LanguagesController extends APIController
{
    use AccessControlAPI;

    /**
     * Display a listing of the resource.
     *
     * Fetches the records from the database > passes them through fractal for transforming.
     *
     * @OA\Get(
     *     path="/languages",
     *     tags={"Languages"},
     *     summary="Returns Languages",
     *     description="Returns the List of Languages",
     *     operationId="v4_languages.all",
     *     @OA\Parameter(
     *          name="country",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Country/properties/id"),
     *          description="The country"
     *     ),
     *     @OA\Parameter(
     *          name="iso",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Language/properties/iso"),
     *          description="The iso code to filter languages by. For a complete list see the `iso` field in the `/languages` route"
     *     ),
     *     @OA\Parameter(
     *          name="language_name",
     *          in="query",
     *          @OA\Schema(type="string"),
     *          description="The language_name field will filter results by a specific language name"
     *     ),
     *     @OA\Parameter(
     *          name="asset_id",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Asset/properties/id"),
     *          description="The bucket_id"
     *     ),
     *     @OA\Parameter(
     *          name="include_alt_names",
     *          in="query",
     *          @OA\Schema(ref="#/components/schemas/Language/properties/name"),
     *          description="The include_alt_names"
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/l10n"),
     *     @OA\Parameter(ref="#/components/parameters/sort_by"),
     *     @OA\Parameter(ref="#/components/parameters/sort_dir"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/Language")),
     *         @OA\MediaType(mediaType="application/xml", @OA\Schema(ref="#/components/schemas/Language")),
     *         @OA\MediaType(mediaType="text/x-yaml", @OA\Schema(ref="#/components/schemas/Language")),
     *         @OA\MediaType(mediaType="text/csv", @OA\Schema(ref="#/components/schemas/Language"))
     *     )
     * )
     * @link https://api.dbp.test/languages?key=1234&v=4&pretty
     * @return \Illuminate\Http\Response
     *
     *
     */
    public function index()
    {
        if (!$this->api) {
            return view('wiki.languages.index');
        }

        $country               = checkParam('country');
        $code                  = checkParam('code|iso');
        $sort_by               = checkParam('sort_by') ?? 'name';
        $include_alt_names     = checkParam('include_alt_names');
        $show_restricted       = checkParam('show_restricted');
        $asset_id              = checkParam('bucket_id|asset_id');
        $name                  = checkParam('name|language_name');

        $access_control = $this->accessControl($this->key);

        $cache_string = 'v'.$this->v.'_l_'.$country.$code.$GLOBALS['i18n_id'].$sort_by.$name.
                        $show_restricted.$include_alt_names.$asset_id.$access_control->string;

        $order = $country ? 'country_population.population' : 'name';
        $select_country_population = $country ? 'country_population.population' : '';

        $languages = \Cache::remember($cache_string, now()->addDay(), function () use ($country, $include_alt_names, $asset_id, $code, $name, $show_restricted, $access_control, $order, $select_country_population) {
            $languages = Language::includeCurrentTranslation()
                ->includeAutonymTranslation()
                ->includeExtraLanguages($show_restricted, $access_control, $asset_id)
                ->includeExtraLanguageTranslations($include_alt_names)
                ->includeCountryPopulation($country)
                ->filterableByCountry($country)
                ->filterableByIsoCode($code)
                ->filterableByName($name)
                ->orderBy($order, 'desc')
                ->select([
                    'languages.id',
                    'languages.glotto_id',
                    'languages.iso',
                    'languages.name as backup_name',
                    'current_translation.name as name',
                    'autonym.name as autonym',
                    $select_country_population.' as country_population',
                ])->withCount('bibles')->withCount('filesets')->get();
            return fractal($languages, new LanguageTransformer(), $this->serializer);
        });

        return $this->reply($languages);
    }

    /**
     * @param $id
     *
     * @OA\Get(
     *     path="/languages/{id}",
     *     tags={"Languages"},
     *     summary="Return a single Languages",
     *     description="Returns a single Language",
     *     operationId="v4_languages.one",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="The language ID",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/Language/properties/id")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/Language")),
     *         @OA\MediaType(mediaType="application/xml", @OA\Schema(ref="#/components/schemas/Language")),
     *         @OA\MediaType(mediaType="text/x-yaml", @OA\Schema(ref="#/components/schemas/Language")),
     *         @OA\MediaType(mediaType="text/csv", @OA\Schema(ref="#/components/schemas/Language"))
     *     )
     * )
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($id)
    {
        $cache_string = 'language:'. strtolower($id);
        $language = \Cache::remember($cache_string, now()->addDay(), function () use ($id) {
            $language = Language::where('id', $id)->orWhere('iso', $id)->first();
            if (!$language) {
                return $this->setStatusCode(404)->replyWithError("Language not found for ID: $id");
            }
            $language->load(
                'translations',
                'codes',
                'dialects',
                'classifications',
                'countries',
                'primaryCountry',
                'bibles.translations.language',
                'bibles.filesets',
                'resources.translations',
                'resources.links'
            );
            return fractal($language, new LanguageTransformer());
        });

        return $this->reply($language);
    }

    public function valid($id)
    {
        $cache_string = 'language_single_valid:'. strtolower($id);
        $language = \Cache::remember($cache_string, now()->addDay(), function () use ($id) {
            return Language::where('iso', $id)->exists();
        });

        return $this->reply($language);
    }

}
