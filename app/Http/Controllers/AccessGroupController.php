<?php

namespace App\Http\Controllers;

use App\Models\User\AccessGroup;
use App\Transformers\AccessGroupTransformer;
use Illuminate\Http\Request;

class AccessGroupController extends APIController
{
	/**
	 * Update the specified resource in storage.
	 *
	 * @OAS\Get(
	 *     path="/access/groups/",
	 *     tags={"Admin"},
	 *     summary="Update the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.index",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/AccessGroup"))
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
	 * @OAS\Post(
	 *     path="/access/groups/",
	 *     tags={"Admin"},
	 *     summary="Create the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.show",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Parameter(name="access_group_id", in="path", required=true, @OAS\Schema(ref="#/components/schemas/AccessGroup/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/AccessGroup"))
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
	 * @OAS\Get(
	 *     path="/access/groups/{group_id}",
	 *     tags={"Admin"},
	 *     summary="Update the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.show",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Parameter(name="access_group_id", in="path", required=true, @OAS\Schema(ref="#/components/schemas/AccessGroup/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/AccessGroup"))
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
	 * @OAS\Put(
	 *     path="/access/groups/{group_id}",
	 *     tags={"Admin"},
	 *     summary="Update the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.update",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Parameter(name="access_group_id", in="path", required=true, @OAS\Schema(ref="#/components/schemas/AccessGroup/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/AccessGroup"))
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
	 * @OAS\Delete(
	 *     path="/access/groups/{group_id}",
	 *     tags={"Admin"},
	 *     summary="Remove the specified Access group",
	 *     description="",
	 *     operationId="v4_access_groups.destroy",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Parameter(name="access_group_id", in="path", required=true, @OAS\Schema(ref="#/components/schemas/AccessGroup/properties/id")),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/AccessGroup")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/AccessGroup"))
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
