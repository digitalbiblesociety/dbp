<?php

namespace App\Http\Controllers;

use App\Models\User\ProjectOauthProvider;
use Illuminate\Http\Request;
use Validator;

class ProjectOAuthProvidersController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$project_id = checkParam('project_id');
        $providers = ProjectOauthProvider::where('project_id', $project_id)->get();

        return $this->reply($providers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateOAuthProvider($request);
        $provider = ProjectOauthProvider::create(array_add($request->all(),'id', 'generated'));

        return $this->reply($provider);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $project_id = checkParam('project_id');
	    $provider = ProjectOauthProvider::where('project_id', $project_id)->where('id',$id)->first();

	    return $this->reply($provider);
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
	    $project_id = checkParam('project_id');
	    $provider = ProjectOauthProvider::where('project_id', $project_id)->where('id',$id)->first();
	    $provider->fill($request->all())->save();

	    return $this->reply($provider);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $project_id = checkParam('project_id');
	    $provider = ProjectOauthProvider::where('project_id', $project_id)->where('id',$id)->first();
	    $provider->delete();

	    return $this->reply("Successfully deleted");
    }

	/**
	 * Ensure the current oAuth provider change is valid
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	private function validateOAuthProvider(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'project_id'     => 'required|unique:projects,id',
			'name'           => 'string|max:191|required|in:facebook,twitter,linkedin,google,github,bitbucket',
			'client_id'      => 'string|required',
			'client_secret'  => 'string|required',
			'callback_url'   => 'string|max:191|required'
		]);

		if ($validator->fails()) {
			if($this->api)  return $this->setStatusCode(422)->replyWithError($validator->errors());
			if(!$this->api) return redirect('dashboard/projects/oauth-providers/create')->withErrors($validator)->withInput();
		}

	}

}
