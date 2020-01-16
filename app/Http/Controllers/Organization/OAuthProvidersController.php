<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\APIController;
use App\Models\User\Project;
use App\Models\User\ProjectMember;
use App\Models\User\ProjectOauthProvider;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Validator;

use Illuminate\Support\Arr;

class OAuthProvidersController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/projects/{project_id}/oauth",
     *     tags={"Users"},
     *     summary="A Project's oAuth Providers",
     *     description="Returns the oAuth providers being used by a project. This route can inform
     *          developers about the potential login options provided to users by each project.",
     *     operationId="v4_projects_oAuthProvider.index",
     *     @OA\Parameter(name="project_id", in="path", required=true, description="The Project id", @OA\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OA\Parameter(name="provider_id", in="query", description="The Provider id", @OA\Schema(ref="#/components/schemas/Account/properties/provider_id")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_projects_oAuthProvider.index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_projects_oAuthProvider.index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_projects_oAuthProvider.index")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_projects_oAuthProvider.index"))
     *     )
     * )
     *
     * @OA\Schema(
     *   schema="v4_projects_oAuthProvider.index",
     *   type="object",
     *   @OA\Property(property="data", type="array",
     *      @OA\Items(ref="#/components/schemas/ProjectOauthProvider")
     *   )
     * )
     * @param string $project_id
     *
     * @return mixed
     */
    public function index(string $project_id)
    {
        $project_id = checkParam('project_id', true, $project_id);
        $provider_id = checkParam('provider_id');

        $providers  = ProjectOauthProvider::where('project_id', $project_id)
            ->when($provider_id, function ($query) use ($provider_id) {
                $query->where('name', $provider_id);
            })->get();

        return $this->reply($providers);
    }

    public function store(Request $request)
    {
        $invalidRequest = $this->invalidOAuthProvider($request);
        if ($invalidRequest) {
            return $invalidRequest;
        }

        $provider = ProjectOauthProvider::create(Arr::add($request->all(), 'id', 'generated'));
        return $this->setStatusCode(200)->reply($provider);
    }

    public function update(Request $request, $project_id, $provider_id)
    {
        $invalidRequest = $this->invalidOAuthProvider($request);
        if ($invalidRequest) {
            return $invalidRequest;
        }

        $project_id = checkParam('project_id', true, $project_id);
        $provider   = ProjectOauthProvider::where('project_id', $project_id)->where('id', $provider_id)->first();
        $provider->fill($request->all())->save();

        return $this->setStatusCode(200)->reply($provider);
    }

    public function destroy($project_id, $id)
    {
        $project_id = checkParam('project_id', true, $project_id);
        $project    = Project::where('id', $project_id)->first();
        if (!$project) {
            return $this->setStatusCode(404)->replyWithError(trans('api.projects_404'));
        }

        $roles = Role::where('slug', 'admin')->orWhere('slug', 'developer')->select('id')->get()->pluck('id')->toArray();
        $access_allowed = ProjectMember::where('project_id', $project_id)
            ->where('user_id', $this->user->id)
            ->whereIn('role_id', $roles)->first();
        if (!$access_allowed) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_destroy_401'));
        }

        $provider = ProjectOauthProvider::where('project_id', $project_id)->where('id', $id)->first();
        $provider->delete();

        return $this->setStatusCode(200)->reply(trans('api.projects_destroy_200'));
    }

    /**
     * Ensure the current oAuth provider change is valid
     *
     *
     *
     * @param Request $request
     *
     * @return mixed
     */
    private function invalidOAuthProvider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id'       => (($request->method() === 'POST') ? 'required|' : '') . 'exists:dbp_users.projects,id',
            'name'             => (($request->method() === 'POST') ? 'required|' : '') . 'string|max:191|in:facebook,twitter,linkedin,google,github,bitbucket,test_provider',
            'client_id'        => (($request->method() === 'POST') ? 'required|' : '') . 'string|max:191|different:client_secret',
            'client_secret'    => (($request->method() === 'POST') ? 'required|' : '') . 'string|different:client_id',
            'callback_url'     => (($request->method() === 'POST') ? 'required|' : '') . 'string|max:191|url'
        ]);

        if ($validator->fails()) {
            if ($this->api) {
                return $this->setStatusCode(422)->replyWithError($validator->errors());
            }
            if (!$this->api) {
                return redirect('dashboard/projects/oauth-providers/create')->withErrors($validator)->withInput();
            }
        }
        return false;
    }
}
