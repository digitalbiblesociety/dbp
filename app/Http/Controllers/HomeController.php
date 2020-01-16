<?php

namespace App\Http\Controllers;

use App\Models\Bible\BibleFileset;
use App\Models\Country\Country;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Asset;
use App\Models\Organization\Organization;
use App\Models\Bible\Bible;
use App\Models\Resource\Resource;
use App\Traits\CallsBucketsTrait;

class HomeController extends APIController
{
    use CallsBucketsTrait;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();

        return view('home', compact('user'));
    }

    public function admin()
    {
        $status['updates'] = '';

        return view('dashboard.admin', compact('status'));
    }

    /**
     * Returns a List of Assets used by the API
     *
     * @OA\Get(
     *     path="/api/buckets",
     *     tags={"Bibles"},
     *     summary="Returns the asset paths currently being used by the api",
     *     description="",
     *     operationId="v4_api.assets",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_api_assets")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_api_assets")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_api_assets")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_api_assets"))
     *     )
     * )
     *
     * @OA\Schema (
     *     type="array",
     *     schema="v4_api_assets",
     *     description="The aws assets currently being used by the api",
     *     title="The assets response",
     *     required={"id","organization_id"},
     *     @OA\Xml(name="v4_api_assets"),
     *     @OA\Items(
     *          @OA\Property(property="id",               ref="#/components/schemas/Asset/properties/id"),
     *          @OA\Property(property="asset_type",       ref="#/components/schemas/Asset/properties/asset_type"),
     *          @OA\Property(property="organization_id",  ref="#/components/schemas/Asset/properties/organization_id"),
     *          @OA\Property(property="organization", type="object",
     *             @OA\Property(property="id", ref="#/components/schemas/Organization/properties/id"),
     *             @OA\Property(property="slug", ref="#/components/schemas/Organization/properties/slug"),
     *             @OA\Property(property="email", ref="#/components/schemas/Organization/properties/email")
     *          )
     *   )
     * )
     *
     * @return mixed
     */
    public function buckets()
    {
        $assets = \Cache::remember('v4_api_assets', now()->addDay(), function () {
            return Asset::select('id', 'asset_type', 'organization_id')->with([
                'organization' => function ($query) {
                    $query->select('slug', 'email', 'id');
                }
            ])->get();
        });

        return $this->reply($assets);
    }

    public function stats()
    {
        $counts = \Cache::remember('v4_api_counts', now()->addDay(), function () {
            $count['languages']      = Language::count();
            $count['countries']      = Country::count();
            $count['alphabets']      = Alphabet::count();
            $count['organizations']  = Organization::count();
            $count['bible_filesets'] = BibleFileset::count();
            $count['bibles']         = Bible::count();
            $count['resources']      = Resource::count();
            return $count;
        });

        return $this->reply($counts);
    }

    public function versions()
    {
        return $this->reply(['versions' => [2, 4]]);
    }
}
