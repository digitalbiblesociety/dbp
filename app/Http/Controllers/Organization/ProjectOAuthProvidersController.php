<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\APIController;
use App\Models\User\ProjectOauthProvider;
use Illuminate\Http\Request;
use Validator;

class ProjectOAuthProvidersController extends APIController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @OA\Get(
	 *     path="/projects/{project_id}/oauth-providers/",
	 *     tags={"Users"},
	 *     summary="Returns the oAuth providers being used by a project",
	 *     description="",
	 *     operationId="v4_projects_oAuthProvider.index",
	 *     @OA\Parameter(name="id", in="path", required=true, description="The Project id", @OA\Schema(ref="#/components/schemas/Project/properties/id")),
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
	 */
	public function index()
	{
		$project_id = checkParam('project_id');
		$providers  = ProjectOauthProvider::where('project_id', $project_id)->get();

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
	 *     @OA\Parameter(name="id", in="path", required=true, description="The Project id", @OA\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(required=true, description="Information supplied for oAuth Provider creation", @OA\MediaType(mediaType="application/json",
	 *          @OA\Schema(
	 *              @OA\Property(property="project_id",ref="#/components/schemas/ProjectOauthProvider/properties/project_id"),
	 *              @OA\Property(property="name",ref="#/components/schemas/ProjectOauthProvider/properties/name"),
	 *              @OA\Property(property="client_id",ref="#/components/schemas/ProjectOauthProvider/properties/client_id"),
	 *              @OA\Property(property="client_secret",ref="#/components/schemas/ProjectOauthProvider/properties/client_secret"),
	 *              @OA\Property(property="callback_url",ref="#/components/schemas/ProjectOauthProvider/properties/callback_url"),
	 *              @OA\Property(property="callback_url_alt",ref="#/components/schemas/ProjectOauthProvider/properties/callback_url_alt"),
	 *              @OA\Property(property="description",ref="#/components/schemas/ProjectOauthProvider/properties/description"),
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
		$this->validateOAuthProvider($request);
		$provider = ProjectOauthProvider::create(array_add($request->all(), 'id', 'generated'));

		return $this->reply($provider);
	}

	/**
	 * Display the specified resource.
	 *
	 * @OA\Get(
	 *     path="/projects/{project_id}/oauth-providers/{provider_id}",
	 *     tags={"Users"},
	 *     summary="Return a specific oAuth provider",
	 *     description="",
	 *     operationId="v4_projects_oAuthProvider.show",
	 *     @OA\Parameter(name="id", in="path", required=true, description="The Project id", @OA\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OA\Parameter(name="id", in="path", required=true, description="The Provider id", @OA\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/id")),
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
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($project_id, $provider_id)
	{
		$project_id  = checkParam('project_id', $project_id);
		$provider_id = checkParam('provider_id', $provider_id);
		$provider    = ProjectOauthProvider::where('project_id', $project_id)->where('id', $provider_id)->first();

		return $this->reply($provider);
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
	 *     @OA\Parameter(name="id", in="path", required=true, description="The Project id", @OA\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OA\Parameter(name="id", in="path", required=true, description="The Provider id", @OA\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/id")),
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
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $project_id, $provider_id)
	{
		$project_id = checkParam('project_id', $project_id);
		$provider   = ProjectOauthProvider::where('project_id', $project_id)->where('id', $provider_id)->first();
		$provider->fill($request->all())->save();

		return $this->reply($provider);
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
	 *     @OA\Parameter(name="id", in="path", required=true, description="The Project id",  @OA\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OA\Parameter(name="id", in="path", required=true, description="The Provider id", @OA\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/id")),
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
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$project_id = checkParam('project_id');
		$provider   = ProjectOauthProvider::where('project_id', $project_id)->where('id', $id)->first();
		$provider->delete();

		return $this->reply(trans('api.projects_destroy_200', []));
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
	private function validateOAuthProvider(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'project_id'       => 'required|unique:projects,id',
			'name'             => 'string|max:191|required|in:facebook,twitter,linkedin,google,github,bitbucket',
			'client_id'        => 'string|max:191|required|different:client_secret',
			'client_secret'    => 'string|required|different:client_id',
			'callback_url'     => 'string|max:191|required|url|different:callback_url_alt',
			'callback_url_alt' => 'string|max:191|url|different:callback_url|nullable',
		]);

		if ($validator->fails()) {
			if ($this->api) {
				return $this->setStatusCode(422)->replyWithError($validator->errors());
			}
			if (!$this->api) {
				return redirect('dashboard/projects/oauth-providers/create')->withErrors($validator)->withInput();
			}
		}

	}

}
