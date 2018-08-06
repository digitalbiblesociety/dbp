<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\Account;
use App\Models\User\ProjectMember;
use App\Models\User\User;
use Illuminate\Http\Request;


class UserAccountsController extends APIController
{
	/**
	 *
	 * @OA\Get(
	 *     path="/accounts",
	 *     tags={"Users"},
	 *     summary="List the Accounts",
	 *     description="",
	 *     operationId="v4_user_accounts.index",
	 *     @OA\Parameter(name="project_id", in="query", description="The Project ID", required=true,
	 *          @OA\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OA\Parameter(name="user_id", in="query", description="The User ID", required=true,
	 *          @OA\Schema(ref="#/components/schemas/User/properties/id")),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/Account")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/Account")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/Account"))
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
	 * @OA\Post(
	 *     path="/accounts",
	 *     tags={"Users"},
	 *     summary="Create a new Account",
	 *     description="Associate a social media account with a different user.",
	 *     operationId="v4_user_accounts.store",
	 *     @OA\Parameter(name="project_id", in="query", description="The Project ID", required=true,
	 *          @OA\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OA\Parameter(name="user_id", in="query", description="The User ID", required=true,
	 *          @OA\Schema(ref="#/components/schemas/User/properties/id")),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(required=true, description="Information supplied for user account creation",
	 *          @OA\MediaType(mediaType="application/json",
	 *              @OA\Schema(
	 *                  @OA\Property(property="provider_id", type="string"),
	 *                  @OA\Property(property="provider_user_id",type="string")
	 *              )
	 *          )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/Account")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/Account")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/Account"))
	 *     )
	 * )
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
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
	 * @OA\Get(
	 *     path="/accounts/{account_id}",
	 *     tags={"Users"},
	 *     summary="List the Accounts",
	 *     description="",
	 *     operationId="v4_user_accounts.show",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="project_id", in="query", description="The Project ID", required=true,
	 *          @OA\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OA\Parameter(name="user_id", in="query", description="The User ID", required=true,
	 *          @OA\Schema(ref="#/components/schemas/User/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/Account")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/Account")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/Account"))
	 *     )
	 * )
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($account_id)
	{
		$user = $this->verifyProjectUserConnection();

		return $this->reply($user->accounts->where('id', $account_id)->first());
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @OA\Put(
	 *     path="/accounts/{account_id}",
	 *     tags={"Users"},
	 *     summary="Update a specific Account",
	 *     description="",
	 *     operationId="v4_user_accounts.update",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="project_id", in="query", description="The Project ID", required=true,
	 *          @OA\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OA\Parameter(name="user_id", in="query", description="The User ID", required=true,
	 *          @OA\Schema(ref="#/components/schemas/User/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/Account")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/Account")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/Account"))
	 *     )
	 * )
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $account_id)
	{
		$user    = $this->verifyProjectUserConnection();
		$account = $user->accounts->where('id', $account_id)->first()->update($request->all());

		return $this->reply($account);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @OA\Delete(
	 *     path="/accounts/{account_id}",
	 *     tags={"Users"},
	 *     summary="Delete an account",
	 *     description="",
	 *     operationId="v4_user_accounts.delete",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Parameter(name="project_id", in="query", description="The Project ID", required=true,
	 *          @OA\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OA\Parameter(name="user_id", in="query", description="The User ID", required=true,
	 *          @OA\Schema(ref="#/components/schemas/User/properties/id")),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/Account")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/Account")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/Account"))
	 *     )
	 * )
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($account_id)
	{
		$user     = $this->verifyProjectUserConnection();
		$accounts = $user->accounts;
		$account  = $accounts->where('id', $account_id)->first();
		$account->delete();


		return $this->reply($accounts->where('id', '!=', $account_id)->all());
	}

	private function verifyProjectUserConnection()
	{
		$project_id = checkParam('project_id');
		$user_id    = checkParam('user_id');

		$project_member = ProjectMember::where('project_id', $project_id)->where('user_id', $user_id)->first();
		if (!$project_member) {
			return $this->setStatusCode(404)->replyWithError(trans('api.project_users_404'));
		}

		return $project_member->user;
	}
}
