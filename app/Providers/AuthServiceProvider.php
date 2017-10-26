<?php

namespace App\Providers;

use App\Models\Bible\BibleFileset;
use App\Policies\BibleFilesetsPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        BibleFileset::class => BibleFilesetsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

	    Gate::resource('BibleFileset', 'BibleFilesetsPolicy');
    }
}
