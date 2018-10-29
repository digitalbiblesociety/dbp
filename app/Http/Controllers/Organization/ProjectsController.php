<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\APIController;
use App\Models\User\Key;
use App\Models\User\Project;
use App\Models\User\ProjectMember;
use App\Models\User\Role;
use Illuminate\Http\Request;
use App\Transformers\ProjectTransformer;

use Illuminate\View\View;
use \Illuminate\Http\Response;
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
	 * @return Response
	 */
	public function index()
	{
		if(!$this->api) return view('community.projects.index');
		$projects = Project::where('sensitive', 0)->get();
		return $this->reply(fractal($projects, ProjectTransformer::class));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
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
	 * @return View|\Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$user = \Auth::user() ?? $this->user;
		if(!$user) return $this->setStatusCode(401)->replyWithError(trans('api.auth_permission_denied'));
		$this->validateProject($request);

		$project = \DB::transaction(function () use ($request, $user) {
			$project = Project::create([
				'id'              => random_int(1,65535),
				'name'            => $request->name,
				'url_avatar'      => $request->url_avatar,
				'url_avatar_icon' => $request->url_avatar_icon,
				'url_site'        => $request->url_site,
				'description'     => $request->description,
			]);
			$admin_role = Role::where('slug','admin')->first();
			if(!$admin_role) return $this->replyWithError('No Admin Role Found');
			$developer_role = Role::where('slug','developer')->first();
			if(!$developer_role) return $this->replyWithError('No Developer Role Found');
			$project->members()->create([
				'user_id' => $user->id,
				'role_id' => $admin_role->id
			],[
				'user_id' => $user->id,
				'role_id' => $developer_role->id
			]);

			return $project;
		});

		if(!$this->api) return view('dashboard.projects.show', compact('user', 'project'));
		return $this->setStatusCode(200)->reply(fractal($project,ProjectTransformer::class)->addMeta(['message' => trans('api.projects_created_200')]));
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
	 * @return View|Response
	 */
	public function show($id)
	{
		$access_allowed = true;
		$user           = \Auth::user() ?? $this->user;

		$project = Project::find($id);
		if(!$project) return $this->setStatusCode(404)->replyWithError(trans('api.projects_404'));

		if($project->sensitive) $access_allowed = $user !== null ? ($project->members->contains($user) OR $user->admin) : false;
		if(!$access_allowed) return $this->setStatusCode(404)->replyWithError(trans('api.projects_401'));

		if(!$this->api) return view('community.projects.show', compact('project'));
		return $this->reply(fractal($project,ProjectTransformer::class));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$project = Project::where('id',$id)->first();
		return view('dashboard.projects.edit',compact('project'));
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
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$user = $this->user;
		if(!$user) return $this->setStatusCode(401)->replyWithError("you're not logged in");
		$this->validateProject($request);

		$project = Project::find($id);
		if(!$project) return $this->setStatusCode(404)->replyWithError(trans('api.projects_404'));
		$access_allowed = $project->developers;
		if(!$access_allowed) return $this->setStatusCode(404)->replyWithError(trans('api.projects_401'));

		$project->update($request->all());
		$project->save();

		return $this->setStatusCode(200)->reply(fractal($project,ProjectTransformer::class)->addMeta(['message' => trans('api.projects_updated_200')]));
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
	 * @return Response
	 */
	public function destroy($id)
	{
		$project = Project::find($id);
		if(!$project) return $this->setStatusCode(404)->replyWithError(trans('api.projects_404'));

		$access_allowed = $project->admins->where('user_id',$this->user->id)->first();
		if(!$access_allowed) return $this->setStatusCode(401)->replyWithError(trans('api.projects_destroy_401'));

		$project->delete();
		return $this->setStatusCode(200)->reply(trans('api.projects_destroy_200'));
	}


	/**
	 * Validate Requests to Connect Users to Projects
	 *
	 * @param $token
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function connect($token)
	{
		$project_member = ProjectMember::with('project')->where('token',$token)->first();
		if(!$project_member) return $this->setStatusCode(404)->replyWithError(trans('api.project_errors_member_404'));

		$project_member->subscribed = true;
		$project_member->save();

		return redirect()->away($project_member->project->url_site);
	}


	/**
	 * Validate Requests to Connect Users to Projects
	 *
	 * @param $request
	 *
	 * @return Response|\Illuminate\Http\RedirectResponse|null
	 */
	private function validateProject(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id'   => ($request->method() === 'POST') ? 'required|unique:dbp_users.projects,id|max:24' : 'required|exists:dbp_users.projects,id|max:24',
			'name' => 'required',
		]);

		if($validator->fails()) {
			if($this->api) return $this->setStatusCode(422)->replyWithError($validator->errors());
			if(!$this->api) return redirect('dashboard/projects/create')->withErrors($validator)->withInput();
		}

		return null;
	}
}
