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
use Carbon\Carbon;

class UserPasswordsController extends APIController
{

	public function showResetForm(Request $request, $token = null)
	{
		$reset_request = PasswordReset::where('token',$token)->first();
		if(!$reset_request) return $this->replyWithError("No matching Token found");
		return view('auth.passwords.reset', compact('reset_request'));
	}

	public function showRequestForm()
	{
		$project = Project::where('name','Digital Bible Platform')->first();
		return view('auth.passwords.email', compact('project'));
	}

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

		$generatedToken = PasswordReset::create(['email' => $request->email, 'token' => str_random(64),'created_at' => Carbon::now()]);
		$user = User::where('email', $request->email)->first();
		$user->token = $generatedToken->token;
		if(!$user) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_email', ['email' => $request->email]));

		$project = Project::where('id',$request->project_id)->first();
		if (!$project) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_401_project', ['user' => $user->id, 'project' => $request->project_id]));

		\Mail::to($user)->send(new EmailPasswordReset($user, $project));
		if(!$this->api) return view('auth.verification-required');
		return $this->reply(trans('api.email_send_successful'));
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
	 * @return mixed
	 *
	 */
	public function validatePasswordReset(Request $request)
	{
		$validated = $this->validatePassword($request);
		if($validated !== 'valid') return $validated;

		$user = User::where('email', $request->email)->first();
		if(!$user) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_email',['email' => $request->email],$GLOBALS['i18n_iso']));

		$user->password = \Hash::needsRehash($request->new_password) ? \Hash::make($request->new_password) : $request->new_password;
		$user->save();

		if($this->api) return $this->reply($user);

		\Auth::login($user);
		return redirect(env('APP_URL').'/home');
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
			'new_password'     => 'confirmed|required|min:8',
			'email'            => 'required|email',
			'project_id'       => 'exists:dbp_users.projects,id',
			'token_id'         => ['required',
				Rule::exists('password_resets', 'token')
				    ->where(function ($query) use ($request) {
					    $query->where('email', $request->email);
				})]
		]);

		if ($validator->fails()) {
			if($this->api AND !isset($_GET['local'])) return $this->setStatusCode(422)->replyWithError($validator->errors());
			return redirect()->back()->with(['errors' => $validator->errors()])->withInput();
		}
		return "valid";
	}

}
