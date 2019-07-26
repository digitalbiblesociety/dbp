<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\APIController;
use App\Models\Bible\Bible;
use App\Models\Bible\BibleLink;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Transformers\OrganizationTransformer;
use App\Transformers\BibleTransformer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OrganizationsController extends APIController
{

    /**
     * Display a listing of the organizations.
     *
     * @version 2
     * @category v2_library_book
     * @category v2_library_bookOrder
     * @link http://dbt.io/library/volumeorganization - V2 Access
     * @link http://api.dbp.test/library/volumeorganization?v=2&dam_id=AMKWBT&pretty&key={key} - V2 Test
     *
     * @OA\Get(
     *     path="/library/volumeorganization/",
     *     tags={"Library Volume Organization"},
     *     summary="",
     *     description="",
     *     operationId="v2_volume_organization_list",
     *     @OA\Parameter(
     *          name="membership",
     *          in="query",
     *          description="",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="has_content",
     *          in="query",
     *          description="",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="bibles",
     *          in="query",
     *          description="",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="resources",
     *          in="query",
     *          description="",
     *          @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_volume_organization_list")),
     *         @OA\MediaType(mediaType="application/xml", @OA\Schema(ref="#/components/schemas/v2_volume_organization_list")),
     *         @OA\MediaType(mediaType="text/csv", @OA\Schema(ref="#/components/schemas/v2_volume_organization_list")),
     *         @OA\MediaType(mediaType="text/x-yaml", @OA\Schema(ref="#/components/schemas/v2_volume_organization_list"))
     *     )
     * )
     *
     * @param dam_id - the volume internal bible_id.
     *
     * @return Book string - A JSON string that contains the status code and error messages if applicable.
     */
    public function index()
    {
        $membership  = checkParam('membership');
        $content     = checkParam('has_content');
        $bibles      = checkParam('bibles');
        $resources   = checkParam('resources');
        $language_id = $GLOBALS['i18n_id'];

        $cache_string = strtolower($this->v . '_organizations:' . $language_id . $membership . $content . $bibles . $resources);
        $organizations = \Cache::remember($cache_string, now()->addDay(), function () use ($language_id, $membership, $content, $bibles, $resources) {

            $organizations = Organization::with('translations')
                ->includeMemberResources($membership)
                ->includeLogos($language_id)
                ->has('translations')
                ->withCount('bibles')
                ->when($bibles, function ($q) {
                    $q->has('bibles')->orHas('links');
                })->when($resources, function ($q) {
                    $q->has('resources');
                })->when($content, function ($q) {
                    $q->has('resources');
                })->get();

                return fractal($organizations, new OrganizationTransformer(), $this->serializer);
            }
        );

        return $this->reply($organizations);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     *
     * @return mixed
     */
    public function show($id)
    {
        $organization = \Cache::remember($this->v . '_organizations:'.$id, now()->addDay(), function () use ($id) {
            $organization = Organization::includeLogos($GLOBALS['i18n_id'])
                ->where('id', $id)->orWhere('slug', $id)
                ->with([
                    'translations',
                    'currentTranslation'
                ])->first();

            if (!$organization) {
                return $this->setStatusCode(404)->replyWithError(trans('api.organizations_errors_404', ['id' => $id]));
            }

            return fractal($organization, new OrganizationTransformer(), $this->serializer);
        });

        return $this->reply($organization);
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public function bibles(string $slug)
    {
        $organization = Organization::with('bibles')->where('slug', $slug)->first();

        return $this->reply(fractal($organization->bibles,new BibleTransformer()));
    }

    public function compare()
    {
        $source_organization = checkParam('source_organization', true);
        $destination_organization = checkParam('destination_organization', true);

        $source_organization = Organization::with('bibles')->where('slug', $source_organization)->first();
        if (!$source_organization) {
            return $this->setStatusCode(404)->replyWithError('source_organization not found');
        }
        $source_bibles = $source_organization->bibles->pluck('id');
        $source_links = BibleLink::where('organization_id', $source_organization->id)->get()->pluck('bible_id');
        $source = $source_bibles->merge($source_links);

        $destination_organization = Organization::with('bibles')->where('slug', $destination_organization)->first();
        if (!$destination_organization) {
            return $this->setStatusCode(404)->replyWithError('destination_organization not found');
        }
        $destination_bibles = $destination_organization->bibles->pluck('id');
        $destination_links = BibleLink::where('organization_id', $destination_organization->id)->get()->pluck('bible_id');
        $destination = $destination_bibles->merge($destination_links);

        $bible_array = Arr::flatten(Arr::sort($destination->diff($source)->unique()));
        $bibles = Bible::with('translations')->whereIn('id',$bible_array)->get();

        return $this->reply($bibles);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return mixed
     */
    public function create()
    {
        $user = \Auth::user() ?? $this->user;
        if (!$user->archivist) {
            return $this->setStatusCode(401)->replyWithError(trans('api.wiki_authorization_failed'));
        }
        return view('community.organizations.create');
    }

    public function apply()
    {
        $user = \Auth::user() ?? $this->user;
        $organizations = Organization::with('translations')->get();

        return view('dashboard.organizations.roles.create', compact('user', 'organizations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return mixed
     */
    public function store()
    {
        $organization = new Organization();
        $organization->save(request()->except(['translations']));
        foreach (request()->translations as $translation) {
            $organization->translations()->create(['iso'         => $translation['iso'],
                                                   'name'        => $translation['translation'],
                                                   'description' => '',
            ]);
        }

        return view('community.organizations.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $slug
     *
     * @return View
     */
    public function edit($slug)
    {
        $organization = Organization::where('slug', $slug)->first();

        return view('community.organizations.edit', compact('organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string $slug
     *
     * @return View
     */
    public function update($slug)
    {
        $organization = Organization::where('slug', $slug)->first();
        if (!$organization) {
            return $this->setStatusCode(404)->replyWithError(trans('api.organizations_errors_404'));
        }
        $organization->update(request()->except(['translations']));
        $organization->translations()->delete();
        foreach (request()->translations as $translation) {
            $organization->translations()->create(['iso' => $translation['iso'], 'name' => $translation['translation'], 'description' => '']);
        }

        return view('community.organizations.show', compact('organization'));
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
        $organization = Organization::find($id);
        $organization->delete();

        if ($this->api) {
            return $this->setStatusCode(200)->reply('Organization successfully deleted');
        }

        $organizations = Organization::with('currentTranslation')->get();
        return view('community.organizations.index', compact('organizations'));
    }
}
