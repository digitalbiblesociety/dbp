<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\APIController;
use App\Mail\ProjectVerificationEmail;

use App\Models\User\Project;
use App\Models\User\ProjectOauthProvider;
use App\Models\User\ProjectMember;
use App\Models\User\Account;
use App\Models\User\Role;
use App\Models\User\User;
use App\Models\User\Key;

use App\Transformers\Serializers\DataArraySerializer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

use Socialite;
use Image;
use Mail;
use Validator;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class UsersController extends APIController
{

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
        if (!$this->api) {
            return view('dashboard.users.index');
        }
        $limit = checkParam('limit') ?? 100;
        $project_id = checkParam('project_id');

        $users = \DB::table('users')->join('project_members', function ($join) use ($project_id) {
            $join->on('users.id', '=', 'project_members.user_id')
                 ->where('project_members.project_id', '=', $project_id);
        })->select(['id','name','email'])->paginate($limit);

        $userCollection = $users->getCollection();
        $userPagination = new IlluminatePaginatorAdapter($users);
        return $this->reply(fractal($userCollection, UserTransformer::class, $this->serializer)->paginateWith($userPagination));
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
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_user_show")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_user_show")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_user_show"))
     *     )
     * )
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($id)
    {
        $project_limited = $this->projectLimited();
        $available_projects = $this->availableProjects();

        $user = User::with('accounts', 'organizations', 'profile')->when($project_limited, function ($q) use ($available_projects) {
            $q->whereHas('projectMembers', function ($query) use ($available_projects) {
                $query->whereIn('project_id', $available_projects);
            });
        })->where('id', $id)->first();
        if (!$user) {
            return $this->replyWithError(trans('api.users_errors_404', ['param' => $id]));
        }

        if (!$this->api) {
            return view('dashboard.users.show', compact('user'));
        }
        return $this->reply(fractal($user, UserTransformer::class));
    }

    public function edit($id)
    {
        $authorized_user = $this->unauthorizedToAlterUsers();
        if (!$authorized_user) {
            return $this->setStatusCode(401)->replyWithError(trans('auth.not_logged_in'));
        }
        $user = User::with('organizations.currentTranslation')->where('id', $id)->first();

        return view('dashboard.users.edit', compact('user'));
    }

    public function create()
    {
        $project = Project::where('name', 'Digital Bible Platform')->first();
        return view('auth.register', compact('project'));
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
     *              @OA\Property(property="project_id",                ref="#/components/schemas/Project/properties/id"),
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
        $project_id = checkParam('project_id');

        if (!$this->api && $request->method() !== 'POST') {
            return view('auth.login');
        }
        $user = User::with('accounts')->where('email', $request->email)->first();
        if (!$user) {
            return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_email'));
        }

        $oldPassword = \Hash::check(md5($request->password), $user->password);
        $newPassword = \Hash::check($request->password, $user->password);

        if ($oldPassword || $newPassword) {
            // Associate user with Project
            if ($project_id) {
                $connection_exists = ProjectMember::where(['user_id' =>$user->id, 'project_id' =>$project_id])->exists();
                if (!$connection_exists) {
                    $role = Role::where('slug', 'user')->first();
                    ProjectMember::create([
                        'user_id'    => $user->id,
                        'project_id' => $project_id,
                        'role_id'    => $role->id ?? 'user'
                    ]);
                }
            }

            if ($this->api) {
                return $user;
            }

            \Auth::guard()->setUser($user);
            return view('dashboard.home', compact('user'));
        }

        return $this->setStatusCode(401)->replyWithError(trans('auth.failed'));
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
     *              @OA\Property(property="avatar",                  ref="#/components/schemas/User/properties/avatar"),
     *              @OA\Property(property="email",                   ref="#/components/schemas/User/properties/email"),
     *              @OA\Property(property="name",                    ref="#/components/schemas/User/properties/name"),
     *              @OA\Property(property="password",                ref="#/components/schemas/User/properties/password"),
     *              @OA\Property(property="project_id",              ref="#/components/schemas/ProjectMember/properties/project_id"),
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
        $invalid = $this->validateUser();
        if ($invalid) {
            return $invalid;
        }

        $user = User::create([
            'avatar'        => $request->avatar,
            'email'         => $request->email,
            'name'          => $request->name,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'token'         => unique_random('dbp_users.users', 'token'),
            'activated'     => 0,
            'notes'         => $request->notes,
            'password'      => \Hash::make($request->password),
        ]);
        if ($request->project_id) {
            $user_role = Role::where('slug', 'user')->first();
            if (!$user_role) {
                return $this->setStatusCode(404)->replyWithError('The Roles table has not been populated');
            }
            $user->projectMembers()->create([
                'project_id' => $request->project_id,
                'role_id'    => $user_role->id,
                'subscribed' => $request->subscribed ?? 0,
            ]);

            $dbp_project = Project::where('name', 'Digital Bible Platform')->first();

            if ($request->project_id === $dbp_project->id) {
                $user->keys()->create([
                    'key' => unique_random('user_keys', 'key', 24),
                    'name' => 'DBP Generated',
                    'description' => 'An Auto-Generated Key'
                ]);
            }
        }
        if ($request->social_provider_id) {
            $user->accounts()->create([
                'provider_id'      => $request->social_provider_id,
                'provider_user_id' => $request->social_provider_user_id,
            ]);
        }
        if (!$this->api) {
            return view('dashboard.home', compact('user'));
        }
        return $this->reply(fractal($user, new UserTransformer())->addMeta(['success' => 'User created']));
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
     * @param         $id
     *
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        if ((int) $request->v === 1) {
            return redirect('http://api.dbp.test/login?reply=json');
        }
        // Validate Request
        $invalidRequest = $this->validateUser();
        if ($invalidRequest) {
            return $invalidRequest;
        }

        // Validate User
        $unauthorized_user = $this->unauthorizedToAlterUsers();
        if ($unauthorized_user) {
            return $unauthorized_user;
        }

        // Retrieve User
        $user = User::with('projects')->where('email', request()->email)->first();
        if (!$user) {
            return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404_email', ['email' => request()->email], $GLOBALS['i18n_iso']));
        }

        // If the request does not originate from an admin
        if ($this->projectLimited()) {
            $user_projects = $user->projects->pluck('id');
            $developer_projects = $this->user->developer->pluck('id');
            if (!$developer_projects->contains(request()->project_id)) {
                return $this->setStatusCode(401)->replyWithError(trans('api.projects_developer_not_a_member', [], $GLOBALS['i18n_iso']));
            }

            if ($developer_projects->intersect($user_projects)->count() === 0) {
                $project = Project::where('id', request()->project_id)->first();
                if (!$project) {
                    return $this->setStatusCode(404)->replyWithError(trans('api.projects_404'));
                }
                $connection = $user->projectMembers()->create([
                    'user_id'       => $user->id,
                    'project_id'    => $project->id,
                    'role_id'       => 'user',
                    'token'         => unique_random(config('database.connections.dbp_users.database').'.project_members', 'token'),
                    'subscribed'    => false
                ]);

                Mail::to($user->email)->send(new ProjectVerificationEmail($connection, $project));
                return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_needs_to_connect', [], $GLOBALS['i18n_iso']));
            }
        }

        // Fetch Data
        $input = $request->all();

        // Process Avatar
        if ($request->hasFile('avatar')) {
            //$input['avatar'] = $id.".".$request->file('avatar')->extension();
            //dd($request->file('avatar'));
            $image = Image::make($request->file('avatar'));
            if (isset($request->avatar_crop_width, $request->avatar_crop_height)) {
                $image->crop($request->avatar_crop_width, $request->avatar_crop_height, $request->avatar_crop_inital_x_coordinate, $request->avatar_crop_inital_y_coordinate);
            }
            $image->resize(300, 300);
            \Storage::disk('public')->put($id.'.'.$request->avatar->extension(), $image->save());
            $input['avatar'] = \URL::to('/storage/'.$id.'.'.$request->avatar->extension());
        }

        $user->fill($input)->save();
        if ($this->api) {
            return $this->reply(['success' => 'User updated', 'user' => $user]);
        }
        return view('dashboard.users.show', $id);
    }

    public function destroy($id)
    {
        $project_id = checkParam('project_id');
        $connection = ProjectMember::where('user_id', $id)->where('project_id', $project_id)->first();
        if (!$connection) {
            return $this->setStatusCode(404)->replyWithError('User/Project connection not found');
        }
        $connection->delete();

        return $this->reply('User Project connection successfully removed');
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
     * @return mixed
     */
    public function geoLocate()
    {
        $ip_address  = checkParam('ip_address') ?? request()->getClientIp();

        $geolocation = geoip($ip_address);
        return $this->reply([
            'ip'          => $geolocation->getAttribute('ip'),
            'iso_code'    => $geolocation->getAttribute('iso_code'),
            'country'     => $geolocation->getAttribute('country'),
            'city'        => $geolocation->getAttribute('city'),
            'state'       => $geolocation->getAttribute('state'),
            'state_name'  => $geolocation->getAttribute('state_name'),
            'postal_code' => $geolocation->getAttribute('postal_code'),
            'lat'         => $geolocation->getAttribute('lat'),
            'lon'         => $geolocation->getAttribute('lon'),
            'timezone'    => $geolocation->getAttribute('timezone'),
            'continent'   => $geolocation->getAttribute('continent'),
        ]);
    }

    public function verify($token)
    {
        $user = User::where('email_token', $token)->first();
        if (!$user) {
            return $this->setStatusCode(404)->replyWithError(trans('api.users_errors_404', ['param' => $token]));
        }
        $user->verified = 1;
        $user->save();
        \Auth::login($user);
        $this->guard()->login($user);
        return redirect()->route('home');
    }

    /**
     * @return bool
     */
    private function unauthorizedToAlterUsers()
    {
        if ($this->user === null) {
            return $this->setStatusCode(401)->replyWithError(trans('api.auth_key_validation_failed'));
        }
        $developer = $this->user->projectMembers->where('slug', 'developer')->first();
        if ($developer !== null) {
            return $this->setStatusCode(401)->replyWithError(trans('api.auth_user_validation_failed'));
        }
        return false;
    }

    private function projectLimited()
    {
        $this->user = isset($_GET['key']) ? Key::where('key', $_GET['key'])->first()->user : \Auth::user();
        if ($this->user !== null) {
            return !($this->user->admin or $this->user->archivist);
        }
        return true;
    }


    private function availableProjects()
    {
        $role = Role::where('slug', 'developer')->first();
        if (!$role) {
            return $this->setStatusCode(404)->replyWithError('The Roles table has not been populated');
        }
        $user = $this->user;
        if (!$user) {
            $user = Key::whereKey($this->key)->first()->user;
        }
        $userWithProjects = $user->load(['projectMembers' => function ($query) use ($role) {
            $query->where('role_id', $role->id);
        }]);

        return $userWithProjects->projectMembers->pluck('project_id')->toArray();
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
            'email'                   => (request()->method() === 'POST') ? 'required|unique:dbp_users.users,email' : 'required|exists:dbp_users.users,email',
            'project_id'              => 'required|exists:dbp_users.projects,id',
            'social_provider_id'      => 'required_with:social_provider_user_id',
            'social_provider_user_id' => 'required_with:social_provider_id',
            'name'                    => 'string|max:191',
            'first_name'              => 'string|max:64',
            'last_name'               => 'string|max:64',
            'remember_token'          => 'max:100',
            'verified'                => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->replyWithError($validator->errors());
        }
        return false;
    }
}
