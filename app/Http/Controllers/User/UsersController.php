<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Models\User\Account;
use App\Models\User\Key;
use App\Models\User\ProjectMember;
use App\Models\User\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UsersController extends APIController
{

	use AuthenticatesUsers;

	protected $redirectAfterLogout = '/';

	public function __construct(Request $request)
	{
		parent::__construct($request);
		$this->user = (isset($_GET['key'])) ? \App\Models\User\Key::where('key',$_GET['key'])->first()->user : \Auth::user();
		if(isset($this->user)) {
			$this->project_limited = ($this->user->admin or $this->user->archivist) ? false : true;
		} else {
			$this->project_limited = true;
		}
	}

	/**
	 * Returns an index of all users within the system
	 *
	 * @OA\Get(
	 *     path="/users",
	 *     tags={"Users"},
	 *     summary="",
	 *     description="",
	 *     operationId="v4_user.index",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_user_index"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
	{
		if(!$this->api) return view('dashboard.users.index');

		$users = User::with('organizations.currentTranslation')->when($this->project_limited, function ($q) {
			$q->whereHas('projects', function ($query) {
				$query->whereIn('project_members.project_id', $this->user->developer->pluck('id'));
			});
		})->get();

		return $this->reply(fractal($users,UserTransformer::class));
	}

	/**
	 *
	 * @OA\Get(
	 *     path="/users/{id}",
	 *     tags={"Users"},
	 *     summary="Returns a single user",
	 *     description="",
	 *     operationId="v4_user.show",
	 *     @OA\Parameter(name="id", in="path", description="The user ID for which to retrieve info.", required=true, @OA\Schema(ref="#/components/schemas/User/properties/id")),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_user_index")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_user_index"))
	 *     )
	 * )
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function show($id)
	{
		$unauthorized_user = $this->unauthorizedToAlterUsers();
		if($unauthorized_user) return $unauthorized_user;

		$user = User::with('organizations.currentTranslation')->when($this->project_limited, function ($q) {
			$q->whereHas('projects', function ($query) {
				$query->whereIn('project_members.project_id', $this->user->developer->pluck('id'));
			});
		})->where('id', $id)->first();
		if(!$user) return $this->replyWithError("user not found");

		if(!$this->api) return view('dashboard.users.show', compact('user'));
		return $this->reply(fractal($user, UserTransformer::class));
	}

	public function edit($id)
	{
		$authorized_user = $this->unauthorizedToAlterUsers();
		if(!$authorized_user) return $this->setStatusCode(401)->replyWithError(trans('auth.not_logged_in'));
		$user = User::with('organizations.currentTranslation')->where('id', $id)->first();

		return view('dashboard.users.edit', compact('user'));
	}

	public function create()
	{
		return view('auth.register');
	}

	/**
	 *
	 * @OA\Post(
	 *     path="/users/login",
	 *     tags={"Users"},
	 *     summary="Login a user",
	 *     description="",
	 *     operationId="v4_user.login",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(required=true, description="Either the `email` & `password` or the `social_provider_user_id` & `social_provider_id` are required for user Login", @OA\MediaType(mediaType="application/json",
	 *          @OA\Schema(
	 *              @OA\Property(property="email",                     ref="#/components/schemas/User/properties/email"),
	 *              @OA\Property(property="password",                  ref="#/components/schemas/User/properties/password"),
	 *              @OA\Property(property="social_provider_user_id",   ref="#/components/schemas/Account/properties/provider_user_id"),
	 *              @OA\Property(property="social_provider_id",        ref="#/components/schemas/Account/properties/provider_id"),
	 *          )
	 *     )),
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
	 */
	public function login(Request $request)
	{

		if (isset($request->social_provider_id)) {
			$account = Account::where('provider_user_id', $request->social_provider_user_id)->where('provider_id', $request->social_provider_id)->first();
			if ($account) return $this->reply($account->user);
		}
		$user = User::with('accounts')->where('email', $request->email)->first();
		if(!$user) {
			if($this->api) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_email'));
			return redirect()->back()->withErrors(['errors' => 'No user found for the email provided']);
		}

		if($user->password == "needs_resetting") return $this->setStatusCode(428)->replyWithError(trans('api.users_errors_428_password'));

		if (Hash::check($request->password, $user->password)) {
			Auth::guard()->login($user, true);
			Auth::guard('administrator')->login($user, true);
			Auth::guard('user')->login($user, true);
			if($this->api) return $this->reply($user);
			//return redirect()->route('public.home');
			return view('dashboard.home');
		}

		$this->incrementLoginAttempts($request);
		if($this->api) return $this->setStatusCode(401)->replyWithError(trans('auth.failed', [], $GLOBALS['i18n_iso']));
		return $this->sendFailedLoginResponse($request);
	}

	public function authenticated()
	{
		if(auth()->user()->admin)
		{
			return redirect('/admin/dashboard');
		}

		return redirect('/user/dashboard');
	}

	public function showLoginForm()
	{
		return view('auth.login');
	}

	/**
	 *
	 * @OA\Post(
	 *     path="/users",
	 *     tags={"Users"},
	 *     summary="Create a new user",
	 *     description="",
	 *     operationId="v4_user.store",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(required=true, description="Information supplied for user creation", @OA\MediaType(mediaType="application/json",
	 *          @OA\Schema(
	 *              @OA\Property(property="nickname",                ref="#/components/schemas/User/properties/nickname"),
	 *              @OA\Property(property="avatar",                  ref="#/components/schemas/User/properties/avatar"),
	 *              @OA\Property(property="email",                   ref="#/components/schemas/User/properties/email"),
	 *              @OA\Property(property="name",                    ref="#/components/schemas/User/properties/name"),
	 *              @OA\Property(property="password",                ref="#/components/schemas/User/properties/password"),
	 *              @OA\Property(property="project_id",              ref="#/components/schemas/ProjectMember/properties/project_id"),
	 *              @OA\Property(property="user_role",               ref="#/components/schemas/ProjectMember/properties/role"),
	 *              @OA\Property(property="subscribed",              ref="#/components/schemas/ProjectMember/properties/subscribed"),
	 *              @OA\Property(property="social_provider_id",      ref="#/components/schemas/Account/properties/provider_id"),
	 *              @OA\Property(property="social_provider_user_id", ref="#/components/schemas/Account/properties/provider_user_id"),
	 *          )
	 *     )),
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
	 */
	public function store(Request $request)
	{
		$unauthorized_user = $this->unauthorizedToAlterUsers();
		if($unauthorized_user) return $unauthorized_user;

		$validator = Validator::make($request->all(), [
			'email'                   => 'required|unique:users,email|max:255|email',
			'name'                    => 'required|string',
			'nickname'                => 'string|different:name',
			'project_id'              => 'required|exists:projects,id',
			'social_provider_id'      => 'required_with:social_provider_user_id',
			'social_provider_user_id' => 'required_with:social_provider_id',
		]);

		if ($validator->fails()) return $this->replyWithError($validator->errors());
		$user = User::create([
			'id'       => unique_random('users', 'id', 32),
			'nickname' => $request->nickname,
			'avatar'   => $request->avatar,
			'email'    => $request->email,
			'name'     => $request->name,
			'token'    => unique_random('dbp_users.users','token'),
			'notes'    => $request->notes,
			'password' => Hash::make($request->password),
		]);
		if ($request->project_id) {
			$user->projectMembers()->create([
				'project_id' => $request->project_id,
				'role'       => ($request->user_role) ? $request->user_role : 'user',
				'subscribed' => $request->subscribed ?? 0,
			]);
		}
		if ($request->social_provider_id) {
			$user->accounts()->create([
				'provider_id'      => $request->social_provider_id,
				'provider_user_id' => $request->social_provider_user_id,
			]);
		}

		return $this->reply(["success" => "User created", "user" => $user]);
	}

	/**
	 *
	 * @OA\Put(
	 *     path="/users/{id}",
	 *     tags={"Users"},
	 *     summary="Create a new user",
	 *     description="",
	 *     operationId="v4_user.store",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\RequestBody(required=true, description="Information supplied for updating an existing user", @OA\MediaType(mediaType="application/json",
	 *          @OA\Schema(
	 *              @OA\Property(property="nickname",                ref="#/components/schemas/User/properties/nickname"),
	 *              @OA\Property(property="avatar",                  ref="#/components/schemas/User/properties/avatar"),
	 *              @OA\Property(property="email",                   ref="#/components/schemas/User/properties/email"),
	 *              @OA\Property(property="name",                    ref="#/components/schemas/User/properties/name"),
	 *              @OA\Property(property="password",                ref="#/components/schemas/User/properties/password"),
	 *              @OA\Property(property="project_id",              ref="#/components/schemas/ProjectMember/properties/project_id"),
	 *              @OA\Property(property="user_role",               ref="#/components/schemas/ProjectMember/properties/role"),
	 *              @OA\Property(property="subscribed",              ref="#/components/schemas/ProjectMember/properties/subscribed"),
	 *              @OA\Property(property="social_provider_id",      ref="#/components/schemas/Account/properties/provider_id"),
	 *              @OA\Property(property="social_provider_user_id", ref="#/components/schemas/Account/properties/provider_user_id"),
	 *          )
	 *     )),
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
	 */
	public function update(Request $request, $id)
	{
		// Validate Request
		$invalidRequest = $this->validateUserAlterationRequest($request);
		if($invalidRequest) return $invalidRequest;

		// Validate User
		$unauthorized_user = $this->unauthorizedToAlterUsers();
		if($unauthorized_user) return $unauthorized_user;

		// Retrieve User
		$user = User::where('id', $id)->when($this->project_limited, function ($q) {
			$q->whereHas('projects', function ($query) {
				$query->whereIn('project_members.project_id', $this->user->developer->pluck('id'));
			});
		})->first();
		if(!$user) return $this->setStatusCode(404)->replyWithError("User not found");

		// Fetch Data
		$input = $request->all();

		// Process Avatar
		if($request->hasFile('avatar')) {
			//$input['avatar'] = $id.".".$request->file('avatar')->extension();
			//dd($request->file('avatar'));
			$image = Image::make($request->file('avatar'));
			if(isset($request->avatar_crop_width) AND isset($request->avatar_crop_height)) {
				$image->crop($request->avatar_crop_width, $request->avatar_crop_height, $request->avatar_crop_inital_x_coordinate, $request->avatar_crop_inital_y_coordinate);
			}
			$image->resize(300, 300);
			\Storage::disk('public')->put($id.'.'.$request->avatar->extension(), $image->save());
		}
		$input['avatar'] = \URL::to('/storage/'.$id.'.'.$request->avatar->extension());
		$user->fill($input)->save();
		// $user->project_role         = $user->projects->first()->pivot->role;
		// $user->project_subscription = $user->projects->first()->pivot->subscribed;
		// unset($user->projects);

		// Return Updated Model
		if($this->api) return $this->reply(["success" => "User updated", "user" => $user]);
		return view('dashboard.users.show', $id);
	}

	public function destroy($id)
	{
		$project_id = checkParam('project_id');

		$connection = ProjectMember::where('user_id', $id)->where('project_id', $project_id)->first();
		if(!$connection) return $this->setStatusCode(404)->replyWithError("User/Project connection not found");
		$connection->delete();
		return $this->reply("User Project connection successfully removed");
	}


	/**
	 *
	 * @OA\Get(
	 *     path="/users/geolocate",
	 *     tags={"Users"},
	 *     summary="Geolocate a user by their Ip address",
	 *     description="",
	 *     operationId="v4_user.geolocate",
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json", @OA\Schema(type="object")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(type="object")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(type="object"))
	 *     )
	 * )
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function geoLocate()
	{
		$ip_address  = checkParam('ip_address');
		$geolocation = geoip($ip_address);

		return $this->reply([
			"ip"          => $geolocation->getAttribute("ip"),
			"iso_code"    => $geolocation->getAttribute("iso_code"),
			"country"     => $geolocation->getAttribute("country"),
			"city"        => $geolocation->getAttribute("city"),
			"state"       => $geolocation->getAttribute("state"),
			"state_name"  => $geolocation->getAttribute("state_name"),
			"postal_code" => $geolocation->getAttribute("postal_code"),
			"lat"         => $geolocation->getAttribute("lat"),
			"lon"         => $geolocation->getAttribute("lon"),
			"timezone"    => $geolocation->getAttribute("timezone"),
			"continent"   => $geolocation->getAttribute("continent"),
		]);
	}

	private function validateUserAlterationRequest($request)
	{
		$validator = Validator::make($request->all(), [
			'id'              => 'exists:users,id',
			'email'           => 'max:191|email',
			'name'            => 'string|max:191',
			'nickname'        => 'string|max:191',
			'remember_token'  => 'max:100',
			'verified'        => 'boolean',
			'avatar'          => 'image',
		]);

		if ($validator->fails()) return $this->replyWithError($validator->errors());
		return false;
	}

	private function unauthorizedToAlterUsers()
	{
		if(!isset($this->user)) return $this->setStatusCode(401)->replyWithError(trans('api.auth_key_validation_failed'));
		if(!isset($this->user->canAlterUsers) AND !isset($this->user->developer)) return $this->setStatusCode(401)->replyWithError(trans('api.auth_user_validation_failed'));
		return false;
	}

}
