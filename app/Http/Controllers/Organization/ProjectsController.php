<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\APIController;
use App\Models\User\Key;
use App\Models\User\Project;
use Illuminate\Http\Request;
use App\Transformers\ProjectTransformer;

use Validator;

class ProjectsController extends APIController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @OA\Get(
	 *     path="/projects",
	 *     tags={"Users"},
	 *     summary="Returns the projects currently using the DBP",
	 *     description="Returns a list of all your projects currently registered as using the DBP",
	 *     operationId="v4_projects.index",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_projects_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_projects_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_projects_index"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		if (!$this->api) {
			return view('community.projects.index');
		}
		$all_open_projects = checkParam('all_projects', null, 'optional');

		if (!isset($all_open_projects)) {
			$key      = Key::find($this->key);
			$user     = $key->user;
			$projects = ($user->admin) ? Project::all() : $user->projects;
		} else {
			$projects = Project::where('sensitive', 0)->get();
		}

		return $this->reply(fractal()->transformWith(ProjectTransformer::class)->collection($projects));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('dashboard.projects.create');
	}


	/**
	 * Store a new Project
	 *
	 * @OA\Post(
	 *     path="/projects",
	 *     tags={"Users"},
	 *     summary="Apply for a project_id",
	 *     description="It is recommended that you create a distinct project_id for each app using the API",
	 *     operationId="v4_projects.store",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(required=true, description="Information supplied for user creation", @OA\MediaType(mediaType="application/json",
	 *          @OA\Schema(
	 *              @OA\Property(property="name",                    ref="#/components/schemas/Project/properties/name"),
	 *              @OA\Property(property="url_avatar",              ref="#/components/schemas/Project/properties/url_avatar"),
	 *              @OA\Property(property="url_avatar_icon",         ref="#/components/schemas/Project/properties/url_avatar_icon"),
	 *              @OA\Property(property="url_site",                ref="#/components/schemas/Project/properties/url_site"),
	 *              @OA\Property(property="description",             ref="#/components/schemas/Project/properties/description")
	 *          )
	 *     )),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_projects_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_projects_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_projects_index"))
	 *     )
	 * )
	 *
	 * @param Request $request
	 *
	 * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function store(Request $request)
	{
		$user = \Auth::user() ?? Key::find($this->key)->user;
		if (!$user) {
			return $this->setStatusCode(401)->replyWithError(trans('api.auth_permission_denied'));
		}

		$validator = Validator::make($request->all(), [
			'id'   => 'required|unique:projects,id|max:24',
			'name' => 'required',
		]);

		if ($validator->fails()) {
			if ($this->api) {
				return $this->setStatusCode(422)->replyWithError($validator->errors());
			}
			if (!$this->api) {
				return redirect('dashboard/projects/create')->withErrors($validator)->withInput();
			}
		}
		$project = \DB::transaction(function () use ($request, $user) {
			$project = Project::create([
				'name'            => $request->name,
				'url_avatar'      => $request->url_avatar,
				'url_avatar_icon' => $request->url_avatar_icon,
				'url_site'        => $request->url_site,
				'description'     => $request->description,
			]);
			$project->members()->attach($user->id, ['role' => 'member', 'admin' => 1]);

			return $project;
		});

		if (!$this->api) {
			return view('dashboard.projects.show', compact('user', 'project'));
		}

		return fractal()->transformWith(ProjectTransformer::class)->item($project)->addMeta(['message' => 'Project Creation Successful']);
	}

	/**
	 * Display the specified resource.
	 *
	 * @OA\Get(
	 *     path="/projects/{project_id}",
	 *     tags={"Users"},
	 *     summary="Get the details for a project",
	 *     description="",
	 *     operationId="v4_projects.show",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_projects_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_projects_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_projects_index"))
	 *     )
	 * )
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$access_allowed = true;
		$user           = \Auth::user() ?? @Key::find($this->key)->user;

		$project = Project::find($id);
		if (!$project) {
			return $this->setStatusCode(404)->replyWithError("Project Not found");
		}

		if ($project->sensitive) {
			$access_allowed = isset($user) ? ($project->members->contains($user) OR $user->admin) : false;
		}
		if (!$access_allowed) {
			return $this->setStatusCode(404)->replyWithError("Access Not allowed");
		}

		if (!$this->api) {
			return view('community.projects.show', compact('project'));
		}

		return $this->reply(fractal()->transformWith(ProjectTransformer::class)->item($project));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return view('dashboard.projects.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @OA\Put(
	 *     path="/projects/{project_id}",
	 *     tags={"Users"},
	 *     summary="Update the details for a project",
	 *     description="",
	 *     operationId="v4_projects.update",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_projects_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_projects_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_projects_index"))
	 *     )
	 * )
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$user = \Auth::user() ?? Key::find($this->key)->user;
		if (!$user) {
			return $this->setStatusCode(401)->replyWithError("you're not logged in");
		}

		$project = Project::find($id);
		if (!$project) {
			return $this->setStatusCode(404)->replyWithError("Project Not found");
		}

		$access_allowed = ($project->members->contains($user) OR $user->admin) ? true : false;
		if (!$access_allowed) {
			return $this->setStatusCode(404)->replyWithError("Access Not allowed");
		}

		$project->update($request->all());
		$project->save();

		return $this->reply(trans('api.'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @OA\Delete(
	 *     path="/projects/{project_id}",
	 *     tags={"Users"},
	 *     summary="Remove a project",
	 *     description="",
	 *     operationId="v4_projects.update",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_projects_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_projects_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_projects_index"))
	 *     )
	 * )
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$user = \Auth::user() ?? Key::find($this->key)->user;
		if (!$user) {
			return $this->setStatusCode(401)->replyWithError("you're not logged in");
		}

		$project = Project::find($id);
		if (!$project) {
			return $this->setStatusCode(404)->replyWithError("Project Not found");
		}

		$access_allowed = $project->admins->contains($user->id);
		if (!$access_allowed) {
			return $this->setStatusCode(404)->replyWithError("You must be an admin to delete a project");
		}

		$project->delete();

		return $this->reply("project deleted");
	}
}
