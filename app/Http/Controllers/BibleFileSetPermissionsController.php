<?php

namespace App\Http\Controllers;

use App\Models\User\Key;
use App\Models\User\Access;
use App\Models\Bible\BibleFileset;
use App\Transformers\BibleFilePermissionsTransformer;

use Illuminate\Http\Request;

class BibleFileSetPermissionsController extends APIController
{

	/**
	 * Returns an index of fileset permissions
	 *
	 * @version 4
	 * @category v4_bible_filesets.permissions_index
	 * @link http://bible.build/bibles/filesets/AMKWBT/permissions - V4 Access
	 * @link https://api.dbp.dev/bibles/filesets/AMKWBT/permissions?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/v4#/Bible/v4_bible_chapter2 - V4 Test Docs
	 *
	 * @OAS\Get(
	 *     path="/bibles/filesets/{id}/permissions/",
	 *     tags={"Bibles"},
	 *     summary="Returns a list of permissions for a specific Fileset",
	 *     description="Returns filtered permissions for a fileset dependent upon your authorization level and API key",
	 *     operationId="v4_bible_filesets_permissions.index",
	 *     @OAS\Parameter(name="id", in="path", required=true, description="The fileset ID", @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(
	 *            mediaType="application/json",
	 *            @OAS\Schema(ref="#/components/schemas/v4_bible_filesets_permissions.index")
	 *         )
	 *     )
	 * )
	 *
	 * @param string $fileset_id - The fileset_id for which permissions are being queried
	 *
	 * @return mixed $fileset string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
	public function index($fileset_id)
	{
		if (!$this->api) {
			$user = \Auth::user();
			if (!$user) {
				return $this->setStatusCode(401)->replyWithError(trans('api.auth_permission_denied'));
			}

			return view('bibles.filesets.permissions.index');
		}

		$filesets = Access::with('fileset.bible.translations')->where('hash_id', $fileset_id)->get();
		if (!$filesets) {
			return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404',
				['id' => $fileset_id]));
		}
		$fileset = $filesets->where('key_id', $this->key)->first();
		if (!$fileset) {
			return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_401'));
		}

		return $this->reply(fractal()->item($fileset)->transformWith(BibleFilePermissionsTransformer::class)->serializeWith($this->serializer));
	}


	/**
	 * Store a new fileset permission.
	 *
	 * @version 4
	 * @category v4_bible_filesets_permissions.store
	 * @link http://bible.build/bibles/filesets/AMKWBT/permissions - V4 Access [POST]
	 * @link https://dbp.dev/bibles/filesets/AMKWBT/permissions - V4 Test Access [POST]
	 *
	 * @param string $fileset_id
	 * @param Request $request
	 *
	 * @return mixed $fileset string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
	public function store(string $fileset_id, Request $request)
	{
		$this->validateUser();
		$this->validateBibleFilesetPermission($request);

		$fileset = Fileset::with('bucket')->where('hash_id', $request->hash_id)->first();
		if (!$fileset) {
			return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404',
				['id' => $fileset_id]));
		}

		Access::create([
			'hash_id'        => $request->hash_id,
			'key_id'         => $request->key_id,
			'access_type'    => $request->access_type,
			'access_notes'   => $request->access_notes,
			'access_granted' => $request->access_granted ?? 0,
		]);

		return redirect()->route('view_bible_filesets_permissions.index', ['id' => $fileset_id]);
	}

	/**
	 * Update an existing fileset permission.
	 *
	 * @version 4
	 * @category v4_bible_filesets_permissions.store
	 * @link http://bible.build/bibles/filesets/AMKWBT/permissions - V4 Access [POST]
	 * @link https://dbp.dev/bibles/filesets/AMKWBT/permissions - V4 Test Access [POST]
	 *
	 * @param string $fileset_id
	 * @param Request $request
	 *
	 * @return mixed $permission string - A JSON string that contains the status code and error messages if applicable.
	 *
	 */
	public function update($fileset_id, Request $request)
	{
		$this->validateUser();
		$this->validateBibleFilesetPermission($request);

		$fileset = BibleFileset::find($fileset_id);
		$this->authorize('update', $fileset);
		$permission               = BibleFileSetPermission::find(request()->permission_id);
		$permission->access_level = request()->access_level;
		$permission->save();

		return $this->reply($permission);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$this->validateUser();

		$fileSet = BibleFileset::find($id);
		$fileSet->delete();

		return $this->reply(trans('api.filesets_delete_200', ['id' => $id]));
	}

	public function user()
	{
		$user = \Auth::user();
		if (!$user) {
			return $this->setStatusCode(401)->replyWithError(trans('api.auth_permission_denied'));
		}

		return view('bibles.filesets.permissions.user', compact('user'));
	}

	/**
	 * Ensure the current User has permissions to alter the permissions
	 *
	 * @param null $user
	 *
	 * @return \App\Models\User\User|mixed
	 */
	private function validateUser($user = null)
	{
		if (!$this->api) {
			$user = Auth::user();
		}
		if (!$user) {
			$key = Key::where('key', $this->key)->first();
			if (!isset($key)) {
				return $this->setStatusCode(403)->replyWithError(trans('api.auth_key_validation_failed'));
			}
			$user = $key->user;
		}
		if (!$user->archivist AND !$user->admin) {
			return $this->setStatusCode(401)->replyWithError(trans('api.bible_fileset_errors_401'));
		}

		return $user;
	}

	/**
	 * Ensure the current permission change is valid
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	private function validateBibleFilesetPermission(Request $request)
	{
		$validator = \Validator::make($request->all(), [
			'hash_id' => 'required|exists:filesets,hash_id',
			'key_id'  => 'required|exists:user_keys,key',
		]);

		if ($validator->fails()) {
			if ($this->api) {
				return $this->setStatusCode(422)->replyWithError($validator->errors());
			}
			if (!$this->api) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
		}

	}

}
