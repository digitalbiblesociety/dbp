<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\AccessGroup;
use App\Models\User\Key;
use App\Traits\AccessControlAPI;
use App\Transformers\AccessGroupTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccessGroupController extends APIController
{

	use AccessControlAPI;

	/**
	 * Update the specified resource in storage.
	 *
	 * @OA\Get(
	 *     path="/access/groups/",
	 *     tags={"Admin"},
	 *     summary="Update the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.index",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/AccessGroup"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		if (!$this->api) return view('access.groups.index');

		if(config('app.env') === 'local') \Cache::forget('access_groups');
		$access_groups = \Cache::remember('access_groups', 1800,  function () {
			$access_groups = AccessGroup::select(['id','name'])->get();
			return $access_groups->pluck('name','id');
		});

		return $this->reply($access_groups);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @OA\Post(
	 *     path="/access/groups/",
	 *     tags={"Admin"},
	 *     summary="Create the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.store",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/AccessGroup"))
	 *     )
	 * )
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$invalidUser = $this->validateUser();
		if($invalidUser) return $invalidUser;

		$invalid = $this->validateAccessGroup($request);
		if($invalid) return $this->setStatusCode(400)->reply($invalid);

		$access_group = \DB::transaction(function () use($request) {
			$access_group = AccessGroup::create($request->only(['name','description']));
			if($request->filesets) {
				foreach($request->filesets as $fileset) $access_group->filesets()->create(['hash_id' => $fileset]);
				foreach($request->users as $user) $access_group->users()->create(['user_id' => $user]);
			}
			return $access_group;
		});

		if(!$this->api) return redirect()->route('access.groups.show', ['group_id' => $access_group->id]);
		return $this->reply($access_group);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @OA\Get(
	 *     path="/access/groups/{group_id}",
	 *     tags={"Admin"},
	 *     summary="Update the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.show",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="group_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/AccessGroup/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/AccessGroup"))
	 *     )
	 * )
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(config('app.env') === 'local') \Cache::forget('access_group_'.$id);
		$access_group = \Cache::remember('access_group_'.$id, 1800,  function () use($id) {
			$access_group = AccessGroup::with('filesets','types','keys')->where('id',$id)->orWhere('name',$id)->first();
			if(!$access_group) return $this->setStatusCode(404)->replyWithError(trans('api.access_group_404'));
			$access_group->current_key = $this->key;
			return fractal($access_group, new AccessGroupTransformer());
		});

		// If The access group is an error message don't pass it through to reply()
		if(is_a($access_group, JsonResponse::class)) return $access_group;

		return $this->reply($access_group);
	}

	public function current()
	{
		$current_access = $this->accessControl($this->key, 'api');
		$current_access->hash_count = \count($current_access->hashes);
		return $this->reply($current_access);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @OA\Put(
	 *     path="/access/groups/{group_id}",
	 *     tags={"Admin"},
	 *     summary="Update the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.update",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="access_group_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/AccessGroup/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/AccessGroup"))
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
		$invalid = $this->validateAccessGroup($request);
		if($invalid) return $this->setStatusCode(400)->reply($invalid);

		$access_group = AccessGroup::where('id',$id)->orWhere('name',$id)->first();
		if(!$access_group) return $this->setStatusCode(404)->replyWithError(trans('api.'));
		$access_group->fill($request->all())->save();

		if(isset($request->filesets)) $access_group->filesets()->createMany($request->filesets);
		if(isset($request->keys)) $access_group->keys()->createMany($request->keys);
		if(isset($request->types)) $access_group->keys()->sync($request->types);

		return $this->reply($access_group);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @OA\Delete(
	 *     path="/access/groups/{group_id}",
	 *     tags={"Admin"},
	 *     summary="Remove the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.destroy",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="access_group_id", in="path", required=true, @OA\Schema(ref="#/components/schemas/AccessGroup/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/AccessGroup"))
	 *     )
	 * )
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$invalidUser = $this->validateUser();
		if($invalidUser) return $invalidUser;

		$access_group = AccessGroup::where('id',$id)->orWhere('name',$id)->first();
		if(!$access_group) return $this->setStatusCode(404)->replyWithError('Access Group not Found');
		$access_group->delete();

		return $this->reply('successfully deleted');
	}

	/**
	 * Ensure the current access_group change is valid
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	private function validateAccessGroup(Request $request)
	{
		$validator = \Validator::make($request->all(), [
			'name'               => ($request->method() === 'POST') ? 'required|max:64|alpha_dash|unique:dbp.access_groups,name' : 'max:64|alpha_dash|exists:dbp.access_groups,name',
			'description'        => 'string',
			'filesets.*'         => 'exists:dbp.bible_filesets,hash_id',
			'keys.*'             => 'exists:dbp_users.user_keys,key',
			'types.*'            => 'exists:dbp.access_types,id',
		]);

		if ($validator->fails()) {
			if (!$this->api) return redirect('access/groups/create')->withErrors($validator)->withInput();
			return $this->setStatusCode(422)->replyWithError($validator->errors());
		}
		return false;
	}

	private function validateUser()
	{
		$is_admin = $this->user->roles->where('slug','admin')->first();
		if($is_admin) return true;
		return null;
	}

}
