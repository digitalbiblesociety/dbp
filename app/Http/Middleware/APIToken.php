<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\AuthenticationException;

use Closure;

class APIToken
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $method
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, $type = '')
    {
        $guard = 'api';

        if ($type === 'check') {
            if (!$this->auth->guard($guard)->check()) {
                throw new AuthenticationException(trans('auth.failed'));
            }
        }

        $this->auth->shouldUse($guard);
        return $next($request);
    }
}
