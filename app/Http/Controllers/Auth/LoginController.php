<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\APIController;
use App\Http\Controllers\Controller;
use App\Models\User\ProjectOauthProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User\User;
use App\Models\User\Account;
use Laravel\Socialite\One\TwitterProvider;
use Laravel\Socialite\Two\BitbucketProvider;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\GoogleProvider;

class LoginController extends APIController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';


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
    public function redirectToProvider($provider)
    {
        if ($this->api) {
            if ($provider == "twitter") {
                return $this->setStatusCode(422)->replyWithError(trans('api.auth_errors_twitter_stateless'));
            }

            $project_id = checkParam('project_id');
            $provider   = checkParam('name', $provider);
            $alt_url    = checkParam('alt_url', null, 'optional');
            if ($provider == "twitter") {
                return $this->setStatusCode(422)->replyWithError(trans('api.auth_errors_twitter_stateless'));
            }

            $driverData = ProjectOauthProvider::where('project_id', $project_id)->where('name', $provider)->first();
            $driver     = [
                'client_id'     => $driverData->client_id,
                'client_secret' => $driverData->client_secret,
                'redirect'      => (!isset($alt_url)) ? $driverData->callback_url : $driverData->callback_url_alt,
            ];

            switch ($provider) {
                case "facebook": {
                    $providerClass = FacebookProvider::class;
                    break;
                }
                case "bitbucket": {
                    $providerClass = BitbucketProvider::class;
                    break;
                }
                case "github": {
                    $providerClass = GithubProvider::class;
                    break;
                }
                case "twitter": {
                    $providerClass = TwitterProvider::class;
                    break;
                }
                case "google": {
                    $providerClass = GoogleProvider::class;
                    break;
                }
            }

            return $this->reply(Socialite::buildProvider($providerClass,
                $driver)->stateless()->redirect()->getTargetUrl());
        }
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = \Socialite::driver($provider)->stateless()->user();
        $user = $this->createOrGetUser($user, $provider);

        \Auth::login($user);
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
        return redirect()->route('home');
    }

}
