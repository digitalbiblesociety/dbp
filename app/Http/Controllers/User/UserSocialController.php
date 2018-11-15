<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\APIController;

use App\Models\User\ProjectOauthProvider;
use App\Models\User\Account;
use App\Models\User\User;

use Laravel\Socialite\Two\BitbucketProvider;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\One\TwitterProvider;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\GoogleProvider;

use Socialite;

class UserSocialController extends APIController
{

    /**
     *
     * @OAS\Get(
     *     path="/users/login/{driver}",
     *     tags={"Users"},
     *     summary="Add a new oAuth provider to a project",
     *     description="",
     *     operationId="v4_projects_oAuthProvider.store",
     *     @OAS\Parameter(ref="#/components/parameters/version_number"),
     *     @OAS\Parameter(ref="#/components/parameters/key"),
     *     @OAS\Parameter(ref="#/components/parameters/pretty"),
     *     @OAS\Parameter(ref="#/components/parameters/format"),
     *     @OAS\Parameter(
     *          name="driver",
     *          in="path",
     *          required=true,
     *          @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/name"),
     *          description="The Provider name, the currently supported providers are: facebook, bitbucket, github, twitter, & google",
     *     ),
     *     @OAS\Parameter(
     *          name="project_id",
     *          in="query",
     *          required=true,
     *          @OAS\Schema(ref="#/components/schemas/Project/properties/id"),
     *          description="The Project id"
     *     ),
     *     @OAS\Parameter(
     *          name="alt_url",
     *          in="query",
     *          @OAS\Schema(ref="#/components/schemas/ProjectOauthProvider/properties/callback_url_alt"),
     *          description="When this parameter is set, the return will use the alternative callback url"
     *     ),
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
     * @return mixed
     *
     */
    public function getSocialRedirect($provider = null)
    {
        $project_id = checkParam('project_id');
        $provider   = checkParam('name', $provider);
        $alt_url    = checkParam('alt_url', null, 'optional');

        $socialiteProvider = $this->getOauthProvider($project_id, $provider, $alt_url);
        if (is_a($socialiteProvider, JsonResponse::class)) return $socialiteProvider;
        return $this->reply([
            'data' => [
                'provider_id'  => $provider,
                'redirect_url' => urldecode($socialiteProvider->redirect()->getTargetUrl()),
            ]
        ]);
    }

    public function handleProviderCallback($provider)
    {
        $user = \Socialite::driver($provider)->stateless()->user();
        $user = $this->createOrGetUser($user, $provider);
        return $user;
    }

    private function getOauthProvider($project_id, $provider, $alt_url = null)
    {
        if ($provider === 'twitter') return $this->setStatusCode(422)->replyWithError(trans('api.auth_errors_twitter_stateless'));

        $driverData = ProjectOauthProvider::where('project_id', $project_id)->where('name', $provider)->first();
        if (!$driverData) return $this->setStatusCode(404)->replyWithError('No oAuth Provider found for the given params');
        $driver     = [
            'client_id'     => $driverData->client_id,
            'client_secret' => $driverData->client_secret,
            'redirect'      => $alt_url === null ? $driverData->callback_url : $driverData->callback_url_alt,
        ];
        switch ($provider) {
            case 'bitbucket': {
                $providerClass = BitbucketProvider::class;
                break;
            }
            case 'facebook':  {
                $providerClass = FacebookProvider::class;
                break;
            }
            case 'twitter':   {
                $providerClass = TwitterProvider::class;
                break;
            }
            case 'github':    {
                $providerClass = GithubProvider::class;
                break;
            }
            case 'google':    {
                $providerClass = GoogleProvider::class;
                break;
            }
            default:          {
                $providerClass = null;
            }
        }

        return Socialite::buildProvider($providerClass, $driver)->stateless();
    }

    private function createOrGetUser($providerUser, $provider)
    {
        $account = Account::where('provider_id', $provider)->where('provider_user_id', $providerUser->getId())->first();
        if (!$account) {
            $account = new Account(['provider_user_id' => $providerUser->getId(), 'provider_id' => $provider]);
            $user    = User::where('email', $providerUser->getEmail())->first();
            if (!$user) {
                $user = User::create([
                    'id'       => str_random(24),
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

}
