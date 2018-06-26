<?php

namespace App\Http\Controllers;

use App\Models\User\ProjectOauthProvider;
use Illuminate\Http\Request;
use Validator;

class ProjectOAuthProvidersController extends APIController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @OAS\Get(
	 *     path="/projects/{project_id}/oauth-providers/",
	 *     tags={"Users"},
	 *     summary="Returns the oAuth providers being used by a project",
	 *     description="",
	 *     operationId="v4_projects_oAuthProvider.index",
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The Project id", @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider"))
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
	 * @OAS\Post(
	 *     path="/projects/{project_id}/oauth-providers/",
	 *     tags={"Users"},
	 *     summary="Add a new oAuth provider to a project",
	 *     description="",
	 *     operationId="v4_projects_oAuthProvider.store",
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The Project id", @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\RequestBody(required=true, description="Information supplied for oAuth Provider creation", @OAS\MediaType(mediaType="application/json",
	 *          @OAS\Schema(
	 *              @OAS\Property(property="project_id",ref="#/components/schemas/ProjectOauthProvider/properties/project_id"),
	 *              @OAS\Property(property="name",ref="#/components/schemas/ProjectOauthProvider/properties/name"),
	 *              @OAS\Property(property="client_id",ref="#/components/schemas/ProjectOauthProvider/properties/client_id"),
	 *              @OAS\Property(property="client_secret",ref="#/components/schemas/ProjectOauthProvider/properties/client_secret"),
	 *              @OAS\Property(property="callback_url",ref="#/components/schemas/ProjectOauthProvider/properties/callback_url"),
	 *              @OAS\Property(property="callback_url_alt",ref="#/components/schemas/ProjectOauthProvider/properties/callback_url_alt"),
	 *              @OAS\Property(property="description",ref="#/components/schemas/ProjectOauthProvider/properties/description"),
	 *          )
	 *     )),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider"))
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
	 * @OAS\Get(
	 *     path="/projects/{project_id}/oauth-providers/{provider_id}",
	 *     tags={"Users"},
	 *     summary="Return a specific oAuth provider",
	 *     description="",
	 *     operationId="v4_projects_oAuthProvider.show",
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The Project id", @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The Provider id", @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider"))
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
	 * @OAS\Put(
	 *     path="/projects/{project_id}/oauth-providers/{provider_id}",
	 *     tags={"Users"},
	 *     summary="Update a specific oAuth provider",
	 *     description="",
	 *     operationId="v4_projects_oAuthProvider.update",
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The Project id", @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The Provider id", @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider"))
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
	 * @OAS\Delete(
	 *     path="/projects/{project_id}/oauth-providers/{provider_id}",
	 *     tags={"Users"},
	 *     summary="Delete a specific oAuth provider",
	 *     description="",
	 *     operationId="v4_projects_oAuthProvider.destroy",
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The Project id",  @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The Provider id", @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider"))
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
