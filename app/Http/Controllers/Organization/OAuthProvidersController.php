<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\APIController;
use App\Models\User\Project;
use App\Models\User\ProjectMember;
use App\Models\User\ProjectOauthProvider;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Validator;

class OAuthProvidersController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/projects/{project_id}/oauth-providers/",
     *     tags={"Users"},
     *     summary="A Project's oAuth Providers",
     *     description="Returns the oAuth providers being used by a project. This route can inform
     *         developers about the potential login options provided to users by each project.",
     *     operationId="v4_projects_oAuthProvider.index",
     *     @OA\Parameter(name="project_id", in="path", required=true, description="The Project id", @OA\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ProjectOauthProvider")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/ProjectOauthProvider")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/ProjectOauthProvider"))
     *     )
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

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/projects/{project_id}/oauth-providers/",
     *     tags={"Users"},
     *     summary="Add a new oAuth provider to a project",
     *     description="",
     *     operationId="v4_projects_oAuthProvider.store",
     *     @OA\Parameter(name="project_id", in="path", required=true, description="The Project id", @OA\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\RequestBody(required=true, description="Information supplied for oAuth Provider creation", @OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="project_id",       ref="#/components/schemas/ProjectOauthProvider/properties/project_id"),
     *              @OA\Property(property="name",             ref="#/components/schemas/ProjectOauthProvider/properties/name"),
     *              @OA\Property(property="client_id",        ref="#/components/schemas/ProjectOauthProvider/properties/client_id"),
     *              @OA\Property(property="client_secret",    ref="#/components/schemas/ProjectOauthProvider/properties/client_secret"),
     *              @OA\Property(property="callback_url",     ref="#/components/schemas/ProjectOauthProvider/properties/callback_url"),
     *              @OA\Property(property="description",      ref="#/components/schemas/ProjectOauthProvider/properties/description"),
     *          )
     *     )),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ProjectOauthProvider")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/ProjectOauthProvider")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/ProjectOauthProvider"))
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $invalidRequest = $this->invalidOAuthProvider($request);
        if ($invalidRequest) {
            return $invalidRequest;
        }

        $provider = ProjectOauthProvider::create(array_add($request->all(), 'id', 'generated'));
        return $this->setStatusCode(200)->reply($provider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/projects/{project_id}/oauth-providers/{provider_id}",
     *     tags={"Users"},
     *     summary="Update a specific oAuth provider",
     *     description="",
     *     operationId="v4_projects_oAuthProvider.update",
     *     @OA\Parameter(name="project_id", in="path", required=true, description="The Project id", @OA\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OA\Parameter(name="provider_id", in="path", required=true, description="The Provider id", @OA\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/id")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ProjectOauthProvider")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/ProjectOauthProvider")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/ProjectOauthProvider"))
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request $request
     * @param                           $project_id
     * @param                           $provider_id
     *
     * @return \Illuminate\Http\Response
     *
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/projects/{project_id}/oauth-providers/{provider_id}",
     *     tags={"Users"},
     *     summary="Delete a specific oAuth provider",
     *     description="",
     *     operationId="v4_projects_oAuthProvider.destroy",
     *     @OA\Parameter(name="project_id", in="path", required=true, description="The Project id",  @OA\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OA\Parameter(name="provider_id", in="path", required=true, description="The Provider id", @OA\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/id")),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ProjectOauthProvider")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/ProjectOauthProvider")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/ProjectOauthProvider"))
     *     )
     * )
     *
     * @param      $project_id
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $id)
    {
        $project_id = checkParam('project_id', true, $project_id);

        $project = Project::where('id', $project_id)->first();
        if (!$project) {
            return $this->setStatusCode(404)->replyWithError(trans('api.projects_404'));
        }

        $admin_role = Role::where('slug','admin')->first();

        echo "attempting project_id".$project_id.': user_id: '.$this->user->id.': role_id'.$admin_role->id;

        $access_allowed = ProjectMember::where('project_id',$project_id)
            ->where('user_id', $this->user->id)
            ->where('role_id', $admin_role->id)->first();
        if (!$access_allowed) {
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_destroy_401'));
        }

        $provider   = ProjectOauthProvider::where('project_id', $project_id)->where('id', $id)->first();
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
