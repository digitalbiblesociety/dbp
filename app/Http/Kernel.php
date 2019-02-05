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
        TrustProxies::class,
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        Cors::class
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
            LocalizationHandler::class,
        ],
        'api' => [
            'throttle:2000,1',
            'bindings'
        ],
        //'activated' => [CheckIsUserActivated::class,],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces the listed middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
