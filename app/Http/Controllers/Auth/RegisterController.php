<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
        	'id'       => str_random(24),
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


	public function store()
	{
		$rules = [
			'username' => 'required|min:6|unique:users',
			'email'    => 'required|email|unique:users',
			'password' => 'required|confirmed|min:6'
		];

		$input = Input::only('username','email','password','password_confirmation');

		$validator = Validator::make($input, $rules);

		if($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$confirmation_code = str_random(24);

		User::create([
			'id'                => str_random(24),
			'username'          => Input::get('username'),
			'email'             => Input::get('email'),
			'password'          => Hash::make(Input::get('password')),
			'confirmation_code' => $confirmation_code
		]);

		Mail::send('email.verify', $confirmation_code, function($message) {
			$message->to(Input::get('email'), Input::get('username'))->subject('Verify your email address');
		});

		Flash::message('Thanks for signing up! Please check your email.');

		return Redirect::home();
	}

}
