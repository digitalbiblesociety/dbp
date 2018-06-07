<?php

namespace App\Http\Controllers;

use App\Models\User\AccessGroup;
use Illuminate\Http\Request;

class AccessGroupController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	if(!$this->api) return view('access.groups.index');

        $access_groups = AccessGroup::all();
        return $this->reply(AccessGroup::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    ($this->api) ? $this->validateUser() : $this->validateUser(Auth::user());
	    $this->validateAccessGroup($request);

	    $access_group = AccessGroup::create($request->all());
	    if(!$this->api) return redirect()->route('access.groups.show', ['group_id' => $access_group->id]);
	    return $this->reply(["message" => "Access Group Successfully Created"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $access_group = AccessGroup::find($id);
	    return $this->reply($access_group);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
		$validator = Validator::make($request->all(),[
			'name'                => ($request->method() == "POST") ? 'required|unique:access_groups,name|max:64|alpha_dash' : 'required|exists:access_groups,name|max:64|alpha_dash',
			'description'         => 'nullable',
			'filesets.*.hash_id'  => 'exists:bible_filesets,hash_id',
			'keys.*.key_id'       => 'exists:user_keys,key',
			'types.*.type_id'     => 'exists:access_types,id'
		]);

		if ($validator->fails()) {
			if($this->api)  return $this->setStatusCode(422)->replyWithError($validator->errors());
			if(!$this->api) return redirect('access/groups/create')->withErrors($validator)->withInput();
		}

	}

}
