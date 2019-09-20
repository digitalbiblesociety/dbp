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
        $iso             = checkParam('iso');
        $limit           = checkParam('limit') ?? 2000;
        $organization    = checkParam('organization_id');
        $dialects        = checkParam('include_dialects');

        $resources = Resource::with('translations', 'links', 'organization.translations', 'language')
            ->when($iso, function ($query) use ($iso, $dialects) {
                $query->whereHas('language', function ($subquery) use ($iso,$dialects) {
                    if (!$dialects) {
                        $subquery->where('iso', $iso);
                    } else {
                        $language = Language::with('dialects')->where('iso', $iso)->select('id')->get();
                        $subquery->whereIn('id', $language->pluck('id'));
                    }
                });
            })
            ->when($organization, function ($query) use ($organization) {
                $query->whereHas('organization', function ($subquery) use ($organization) {
                    $subquery->where('id', $organization)->orWhere('slug', $organization);
                });
            })
            ->take($limit)->get();

        return $this->reply(fractal($resources, new ResourcesTransformer(), $this->serializer));
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
        $resource = Resource::with('translations', 'links', 'organization.translations')->find($id);
        return $this->reply(fractal($resource, new ResourcesTransformer()));
    }

    private function invalidResource(Request $request)
    {
        $validator = \Validator::make($request->all(), [
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
            if (!$this->api) {
                return redirect('dashboard/resources/create')->withErrors($validator)->withInput();
            }
            return $this->setStatusCode(422)->replyWithError($validator->errors());
        }

        return null;
    }

    /**
    *
    * Returns an array of version return types
    *
    * @category v2_api_jesusFilms
    * @link http://api.dbp4.org/api/reply - V4 Access
    * @link https://api.dbp.test/api/reply?key=1234&v=4&pretty - V4 Test Access
    * @link https://dbp.test/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
    *
    * @OA\Get(
    *     path="/library/jesusfilm",
    *     tags={"Library Video"},
    *     summary="",
    *     description="",
    *     operationId="v2_api_jesusFilms",
    *     @OA\Parameter(name="dam_id", in="query", description="DAM ID for the Jesus Film volume desired.", @OA\Schema(type="string",title="encoding")),
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_video_path")),
    *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_video_path")),
    *         @OA\MediaType(mediaType="text/csv",  @OA\Schema(ref="#/components/schemas/v2_video_path")),
    *         @OA\MediaType(mediaType="text/x-yaml",  @OA\Schema(ref="#/components/schemas/v2_video_path"))
    *     )
    * )
    *
    * @return mixed
    */
    public function jesusFilmListing()
    {
        $id         = checkParam('dam_id');

        $organization = Organization::where('slug', 'the-jesus-film-project')->first();
        $iso          = strtolower(substr($id, 0, 3));
        $language = false;
        if ($iso !== null) {
            $language = Language::where('iso', $iso)->first();
            if (!$language) {
                return $this->setStatusCode(404)->replyWithError('Language not found for provided iso');
            }
        }

        $jesusFilm = Resource::with('translations')
            ->when($language, function ($query) use ($language) {
                $query->where('language_id', $language->id);
            })->where('organization_id', $organization->id)->first();

        return $jesusFilm;
    }
}
