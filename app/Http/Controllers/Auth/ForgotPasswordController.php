<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\APIController;
use App\Mail\EmailPasswordReset;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Models\User\User;

class ForgotPasswordController extends APIController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
	    parent::__construct($request);
        $this->middleware('guest');
    }

	/**
	 * Send a reset link to the given user
	 *
	 * @OAS\Post(
	 *     path="/users/reset",
	 *     tags={"Community"},
	 *     summary="Reset the password for a user",
	 *     description="This route handles resetting the password for a user that is a member of the project id provided.
				If the password is known to the user you can reset their password without sending a verification email by
				setting the optional fields `password` and `new_password` fields within the request. If the both optional
				fields `password` and `new_password` are unset. This route will send the user a verification reset email.",
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
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function getResetToken(Request $request)
	{
		$user = User::where('email', $request->email)->first();
		if (!$user) return $this->setStatusCode(404)->replyWithError("The user could not be found");

		$project = $user->projects->where('id',$request->project_id)->first();
		if(!$project) return $this->setStatusCode(404)->replyWithError("The user given is not a user of the project_id provided.");

		// If password provided, update password
		if(Hash::check($request->password, $user->password)) {
			if(isset($request->new_password)) $user->password = bcrypt($request->new_password); $user->save();
			return $this->reply($user);
		}

		$user->token = $this->broker()->createToken($user);
		$project->iso = $request->iso ?? "eng";
		\Mail::to($user)->send(new EmailPasswordReset($user, $project));
		return $this->reply("Email sent successfully");
	}

}
