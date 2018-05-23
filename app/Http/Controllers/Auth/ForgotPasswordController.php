<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\APIController;
use App\Mail\EmailPasswordReset;
use App\Models\User\PasswordReset;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Models\User\User;
use Illuminate\Support\Facades\Password;

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
		if(\Hash::check($request->password, $user->password)) {
			if(isset($request->new_password)) $user->password = bcrypt($request->new_password); $user->save();
			return $this->reply($user);
		}

		$generatedToken = PasswordReset::create([
			'email'        => $request->email,
			'token'        => str_random(64),
			'reset_path'   => $request->reset_path
		]);

		$user->token = $generatedToken->token;
		$project->reset_path = $generatedToken->reset_path;
		$project->iso = $request->iso ?? "eng";
		\Mail::to($user)->send(new EmailPasswordReset($user, $project));
		return $this->reply("Email sent successfully");
	}

}
