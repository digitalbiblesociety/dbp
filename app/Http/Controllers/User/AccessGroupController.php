<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\AccessGroup;
use App\Transformers\AccessGroupTransformer;
use Illuminate\Http\Request;

class AccessGroupController extends APIController
{
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
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		if (!$this->api) return view('access.groups.index');
		$access_groups = AccessGroup::select(['id','name'])->get();
		return $this->reply($access_groups->pluck('name','id'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @OA\Post(
	 *     path="/access/groups/",
	 *     tags={"Admin"},
	 *     summary="Create the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.show",
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
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		($this->api) ? $this->validateUser() : $this->validateUser(Auth::user());
		$invalid = $this->validateAccessGroup($request);
		if($invalid) return $this->setStatusCode(400)->reply($invalid);

		$access_group = AccessGroup::create($request->all());
		if (!$this->api) return redirect()->route('access.groups.show', ['group_id' => $access_group->id]);
		return $this->reply(["message" => "Access Group Successfully Created"]);
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
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$access_group = AccessGroup::with('filesets','types','keys')->find($id);
		return $this->reply(fractal($access_group, new AccessGroupTransformer()));
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

		$access_group = AccessGroup::find($id);
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
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$access_group = AccessGroup::find($id);
		$access_group->delete();

		return $this->reply("successfully delete");
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
			'name'               => 'required|max:64|alpha_dash',
			'description'        => 'string',
			'filesets.*'         => 'exists:bible_filesets,hash_id',
			'keys.*'             => 'exists:user_keys,key',
			'types.*'            => 'exists:access_types,id',
		]);

		if ($validator->fails()) {
			if (!$this->api) return redirect('access/groups/create')->withErrors($validator)->withInput();
			return $this->setStatusCode(422)->replyWithError($validator->errors());
		}
		return false;
	}

	private function validateUser()
	{

	}

}
