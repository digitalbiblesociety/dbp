<?php

namespace App\Http\Controllers;

use App\Models\Bible\BibleFileset;
use App\Models\User\User;
use App\Models\User\Access;
use Illuminate\Http\Request;

class BibleFileSetPermissionsController extends APIController
{

	/**
	 * Return an index of file set permissions
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index($fileset_id)
	{
		$user = \Auth::user();
		if(!$user) return $this->setStatusCode(401)->replyWithError("you need to be logged in to see this page");
		$fileset = BibleFileset::with('permissions')->where('id',$fileset_id)->first();
		return view('bibles.filesets.permissions.index',compact('fileset'));
	}


	/**
	 *
	 * Show the form for creating a new resource.
	 *
	 * @param string $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create(string $id)
	{
		$users = User::all()->pluck('name','id');
		$fileset = BibleFileset::find($id);
		return view('bibles.filesets.permissions.create', compact('fileset','users'));
	}


	/**
	 *
	 * Store a newly created resource in storage.
	 *
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function store(string $fileset_id,Request $request)
	{
		$validator = \Validator::make($request->all(), [
			'hash_id'          => 'required|exists:filesets,hash_id',
			'key_id'           => 'required|exists:user_keys,key',
		]);
		if ($validator->fails()) return ['errors' => $validator->errors() ];

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
	 * Display the specified resource.
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function show($id)
	{
		$permission = BibleFileset::find($id);
		return view('bibles.filesets.permissions.show', compact('permission'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$bibleFileSet = BibleFileset::find($id);
		return view('bibles.filesets.permissions.edit',compact('bibleFileSet'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($id)
	{
		$fileset = BibleFileset::find($id);
		$this->authorize('update', $fileset);
		$permission = BibleFileSetPermission::find(request()->permission_id);
		$permission->access_level = request()->access_level;
		$permission->save();
		return redirect()->route('view_bible_filesets.show', $id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$fileSet = BibleFileset::find($id);
		$fileSet->delete();

		return redirect()->route('view_bible_filesets.index');
	}

	public function user()
	{
		$user = \Auth::user();
		if(!$user) return $this->setStatusCode(401)->replyWithError("you need to be logged in to see this page");
		return view('bibles.filesets.permissions.user',compact('user'));
	}

}
