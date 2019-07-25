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
use App\Models\User\Study\Note;

use App\Transformers\Serializers\DataArraySerializer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Socialite;
use Image;
use Mail;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
     *     @OA\Parameter(name="limit",  in="query", description="The number of search results to return",
     *          @OA\Schema(type="integer",default=100)),
     *     @OA\Parameter(name="page",  in="query", description="The current page of the results",
     *          @OA\Schema(type="integer",default=1)),
     *     @OA\Parameter(name="project_id", in="query", required=true, description="The Project id", @OA\Schema(ref="#/components/schemas/Project/properties/id")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_user_index")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_user_index")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_user_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_user_index"))
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

        $users = \DB::table('dbp_users.users')->join('dbp_users.project_members', function ($join) use ($project_id) {
            $join->on('users.id', 'project_members.user_id')->where('project_members.project_id', $project_id);
        })->select(['id', 'name', 'email'])->paginate($limit);

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
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_user_show")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_user_show"))
     *     )
     * )
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($id)
    {
        $available_projects = $this->availableProjects();

        $user = User::with('accounts', 'organizations', 'profile')
            ->whereHas('projectMembers', function ($query) use ($available_projects) {
                if (!empty($available_projects)) $query->whereIn('project_id', $available_projects);
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
     *     path="/login",
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
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_user_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_user_index"))
     *     )
     * )
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function login(Request $request)
    {
        if (!$this->api && $request->method() !== 'POST') {
            return view('auth.login');
        }

        $email = checkParam('email');
        $social_provider_id = checkParam('social_provider_id');

        if ($email) {
            $password = checkParam('password');
            $user = $this->loginWithEmail($email, $password);
        } elseif ($social_provider_id) {
            $social_provider_user_id = checkParam('social_provider_user_id');
            $user = $this->loginWithSocialProvider($social_provider_id, $social_provider_user_id);
        }

        if (!$user) {
            return $this->setStatusCode(401)->replyWithError(trans('auth.failed'));
        }

        // Associate user with Project
        $project_id = checkParam('project_id');
        if ($project_id) {
            $connection_exists = ProjectMember::where(['user_id' => $user->id, 'project_id' => $project_id])->exists();
            if (!$connection_exists) {
                $role = Role::where('slug', 'user')->first();
                ProjectMember::create([
                    'user_id'    => $user->id,
                    'project_id' => $project_id,
                    'role_id'    => $role->id ?? 'user'
                ]);
            }
        }

        $token = Str::random(60);
        $user->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        $user->api_token = $token;


        if ($this->api) {
            return $user;
        }

        Auth::login($user, true);
        return redirect()->to('dashboard');
    }

    private function loginWithEmail($email, $password)
    {
        $user = User::with('accounts')->where('email', $email)->first();
        if (!$user) {
            return false;
        }

        $oldPassword = \Hash::check(md5($password), $user->password);
        $newPassword = \Hash::check($password, $user->password);
        if (!$oldPassword && !$newPassword) {
            return false;
        }

        return $user;
    }

    private function loginWithSocialProvider($provider_id, $provider_user_id)
    {
        $user = User::with('accounts')->whereHas('accounts', function ($query) use ($provider_id, $provider_user_id) {
            $query->where('provider_id', $provider_id)->where('provider_user_id', $provider_user_id);
        })->first();

        return $user;
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
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_user_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_user_index"))
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
        }
        if ($request->social_provider_id) {
            $user->accounts()->create([
                'provider_id'      => $request->social_provider_id,
                'provider_user_id' => $request->social_provider_user_id,
            ]);
        }

        $token = Str::random(60);
        $user->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        $user->api_token = $token;

        if (!$this->api) {
            Auth::login($user, true);
            return redirect()->to('home');
        }

        return $this->setStatusCode(200)->reply(fractal($user, new UserTransformer())->addMeta(['success' => 'User created']));
    }

    /**
     *
     * @OA\Put(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Update an existing user",
     *     description="",
     *     operationId="v4_user.update",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="id", in="path", description="The user ID for which to retrieve info.", required=true, @OA\Schema(ref="#/components/schemas/User/properties/id")),
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
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_user_index")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(ref="#/components/schemas/v4_user_index"))
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
        $user_projects = $user->projects->pluck('id');
        $developer_projects = $this->user->projectMembers->whereIn('role_id', [2, 3, 4])->pluck('project_id');

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
                'token'         => unique_random(config('database.connections.dbp_users.database') . '.project_members', 'token'),
                'subscribed'    => false
            ]);

            Mail::to($user->email)->send(new ProjectVerificationEmail($connection, $project));
            return $this->setStatusCode(401)->replyWithError(trans('api.projects_users_needs_to_connect', [], $GLOBALS['i18n_iso']));
        }

        // Fetch Data
        $user->fill($request->except(['v', 'key', 'pretty', 'project_id']))->save();

        if ($this->api) {
            return $this->reply(['success' => 'User updated', 'user' => $user]);
        }
        return view('dashboard.users.show', $id);
    }

    /**
     *
     * @OA\Delete(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Delete an existing user",
     *     description="",
     *     operationId="v4_user.destroy",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     security={{"api_token":{}}},
     *     @OA\RequestBody(required=true, description="Either `password` or the `social_provider_user_id` & `social_provider_id` are required for user deletion", @OA\MediaType(mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="password",                  ref="#/components/schemas/User/properties/password"),
     *              @OA\Property(property="social_provider_user_id",   ref="#/components/schemas/Account/properties/provider_user_id"),
     *              @OA\Property(property="social_provider_id",        ref="#/components/schemas/Account/properties/provider_id"),
     *          )
     *     )),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(type="string")),
     *         @OA\MediaType(mediaType="text/csv",      @OA\Schema(type="string"))
     *     )
     * )
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        $password = checkParam('password');
        $social_provider_user_id = checkParam('social_provider_user_id');
        $social_provider_id = checkParam('social_provider_id');

        $user = User::with('accounts')->where('id', $user->id)->first();
        $access_granted = false;
        if ($password) {
            $oldPassword = \Hash::check(md5($password), $user->password);
            $newPassword = \Hash::check($password, $user->password);
            $access_granted = $oldPassword || $newPassword;
        } else if ($social_provider_id) {
            $account  = $user->accounts->where('provider_id', $social_provider_id)
                ->where('provider_user_id', $social_provider_user_id)->first();
            $access_granted = $account;
        }

        if (!$access_granted) {
            return $this->setStatusCode(401)->replyWithError(trans('auth.failed'));
        }

        // Overwrite notes
        Note::where('user_id', $user->id)->update(['notes' => encrypt('Deleted user note')]);

        // Delete accounts
        Account::where('user_id', $user->id)->delete();

        // Overwrite personal user information and soft delete account
        $user->fill([
            'name' => 'deleted',
            'first_name'  => 'deleted',
            'last_name'  => 'user',
            'email'  => $user->id . '@deleted.com',
            'password'  => Str::random(40),
        ])->save();;

        $user->delete();

        return $this->reply('User successfully deleted');
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
