<?php

namespace App\Http\Controllers;

use App\Models\User\Key;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Validator;

class UsersController extends APIController
{
	/**
	 *
	 */
	public function index()
    {
		return view('users');
    }

    public function store(Request $request)
    {

    	$user_authorized = Key::where('key',$_GET['key'])->first()->user;
    	if(!$user_authorized) return $this->setStatusCode(401)->replyWithError("You are not a user");
    	if(!$user_authorized->canCreateUsers()) return $this->setStatusCode(401)->replyWithError("You are not authorized to create users");

	    $validator = Validator::make($request->all(), [
		    'email' => 'required|unique:users,email|max:255',
		    'name'  => 'required'
	    ]);


	    if ($validator->fails()) return $this->replyWithError($validator->errors());

    	User::create([
    		'id'    => unique_random('users','id',32),
    		'email' => $request->email,
		    'name'  => $request->name,
		    'password' => Hash::make($request->password)
	    ]);
    }

}
