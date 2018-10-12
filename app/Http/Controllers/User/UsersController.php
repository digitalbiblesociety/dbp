<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Mail\ProjectVerificationEmail;
use App\Models\User\Project;
use App\Models\User\ProjectOauthProvider;
use App\Models\User\ProjectMember;
use App\Models\User\Account;
use App\Models\User\User;
use App\Models\User\Key;


use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UsersController extends APIController
{

	use AuthenticatesUsers;

	protected $redirectAfterLogout = '/';

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

		$users = \DB::table('users')
			->join('project_members','project_members.user_id','users.id')
			->select(['users.id','users.name','users.email'])->get();

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
		if(!$user) return $this->replyWithError('user not found');

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
		return "didn't redirect";
		if (isset($request->social_provider_id)) {
			$account = Account::where('provider_user_id', $request->social_provider_user_id)->where('provider_id', $request->social_provider_id)->first();
			if ($account) return $this->reply($account->user);
		}
		$user = User::with('accounts')->where('email', $request->email)->first();
		if(!$user) {
			if($this->api) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_email'));
			return redirect()->back()->withErrors(['errors' => 'No user found for the email provided']);
		}

		$loginSuccessful =  $this->guard()->attempt(['email' =>$user->email, 'password' => md5($request->password)], $request->filled('remember'));
		if(!$loginSuccessful) $loginSuccessful = $this->guard()->attempt(['email' =>$user->email, 'password' => $request->password], $request->filled('remember'));

		if ($loginSuccessful) {
			if($this->api) return $this->reply($user);
			return $this->sendLoginResponse($request);
		}

		$this->incrementLoginAttempts($request);
		if($this->api) return $this->setStatusCode(401)->replyWithError(trans('auth.failed', [], $GLOBALS['i18n_iso']));
		return $this->sendFailedLoginResponse($request);
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

		$invalid = $this->validateUser();
		if($invalid) return $invalid;

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
		if((int) $request->v === 1) {
			return redirect('http://api.dbp.test/login?reply=json');
		}
		// Validate Request
		$invalidRequest = $this->validateUser();
		if($invalidRequest) return $invalidRequest;

		// Validate User
		$unauthorized_user = $this->unauthorizedToAlterUsers();
		if($unauthorized_user) return $unauthorized_user;

		// Retrieve User
		$user = User::with('projects')->where('email', request()->email)->first();
		if(!$user) return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_email', ['email' => request()->email], $GLOBALS['i18n_iso']));

		// If the request does not originate from an admin
		if($this->project_limited) {
			$user_projects = $user->projects->pluck('id');
			$developer_projects = $this->user->developer->pluck('id');
			if(!$developer_projects->contains(request()->project_id)) return $this->setStatusCode(401)->replyWithError(trans('api.projects_developer_not_a_member', [], $GLOBALS['i18n_iso']));

			if($developer_projects->intersect($user_projects)->count() == 0) {
				$project = Project::where('id',request()->project_id)->first();
				$connection = $user->projectMembers()->create([
					'user_id'       => $user->id,
					'project_id'    => $project->id,
					'role'          => 'user',
					'token'         => unique_random(env('DBP_USERS_DATABASE').'.project_members','token'),
					'subscribed'    => false
				]);
				Mail::to($user->email)->send(new ProjectVerificationEmail($connection,$project));
				return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_needs_to_connect', [], $GLOBALS['i18n_iso']));
			}
		}

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
			$input['avatar'] = \URL::to('/storage/'.$id.'.'.$request->avatar->extension());
		}

		$user->fill($input)->save();
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


	/**
	 *
	 * @OAS\Get(
	 *     path="/users/login/{driver}",
	 *     tags={"Users"},
	 *     summary="Add a new oAuth provider to a project",
	 *     description="",
	 *     operationId="v4_projects_oAuthProvider.store",
	 *     @OAS\Parameter(name="driver", in="path", required=true, description="The Provider name, the currently supported providers are: facebook, bitbucket, github, twitter, & google", @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/name")),
	 *     @OAS\Parameter(name="project_id", in="query", required=true, description="The Project id", @OAS\Schema(ref="#/components/schemas/Project/properties/id")),
	 *     @OAS\Parameter(name="alt_url", in="query", description="When this parameter is set, the return will use the alternative callback url", @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/callback_url_alt")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(type="string")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(type="string")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(type="string"))
	 *     )
	 * )
	 *
	 * @param $provider
	 *
	 * @return mixed
	 *
	 */
	public function redirectToProvider($provider = null)
	{
		if ($this->api) {
			if ($provider == "twitter") return $this->setStatusCode(422)->replyWithError(trans('api.auth_errors_twitter_stateless'));
			$project_id = checkParam('project_id');
			$provider   = checkParam('name', $provider);
			$alt_url    = checkParam('alt_url', null, 'optional');
			if($provider == "twitter") return $this->setStatusCode(422)->replyWithError(trans('api.auth_errors_twitter_stateless'));
			$driverData = ProjectOauthProvider::where('project_id', $project_id)->where('name', $provider)->first();
			$driver     = [
				'client_id'     => $driverData->client_id,
				'client_secret' => $driverData->client_secret,
				'redirect'      => (!isset($alt_url)) ? $driverData->callback_url : $driverData->callback_url_alt,
			];
			switch ($provider) {
				case "bitbucket": {$providerClass = BitbucketProvider::class; break; }
				case "facebook":  {$providerClass = FacebookProvider::class; break; }
				case "twitter":   {$providerClass = TwitterProvider::class; break; }
				case "github":    {$providerClass = GithubProvider::class; break; }
				case "google":    {$providerClass = GoogleProvider::class; break; }
			}
			return $this->reply(Socialite::buildProvider($providerClass, $driver)->stateless()->redirect()->getTargetUrl());
		}
		return Socialite::driver($provider)->redirect();
	}

	public function handleProviderCallback($provider = null)
	{
		if(!$provider) $provider = request()->remote_type;
		$user = \Socialite::driver($provider)->stateless()->user();
		$user = $this->createOrGetUser($user, $provider);
		\Auth::login($user);
		$this->guard()->login($user);
		if ($this->api) return $user;
		if ($user->admin) return redirect()->route('admin');
		return view('home',compact('user'));
	}

	public function createOrGetUser($providerUser, $provider)
	{
		$account = Account::where('provider_id', $provider)->where('provider_user_id', $providerUser->getId())->first();
		if (!$account) {
			$account = new Account(['provider_user_id' => $providerUser->getId(), 'provider_id' => $provider]);
			$user    = User::where('email', $providerUser->getEmail())->first();
			if (!$user) {
				$user = User::create([
					'id'       => str_random(24),
					'nickname' => $providerUser->getNickname(),
					'email'    => $providerUser->getEmail(),
					'name'     => $providerUser->getName(),
					'verified' => 1,
				]);
			}
			$account->user()->associate($user);
			$account->save();
			return $user;
		}
		return $account->user;
	}

	public function verify($token)
	{
		$user           = User::where('email_token', $token)->first();
		$user->verified = 1;
		$user->save();
		\Auth::login($user);
		$this->guard()->login($user);
		return redirect()->route('home');
	}

	public function authenticated()
	{
		if(auth()->user()->admin) return redirect('/admin/dashboard');
		return redirect('/user/dashboard');
	}

	/**
	 * @return bool
	 */
	private function unauthorizedToAlterUsers()
	{
		if(!isset($this->user)) return $this->setStatusCode(401)->replyWithError(trans('api.auth_key_validation_failed'));
		if(!isset($this->user->canAlterUsers) AND !isset($this->user->developer)) return $this->setStatusCode(401)->replyWithError(trans('api.auth_user_validation_failed'));
		return false;
	}

	private function project_limited()
	{
		$this->user = isset($_GET['key']) ? Key::where('key',$_GET['key'])->first()->user : \Auth::user();
		if(isset($this->user)) return !($this->user->admin or $this->user->archivist);
		return true;
	}

	/**
	 * Return Validate User
	 * If the user is invalid return the errors, otherwise return false.
	 *
	 * @return Validator|bool
	 */
	private function validateUser()
	{
		$validator = Validator::make(request()->all(), [
			'email'                   => (request()->method() == "POST") ? 'required|unique:dbp_users.users,email' : 'required|exists:dbp_users.users,email',
			'project_id'              => 'required|exists:dbp_users.projects,id',
			'social_provider_id'      => 'required_with:social_provider_user_id',
			'social_provider_user_id' => 'required_with:social_provider_id',
			'name'                    => 'string|max:191',
			'nickname'                => 'string|max:191',
			'remember_token'          => 'max:100',
			'verified'                => 'boolean'
		]);

		if ($validator->fails()) return $this->replyWithError($validator->errors());
		return false;
	}


}
