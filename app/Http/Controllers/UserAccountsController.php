<?php

namespace App\Http\Controllers;

use App\Models\User\Account;
use App\Models\User\ProjectMember;
use App\Models\User\User;
use Illuminate\Http\Request;


class UserAccountsController extends APIController
{
    /**
     *
     * @OAS\Get(
     *     path="/accounts",
     *     tags={"Community"},
     *     summary="List the Accounts",
     *     description="",
     *     operationId="v4_user_accounts.index",
     *     @OAS\Parameter(name="project_id", in="query", description="The Project ID", required=true, @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OAS\Parameter(name="user_id", in="query", description="The User ID", required=true, @OAS\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OAS\Parameter(ref="#/components/parameters/version_number"),
     *     @OAS\Parameter(ref="#/components/parameters/key"),
     *     @OAS\Parameter(ref="#/components/parameters/pretty"),
     *     @OAS\Parameter(ref="#/components/parameters/reply"),
     *     @OAS\Response(
     *         response=200,
     *         description="successful operation",
     *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/Account")),
     *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/Account")),
     *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/Account"))
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$user = $this->verifyProjectUserConnection();
	    return $this->reply($user->accounts);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @OAS\Post(
     *     path="/accounts",
     *     tags={"Community"},
     *     summary="Create a new Account",
     *     description="Associate a social media account with a different user.",
     *     operationId="v4_user_accounts.store",
     *     @OAS\Parameter(name="project_id", in="query", description="The Project ID", required=true, @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OAS\Parameter(name="user_id", in="query", description="The User ID", required=true, @OAS\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OAS\Parameter(ref="#/components/parameters/version_number"),
     *     @OAS\Parameter(ref="#/components/parameters/key"),
     *     @OAS\Parameter(ref="#/components/parameters/pretty"),
     *     @OAS\Parameter(ref="#/components/parameters/reply"),
     *     @OAS\RequestBody(required=true, description="Information supplied for user account creation", @OAS\MediaType(mediaType="application/json",
     *          @OAS\Schema(
     *              @OAS\Property(property="provider_id", type="string"),
     *              @OAS\Property(property="provider_user_id",type="string")
     *          )
     *     )),
     *     @OAS\Response(
     *         response=200,
     *         description="successful operation",
     *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/Account")),
     *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/Account")),
     *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/Account"))
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $user = $this->verifyProjectUserConnection();
	    return $this->reply($user->accounts()->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @OAS\Get(
     *     path="/accounts/{account_id}",
     *     tags={"Community"},
     *     summary="List the Accounts",
     *     description="",
     *     operationId="v4_user_accounts.show",
     *     @OAS\Parameter(ref="#/components/parameters/version_number"),
     *     @OAS\Parameter(ref="#/components/parameters/key"),
     *     @OAS\Parameter(ref="#/components/parameters/pretty"),
     *     @OAS\Parameter(ref="#/components/parameters/reply"),
     *     @OAS\Parameter(name="project_id", in="query", description="The Project ID", required=true, @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OAS\Parameter(name="user_id", in="query", description="The User ID", required=true, @OAS\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OAS\Response(
     *         response=200,
     *         description="successful operation",
     *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/Account")),
     *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/Account")),
     *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/Account"))
     *     )
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($account_id)
    {
	    $user = $this->verifyProjectUserConnection();
	    return $this->reply($user->accounts->where('id',$account_id)->first());
    }


    /**
     * Update the specified resource in storage.
     *
     * @OAS\Put(
     *     path="/accounts/{account_id}",
     *     tags={"Community"},
     *     summary="Update a specific Account",
     *     description="",
     *     operationId="v4_user_accounts.update",
     *     @OAS\Parameter(ref="#/components/parameters/version_number"),
     *     @OAS\Parameter(ref="#/components/parameters/key"),
     *     @OAS\Parameter(ref="#/components/parameters/pretty"),
     *     @OAS\Parameter(ref="#/components/parameters/reply"),
     *     @OAS\Parameter(name="project_id", in="query", description="The Project ID", required=true, @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OAS\Parameter(name="user_id", in="query", description="The User ID", required=true, @OAS\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OAS\Response(
     *         response=200,
     *         description="successful operation",
     *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/Account")),
     *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/Account")),
     *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/Account"))
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$account_id)
    {
	    $user = $this->verifyProjectUserConnection();
	    $account = $user->accounts->where('id',$account_id)->first()->update($request->all());
	    return $this->reply($account);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OAS\Delete(
     *     path="/accounts/{account_id}",
     *     tags={"Community"},
     *     summary="Delete an account",
     *     description="",
     *     operationId="v4_user_accounts.delete",
     *     @OAS\Parameter(ref="#/components/parameters/version_number"),
     *     @OAS\Parameter(ref="#/components/parameters/key"),
     *     @OAS\Parameter(ref="#/components/parameters/pretty"),
     *     @OAS\Parameter(ref="#/components/parameters/reply"),
     *     @OAS\Parameter(name="project_id", in="query", description="The Project ID", required=true, @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OAS\Parameter(name="user_id", in="query", description="The User ID", required=true, @OAS\Schema(ref="#/components/schemas/User/properties/id")),
     *     @OAS\Response(
     *         response=200,
     *         description="successful operation",
     *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/Account")),
     *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/Account")),
     *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/Account"))
     *     )
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($account_id)
    {
	    $user = $this->verifyProjectUserConnection();
	    $accounts = $user->accounts;
	    $account = $accounts->where('id',$account_id)->first();
	    $account->delete();


	    return $this->reply($accounts->where('id','!=',$account_id)->all());
    }

    private function verifyProjectUserConnection()
    {
	    $project_id = checkParam('project_id');
	    $user_id = checkParam('user_id');

	    $project_member = ProjectMember::where('project_id',$project_id)->where('user_id',$user_id)->first();
	    if(!$project_member) return $this->setStatusCode(404)->replyWithError("User/Project connection not found");
	    return $project_member->user;
    }
}
