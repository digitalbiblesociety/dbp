<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\Project;
use Illuminate\Http\Request;

use App\Models\User\User;
use App\Models\User\PasswordReset;
use App\Mail\EmailPasswordReset;

use Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UserPasswordsController extends APIController
{


	/**
	 *
	 * @OA\Post(
	 *     path="/users/reset/email",
	 *     tags={"Users"},
	 *     summary="Trigger a reset email",
	 *     description="",
	 *     operationId="v4_user.reset",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(
	 *         required=true,
	 *         description="Information supplied for password reset",
	 *         @OA\MediaType(mediaType="application/json",
	 *             @OA\Schema (
	 *                required={"email","project_id"},
	 *                @OA\Property(property="email",        ref="#/components/schemas/User/properties/email"),
	 *                @OA\Property(property="project_id",   ref="#/components/schemas/Project/properties/id"),
	 *                @OA\Property(property="iso",          ref="#/components/schemas/Language/properties/iso"),
	 *                @OA\Property(property="password",     ref="#/components/schemas/User/properties/password"),
	 *                @OA\Property(property="new_password", ref="#/components/schemas/User/properties/password")
	 *             ))
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_user_index"))
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
		$local = checkParam('local', null, 'optional');

		$user = User::where('email', $request->email)->first();
		if (!$user) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_email', ['email' => $request->email]));

		$project = $user->projects->where('id', $request->project_id)->first();
		if (!$project) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_401_project', ['user' => $user->id, 'project' => $request->project_id]));

		$generatedToken = PasswordReset::create(['email' => $request->email, 'token' => str_random(64), 'reset_path' => $request->reset_path]);

		$user->token         = $generatedToken->token;
		\Mail::to($user)->send(new EmailPasswordReset($user, $project));
		if($local) return view('auth.verification-required');
		return $this->reply(trans('api.email_send_successful', []));
	}

	/**
	 *
	 * @OA\Post(
	 *     path="/users/reset/password",
	 *     tags={"Users"},
	 *     summary="Reset the password for a user",
	 *     description="This route handles resetting the password for a user that is a member of the project id provided.
	If the password is known to the your users you can reset their passwords without sending them a verification email by
	setting the optional fields `password` and `new_password` fields within the request.",
	 *     operationId="v4_user.reset",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(
	 *         required=true,
	 *         description="Information supplied for password reset",
	 *         @OA\MediaType(mediaType="application/json",
	 *             @OA\Schema (
	 *                required={"email","project_id","token_id","new_password","new_password_confirmed"},
	 *                @OA\Property(property="email",                  ref="#/components/schemas/User/properties/email"),
	 *                @OA\Property(property="project_id",             ref="#/components/schemas/Project/properties/id"),
	 *                @OA\Property(property="token_id",               type="string",description="The token sent to the user's email"),
	 *                @OA\Property(property="old_password",           ref="#/components/schemas/User/properties/password"),
	 *                @OA\Property(property="new_password",           ref="#/components/schemas/User/properties/password"),
	 *                @OA\Property(property="new_password_confirmed", ref="#/components/schemas/User/properties/password")
	 *             ))
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_user_index"))
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
		$validated = $this->validatePassword($request);
		if($validated != "valid") return $validated;
		$local = checkParam('local', null, 'optional');
		$user = User::where('email', $request->email)->first();
		$user->password = (\Hash::needsRehash($request->new_password)) ? \Hash::make($request->new_password) : $request->new_password;
		$user->save();
		if($local) {
			\Auth::login($user);
			return redirect(env('APP_URL').'/dashboard');
		}
		return $this->reply($user);
	}


	/**
	 * Ensure the current alphabet change is valid
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	private function validatePassword(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'password'         => 'confirmed|required',
			'email'            => 'required|exists:users,email',
			'project_id'       => 'required|exists:projects,id',
			'token_id'         => ['required',
				Rule::exists('password_resets', 'token')
				    ->where(function ($query) use ($request) {
					    $query->where('email', $request->email);
				})]
		]);

		if ($validator->fails()) {
			if($this->api AND !isset($_GET['local'])) return $this->setStatusCode(422)->replyWithError($validator->errors());
			return redirect()->back()->with(['errors' =>$validator->errors()->toArray()])->withInput();
		}
		return "valid";
	}

}
