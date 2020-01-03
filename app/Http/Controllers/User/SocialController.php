<?php

namespace App\Http\Controllers\User;

use App\Models\User\ProjectMember;
use App\Models\User\Role;
use App\Http\Controllers\APIController;

use App\Models\User\ProjectOauthProvider;
use App\Models\User\Account;
use App\Models\User\User;

use Laravel\Socialite\Two\BitbucketProvider;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\GoogleProvider;

use Socialite;
use Illuminate\Support\Str;

class SocialController extends APIController
{
    public function redirect($provider = null)
    {
        $project_id = checkParam('project_id');
        $provider   = checkParam('provider', true, $provider);

        $oAuthDriver = $this->getOauthProvider($project_id, $provider);
        if (!$oAuthDriver) {
            return $this->setStatusCode(404)->replyWithError('Socialite Provider not found');
        }

        return $this->reply([
            'data' => [
                'provider_id'  => $provider,
                'redirect_url' => urldecode($oAuthDriver->stateless()->redirect()->getTargetUrl()),
            ]
        ]);
    }

    /**
     * @param $provider
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function callback($provider)
    {
        $project_id = checkParam('project_id', true);

        $driver = ProjectOauthProvider::where('project_id', $project_id)->where('name', $provider)->first();
        $oAuthDriver = $this->getOauthProvider($project_id, $provider);
        if (!$oAuthDriver) {
            return $this->setStatusCode(404)->replyWithError('Socialite Provider not found');
        }

        $user = $this->createOrGetUser($oAuthDriver->user(), $provider, $project_id);
        return redirect($driver->redirect_url.'?code='.base64_encode("$user->id,$user->email,$user->first_name $user->last_name"), 302);
    }

    private function getOauthProvider($project_id, $provider)
    {
        $driver = ProjectOauthProvider::where('project_id', $project_id)->where('name', $provider)->first();
        switch ($provider) {
            case 'bitbucket':
                $providerClass = BitbucketProvider::class;
                break;
            case 'facebook':
                $providerClass = FacebookProvider::class;
                break;
            case 'github':
                $providerClass = GithubProvider::class;
                break;
            case 'google':
                $providerClass = GoogleProvider::class;
                break;
            default:
                $providerClass = null;
        }

        if (!$providerClass || !$driver) {
            return null;
        }

        return Socialite::buildProvider($providerClass, [
            'client_id'     => $driver->client_id,
            'client_secret' => $driver->client_secret,
            'redirect'      => $driver->callback_url.'?project_id='.$project_id,
        ])->stateless();
    }

    private function createOrGetUser($providerUser, $provider, $project_id)
    {
        $account = Account::where('provider_id', $provider)->where('provider_user_id', $providerUser->getId())->first();
        if (!$account) {
            $user = User::where('email', $providerUser->getEmail())->first();
            if (!$user) {
                $user = User::create([
                    'id'        => Str::random(24),
                    'email'     => $providerUser->getEmail(),
                    'name'      => $providerUser->getName(),
                    'password'  => bcrypt(Str::random(16)),
                    'token'     => Str::random(10),
                    'activated' => 1,
                ]);
            }

            Account::create([
                'user_id'          => $user->id,
                'provider_user_id' => $providerUser->getId(),
                'provider_id'      => $provider,
                'project_id'       => $project_id
            ]);

            ProjectMember::create([
               'user_id'    => $user->id,
               'project_id' => $project_id,
               'role_id'    => Role::where('slug', 'user')->first()->id
            ]);

            return $user;
        }
        return $account->user;
    }
}
