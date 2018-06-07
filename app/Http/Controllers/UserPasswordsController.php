<?php

namespace App\Http\Controllers;

use App\Models\User\Project;
use Illuminate\Http\Request;

use App\Models\User\User;
use App\Models\User\PasswordReset;
use App\Mail\EmailPasswordReset;

class UserPasswordsController extends APIController
{


	/**
	 *
	 * @OAS\Post(
	 *     path="/users/reset/email",
	 *     tags={"Users"},
	 *     summary="Trigger a reset email",
	 *     description="",
	 *     operationId="v4_user.reset",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\RequestBody(
	 *         required=true,
	 *         description="Information supplied for password reset",
	 *         @OAS\MediaType(mediaType="application/json",
	 *             @OAS\Schema (
	 *                required={"email","project_id"},
	 *                @OAS\Property(property="email",        ref="#/components/schemas/User/properties/email"),
	 *                @OAS\Property(property="project_id",   ref="#/components/schemas/Project/properties/id"),
	 *                @OAS\Property(property="iso",          ref="#/components/schemas/Language/properties/iso"),
	 *                @OAS\Property(property="password",     ref="#/components/schemas/User/properties/password"),
	 *                @OAS\Property(property="new_password", ref="#/components/schemas/User/properties/password")
	 *             ))
	 *     ),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_user_index"))
	 *     )
	 * )
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 *
	 */
	public function triggerPasswordResetEmail(Request $request)
	{
		$user = User::where('email', $request->email)->first();
		if (!$user) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_email',['email' => $request->email],$this->preferred_language));

		$project = $user->projects->where('id',$request->project_id)->first();
		if(!$project) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_401_project',['user' => $user->id, 'project' => $request->project_id],$this->preferred_language));

		$generatedToken = PasswordReset::create(['email' => $request->email,'token' => str_random(64),'reset_path' => $request->reset_path]);

		$user->token = $generatedToken->token;
		$project->reset_path = $request->reset_path;
		$project->iso = $request->iso ?? "eng";
		\Mail::to($user)->send(new EmailPasswordReset($user, $project));
		return $this->reply(trans('api.email_send_successful',[],$this->preferred_language));
	}

	/**
	 *
	 * @OAS\Post(
	 *     path="/users/reset/password",
	 *     tags={"Users"},
	 *     summary="Reset the password for a user",
	 *     description="This route handles resetting the password for a user that is a member of the project id provided.
	If the password is known to the your users you can reset their passwords without sending them a verification email by
	setting the optional fields `password` and `new_password` fields within the request.",
	 *     operationId="v4_user.reset",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\RequestBody(
	 *         required=true,
	 *         description="Information supplied for password reset",
	 *         @OAS\MediaType(mediaType="application/json",
	 *             @OAS\Schema (
	 *                required={"email","project_id","token_id","new_password","new_password_confirmed"},
	 *                @OAS\Property(property="email",                  ref="#/components/schemas/User/properties/email"),
	 *                @OAS\Property(property="project_id",             ref="#/components/schemas/Project/properties/id"),
	 *                @OAS\Property(property="token_id",               type="string",description="The token sent to the user's email"),
	 *                @OAS\Property(property="old_password",           ref="#/components/schemas/User/properties/password"),
	 *                @OAS\Property(property="new_password",           ref="#/components/schemas/User/properties/password"),
	 *                @OAS\Property(property="new_password_confirmed", ref="#/components/schemas/User/properties/password")
	 *             ))
	 *     ),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_user_index"))
	 *     )
	 * )
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 *
	 */
	public function validatePasswordReset(Request $request)
	{
		$project = Project::where('id',$request->project_id)->first();
		if(!$project) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_401_project',[],$this->preferred_language));

		if($request->email AND $request->old_password AND $request->new_password AND $request->new_password_confirmed) {
			$user = User::where('email',$request->email)->first();
			// If password provided, update password
			if(\Hash::check($request->old_password, $user->password)) {
				$user->password = bcrypt($request->new_password);
				$user->save();
				return $this->reply($user);
			}
		}

		$generatedToken = PasswordReset::where('token',$request->token_id)->first();
		if(!$generatedToken) return $this->setStatusCode(404)->replyWithError(trans('api.auth_password_reset_token_failed',[],$this->preferred_language));
		$user = $generatedToken->user;
		$generatedToken->delete();

		$user->password = bcrypt($request->new_password);
		$user->save();

		return $user;
	}

}
