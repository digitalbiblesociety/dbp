<?php

namespace App\Http\Controllers;

use App\Models\User\Key;
use App\Models\User\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Validator;
use Laravel\Socialite\Facades\Socialite;
class UsersController extends APIController
{

	use ResetsPasswords;

	/**
	 * Returns an index of all users within the system
	 *
	 * @OAS\Get(
	 *     path="/users",
	 *     tags={"Community"},
	 *     summary="",
	 *     description="",
	 *     operationId="v4_user.index",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_user_index"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
    {
		$authorized_user = checkUser();
	    if(!$authorized_user) return $this->setStatusCode(401)->replyWithError(trans('auth.not_logged_in'));
		if(!$this->api) return view('dashboard.users.index');

		$users = User::with('organizations.currentTranslation')->get();
		return $this->reply(fractal()->transformWith(UserTransformer::class)->collection($users));
    }

	/**
	 *
	 * @OAS\Get(
	 *     path="/users/{id}",
	 *     tags={"Community"},
	 *     summary="Returns a single user",
	 *     description="",
	 *     operationId="v4_user.show",
	 *     @OAS\Parameter(name="id", in="path", description="The user ID for which to retrieve info.", required=true, @OAS\Schema(ref="#/components/schemas/User/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_user_index"))
	 *     )
	 * )
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
    public function show($id)
    {
	    $authorized_user = checkUser();
	    if(!$authorized_user) return $this->setStatusCode(401)->replyWithError(trans('auth.not_logged_in'));
	    $user = User::with('organizations.currentTranslation')->where('id',$id)->first();
	    if(!$this->api) return view('dashboard.users.show', compact('user'));
	    return $this->reply(fractal()->transformWith(UserTransformer::class)->collection($user));
    }

	public function edit($id)
	{
		$authorized_user = checkUser();
		if(!$authorized_user) return $this->setStatusCode(401)->replyWithError(trans('auth.not_logged_in'));
		$user = User::with('organizations.currentTranslation')->where('id',$id)->first();
		return view('dashboard.users.edit', compact('user'));
	}

	public function create()
	{
		$authorized_user = checkUser();
		if(!$authorized_user) return $this->setStatusCode(401)->replyWithError(trans('auth.not_logged_in'));
		if(!$this->api) return view('dashboard.users.create');
	}

	/**
	 *
	 * @OAS\Post(
	 *     path="/users/login",
	 *     tags={"Community"},
	 *     summary="Login a user",
	 *     description="",
	 *     operationId="v4_user.login",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
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
	 */
    public function login(Request $request)
    {
    	$current_locale = $request->iso ?? \i18n::getCurrentLocale();
    	$user = User::with('accounts')->where('email',$request->email)->first();
	    if($user) if(Hash::check($request->password, $user->password)) return $this->reply($user);
    	return $this->replyWithError(trans('auth.failed',[],$current_locale));
    }

	/**
	 *
	 * @OAS\Post(
	 *     path="/users",
	 *     tags={"Community"},
	 *     summary="Create a new user",
	 *     description="",
	 *     operationId="v4_user.store",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
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
	 */
    public function store(Request $request)
    {
	    $user = checkUser();
	    if(!$user) return $this->setStatusCode(401)->replyWithError(trans('auth.not_logged_in'));
    	if(!$user->canCreateUsers()) return $this->setStatusCode(401)->replyWithError("You are not authorized to create users");

	    $validator = Validator::make($request->all(), [
		    'email' => 'required|unique:users,email|max:255',
		    'name'  => 'required'
	    ]);

	    if ($validator->fails()) return $this->replyWithError($validator->errors());
    	$user = User::create([
    		'id'       => unique_random('users','id',32),
    		'nickname' => $request->nickname,
		    'avatar'   => $request->avatar,
    		'email'    => $request->email,
		    'name'     => $request->name,
		    'password' => Hash::make($request->password)
	    ]);
	    return $this->reply(["success" => "User created","user" => $user]);
    }

	/**
	 *
	 * @OAS\Put(
	 *     path="/users/{id}",
	 *     tags={"Community"},
	 *     summary="Create a new user",
	 *     description="",
	 *     operationId="v4_user.store",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/reply"),
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
	 */
    public function update(Request $request, $id)
    {
	    $user = checkUser();
	    if(!$user) return $this->setStatusCode(401)->replyWithError(trans('auth.not_logged_in'));
	    if(!$user->canCreateUsers()) return $this->setStatusCode(401)->replyWithError("You are not authorized to create users");

	    $updated_user = User::find($id);
	    $validator = Validator::make($request->all(), [
	    	'id'    => 'exists:users',
		    'email' => 'max:255|email',
		    'avatar'=> 'image'
	    ]);

	    if ($validator->fails()) return $this->replyWithError($validator->errors());
	    // TODO: ENABLE WRITE PERMISSIONS ON S3 BUCKET
	    // if($request->hasFile('avatar')) return $request->avatar->storeAs('img/users/', $id.".".$request->avatar->extension(), 'local');
	    if($request->hasFile('avatar')) $request->avatar->storeAs('img', $id.".".$request->avatar->extension(), 'public');
	    $updated_user->fill($request->input())->save();

	    if($this->api) return $this->reply(["success" => "User updated","user_id" => $user->id]);
	    return view('dashboard.users.show', $id);
    }

    public function destroy(Request $request, $id)
    {
    	$user = User::where('id',$id)->where('project_id', $request->project_id)->first();
    }

}
