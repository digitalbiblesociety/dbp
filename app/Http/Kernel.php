<?php

namespace App\Http;

use App\Http\Middleware\CheckIsUserActivated;
use App\Http\Middleware\Cors;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

// Middleware
use \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use \Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use \App\Http\Middleware\TrimStrings;
use \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use \App\Http\Middleware\TrustProxies;
use \App\Http\Middleware\EncryptCookies;
use \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use \Illuminate\Session\Middleware\StartSession;
use \Illuminate\View\Middleware\ShareErrorsFromSession;
use \Illuminate\Routing\Middleware\SubstituteBindings;
use \App\Http\Middleware\VerifyCsrfToken;
use \App\Http\Middleware\Laravel2step;
use \Illuminate\Auth\Middleware\Authenticate;
use \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use \Illuminate\Http\Middleware\SetCacheHeaders;
use \Illuminate\Auth\Middleware\Authorize;
use \App\Http\Middleware\RedirectIfAuthenticated;
use \Illuminate\Routing\Middleware\ThrottleRequests;
use \jeremykenedy\LaravelRoles\Middleware\VerifyRole;
use \jeremykenedy\LaravelRoles\Middleware\VerifyPermission;
use \jeremykenedy\LaravelRoles\Middleware\VerifyLevel;
use \App\Http\Middleware\CheckCurrentUser;
use \Lunaweb\Localization\Middleware\LocalizationHandler;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
	        SubstituteBindings::class,
            VerifyCsrfToken::class,
	        Laravel2step::class,
	        LocalizationHandler::class,
        ],
        'api' => [
            //'throttle:120,1',
            'bindings',
	        Cors::class
        ],
        'activated' => [
            CheckIsUserActivated::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'          => Authenticate::class,
        'auth.basic'    => AuthenticateWithBasicAuth::class,
        'bindings'      => SubstituteBindings::class,
        'cache.headers' => SetCacheHeaders::class,
        'can'           => Authorize::class,
        'guest'         => RedirectIfAuthenticated::class,
        'throttle'      => ThrottleRequests::class,
        'role'          => VerifyRole::class,
        'permission'    => VerifyPermission::class,
        'level'         => VerifyLevel::class,
        'currentUser'   => CheckCurrentUser::class,
        'activated'     => CheckIsUserActivated::class,
    ];
}
