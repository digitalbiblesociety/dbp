<?php

namespace App\Services\Auth;

use App\Models\User\APIToken;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class APITokenGuard implements Guard
{
    use GuardHelpers;

    protected $request;
    protected $provider;
    protected $user;
    protected $inputKey;


    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->user = null;
        $this->inputKey = 'api_token';
    }

    public function check()
    {
        return !is_null($this->user());
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;
        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $api_token = $this->getApiToken($token);
            if ($api_token) {
                $user = $this->provider->retrieveById($api_token->user_id);
            }
        }
        return $this->user = $user;
    }

    public function getTokenForRequest()
    {
        $token = $this->request->query($this->inputKey);

        if (empty($token)) {
            $token = $this->request->input($this->inputKey);
        }

        if (empty($token)) {
            $token = $this->request->bearerToken();
        }

        if (empty($token)) {
            $token = $this->request->getPassword();
        }

        return $token;
    }

    public function validate(array $credentials = [])
    {
        if (empty($credentials[$this->inputKey])) {
            return false;
        }

        $token =  $credentials[$this->inputKey];

        $api_token = $this->getApiToken($token);
        if (!$api_token) {
            return false;
        }

        if ($this->provider->retrieveById($api_token->user_id)) {
            return true;
        }

        return false;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    private function getApiToken($token)
    {
        return  APIToken::where('api_token', hash('sha256', $token))
            ->where('created_at', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL 1 YEAR)'))
            ->first();
    }
}
