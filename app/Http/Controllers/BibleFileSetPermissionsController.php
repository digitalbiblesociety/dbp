<?php

namespace App\Http\Controllers;

use App\Models\Bible\BibleFileset;
use App\Models\User\User;
use App\Models\User\Access;

class BibleFileSetPermissionsController extends APIController
{

	/**
	 * Return an index of file set permissions
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
	{
		$user = Auth::user();
		if(!$user) return $this->setStatusCode(401)->replyWithError("you need to be logged in to see this page");
		$access = Access::where('user_id',$user->id)->get();
		return view('bibles.filesets.permissions.index',compact('access'));
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
	public function store()
	{
		BibleFileSetPermission::create(request()->all());
		return redirect()->route('view_bible_filesets.show', ['id' => request()->bible_fileset_id]);
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
