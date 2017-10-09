<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;
use App\Http\Controllers\APIController;
use App\Models\User\Account;
use App\Models\User\User;
class SocialAuthController extends APIController
{
	public function redirect($provider)
	{
		return Socialite::driver($provider)->redirect();
	}

	public function callback($provider)
	{
		$user = \Socialite::driver($provider)->user();
		$this->createOrGetUser($user,$provider);
		auth()->login($user);
		return redirect()->to('/home');
	}

	public function createOrGetUser(ProviderUser $providerUser,$provider)
	{
		$account = Account::where('provider',$provider)->where('provider_user_id',$providerUser->getId())->first();
		if (!$account) {
			$account = new Account(['provider_user_id' => $providerUser->getId(),'provider' => $provider]);
			$user = User::where('email',$providerUser->getEmail())->first();
			if (!$user) {
				$user = User::create([
					'nickname' => $providerUser->getNickname(),
					'email'    => $providerUser->getEmail(),
					'name'     => $providerUser->getName(),
					'avatar'   => $providerUser->getAvatar()
				]);
			}
			$account->user()->associate($user);
			$account->save();
			return $user;
		}
		return $account->user;
	}


}
