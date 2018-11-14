<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\APIController;

use App\Models\Language\Language;
use App\Transformers\LanguageTransformer;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Traits\AccessControlAPI;
use Validator;
class LanguagesController extends APIController
{

    use AccessControlAPI;

    /**
     * Display a listing of the resource.
     * Fetches the records from the database > passes them through fractal for transforming.
     *
     * @param code (optional): Get the entry for a three letter language code.
     * @param name (optional): Get the entry for a part of a language name in either native language or English.
     * @param full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
     * @param sort_by (optional): [code|name|english] Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'.
     *
     * @deprecated family_only (optional): [true|false] When set to true the returned list is of only legal language families. The default is false.
     * @deprecated possibilities (optional); [true|false] When set to true the returned list is a combination of DBP languages and ISO languages not yet defined in DBP that meet any of the criteria.
     *
     * @link https://api.dbp.test/languages?key=1234&v=4&pretty
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/languages/",
     *     tags={"Languages"},
     *     summary="Returns Languages",
     *     description="Returns the List of Languages",
     *     operationId="v4_languages.all",
     *     @OA\Parameter(name="country",in="query",description="The country",@OA\Schema(ref="#/components/schemas/Country/properties/id")),
     *     @OA\Parameter(name="iso",in="query",description="The iso code to filter languages by",@OA\Schema(ref="#/components/schemas/Language/properties/iso")),
     *     @OA\Parameter(name="language_name",in="query",description="The language_name field will filter results by a specific language name",@OA\Schema(type="object")),
     *     @OA\Parameter(name="sort_by",in="query",description="The sort_by field will order results by a specific field",@OA\Schema(type="object")),
     *     @OA\Parameter(name="has_bibles",in="query",description="When set to true will filter language results depending whether or not they have bibles.",@OA\Schema(type="object")),
     *     @OA\Parameter(name="has_filesets",in="query",description="When set to true will filter language results depending whether or not they have filesets. Will add new filesets_count field to the return.",@OA\Schema(type="object",default=null,example=true)),
     *     @OA\Parameter(name="asset_id",in="query",description="The bucket_id",@OA\Schema(ref="#/components/schemas/Asset/properties/id")),
     *     @OA\Parameter(name="include_alt_names",in="query",description="The include_alt_names",@OA\Schema(ref="#/components/schemas/Language/properties/name")),
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
     *
     *
     */
    public function index()
    {
        if (config('app.env') === 'local') ini_set('memory_limit', '700M');
        if (!$this->api) return view('wiki.languages.index');

        $country               = checkParam('country', null, 'optional');
        $code                  = checkParam('code|iso', null, 'optional');
        $sort_by               = checkParam('sort_by', null, 'optional') ?? 'name';
        $include_alt_names     = checkParam('include_alt_names', null, 'optional');
        $show_restricted       = checkParam('show_only_restricted', null, 'optional');
        $asset_id              = checkParam('bucket_id|asset_id', null, 'optional');


        $access_control = $this->accessControl($this->key, 'api');

        $cache_string = 'v' . $this->v . '_languages_' . $country . $code . $GLOBALS['i18n_id'] . $sort_by . $show_restricted . $include_alt_names . $asset_id . $access_control->string;
        if (config('app.env') === 'local') {
            \Cache::forget($cache_string);
        }
        $languages = \Cache::remember($cache_string, 1600, function () use ($country, $include_alt_names, $asset_id, $code, $sort_by, $show_restricted, $access_control) {
            $languages = Language::select(['languages.id', 'languages.glotto_id', 'languages.iso', 'current_translation.name as name', 'autonym.name as autonym'])
                ->leftJoin('language_translations as autonym', function ($leftJoin) {
                    $leftJoin->on('autonym.language_source_id', '=', 'languages.id')
                             ->on('autonym.language_translation_id', '=', 'languages.id')
                             ->where('autonym.priority', '=', \DB::raw('(select max(`priority`) from language_translations WHERE language_translations.language_translation_id = languages.id LIMIT 1)'));
                })
                ->join('language_translations as current_translation', function ($join) {
                    $join->on('current_translation.language_source_id', 'languages.id')
                        ->where('current_translation.language_translation_id', '=', $GLOBALS['i18n_id'])
                        ->where('current_translation.priority', '=', \DB::raw('(select max(`priority`) from language_translations WHERE language_translations.language_source_id = languages.id LIMIT 1)'));
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
                    //$query->has('bibles');
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
     *     @OA\Parameter(name="id", in="path", description="The languages ID", required=true, @OA\Schema(ref="#/components/schemas/Language/properties/id")),
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
        $language = Language::where('id', $id)->orWhere('iso', $id)->first();
        if (!$language) {
            return $this->setStatusCode(404)->replyWithError("Language not found for ID: $id");
        }
        $language->load('translations', 'codes', 'dialects', 'classifications', 'countries', 'primaryCountry', 'bibles.translations.language', 'bibles.filesets', 'resources.translations', 'resources.links');
        if ($this->api) {
            return $this->reply(fractal($language, new LanguageTransformer()));
        }

        return view('wiki.languages.show', compact('language'));
    }

}
