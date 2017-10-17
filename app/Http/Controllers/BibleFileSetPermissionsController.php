<?php

namespace App\Http\Controllers;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFileSetPermission;
use App\Models\User\User;
use App\Transformers\BibleFilePermissionsTransformer;
use Illuminate\Http\Request;

class BibleFileSetPermissionsController extends APIController
{
	/**
	 * Return an index of file set permissions
	 *
	 * @return View|JSON
	 */
	public function index()
	{
		if(!$this->api) return view('bibles.filesets.permissions.index');
		$bibleFileSetPermissions = BibleFileSetPermission::all();
		return $this->reply(fractal()->collection($bibleFileSetPermissions)->transformWith(BibleFilePermissionsTransformer::class)->serializeWith($this->serializer));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return View
	 */
	public function create(string $id)
	{
		$users = User::all()->pluck('name','id');
		$fileset = BibleFileset::find($id);
		return view('bibles.filesets.permissions.create', compact('fileset','users'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$permission = new BibleFileSetPermission();
		$permission->user_id = $request->user_id;
		$permission->access_level = $request->access_level;
		$permission->access_notes = $request->access_notes;
		$permission->save();

		return view('bibles.filesets.permissions.show',compact('permission'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
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
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$bibleFileSet = BibleFileset::find($id);
		$bibleFileSet->delete();
	}
}
