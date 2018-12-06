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
     *     path="/languages/",
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
     *          description="The iso code to filter languages by"
     *     ),
     *     @OA\Parameter(
     *          name="language_name",
     *          in="query",
     *          @OA\Schema(type="object"),
     *          description="The language_name field will filter results by a specific language name"
     *     ),
     *     @OA\Parameter(
     *          name="sort_by",
     *          in="query",
     *          @OA\Schema(type="object"),
     *          description="The sort_by field will order results by a specific field"
     *     ),
     *     @OA\Parameter(
     *          name="has_bibles",
     *          in="query",
     *          @OA\Schema(type="object"),
     *          description="When set to true will filter language results depending whether or not they have bibles."
     *     ),
     *     @OA\Parameter(
     *          name="has_filesets",
     *          in="query",
     *          @OA\Schema(type="object",default=null,example=true),
     *          description="When set to true will filter language results depending whether or not they have filesets.
     *              Will add new filesets_count field to the return."
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
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/Language"))
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
        $show_restricted       = checkParam('show_only_restricted');
        $asset_id              = checkParam('bucket_id|asset_id');

        $access_control = \Cache::remember($this->key.'_access_control', 2400, function () {
            return $this->accessControl($this->key);
        });

        $cache_string = 'v'.$this->v.'_l_'.$country.$code.$GLOBALS['i18n_id'].$sort_by.
                        $show_restricted.$include_alt_names.$asset_id.$access_control->string;

        $languages = \Cache::remember($cache_string, 1600, function ()
 use ($country, $include_alt_names, $asset_id, $code, $sort_by, $show_restricted, $access_control) {
            $languages = Language::select([
                    'languages.id',
                    'languages.glotto_id',
                    'languages.iso',
                    'languages.name as backup_name',
                    'current_translation.name as name',
                    'autonym.name as autonym'
                ])
                ->leftJoin('language_translations as autonym', function ($join) {
                    $priority_q = \DB::raw('(select max(`priority`) FROM language_translations
                        WHERE language_translation_id = languages.id AND language_source_id = languages.id LIMIT 1)');
                    $join->on('autonym.language_source_id', '=', 'languages.id')
                             ->on('autonym.language_translation_id', '=', 'languages.id')
                             ->orderBy('autonym.priority', '=', $priority_q)->limit(1);
                })
                ->leftJoin('language_translations as current_translation', function ($join) {
                    $priority_q = \DB::raw('(select max(`priority`) from language_translations
                        WHERE language_source_id = languages.id LIMIT 1)');
                    $join->on('current_translation.language_source_id', 'languages.id')
                        ->where('current_translation.language_translation_id', '=', $GLOBALS['i18n_id'])
                        ->where('current_translation.priority', '=', $priority_q)->limit(1);
                })

                ->when(!$show_restricted, function ($query) use ($access_control, $asset_id) {
                    $query->whereHas('filesets', function ($query) use ($access_control, $asset_id) {
                        $query->whereIn('hash_id', $access_control->hashes);
                        if ($asset_id) {
                            $asset_id = explode(',', $asset_id);
                            $query->whereHas('fileset', function ($query) use ($asset_id) {
                                $query->whereIn('asset_id', $asset_id);
                            });
                        }
                    });
                })
                ->when($include_alt_names, function ($query) {
                    return $query->with('translations');
                })
                ->when($country, function ($query) use ($country) {
                    return $query->whereHas('countries', function ($query) use ($country) {
                        $query->where('country_id', $country);
                    });
                })->when($code, function ($query) use ($code) {
                    return $query->where('iso', $code);
                })->when($sort_by, function ($query) use ($sort_by) {
                    return $query->orderBy($sort_by);
                })->withCount('bibles')->withCount('filesets')->get();

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
     *          description="The languages ID",
     *          required=true,
     *          @OA\Schema(ref="#/components/schemas/Language/properties/id")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(ref="#/components/schemas/Language")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($id)
    {
        $language = \Cache::remember('single_language_'.$id, 2400, function () use ($id) {
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
}
