<?php

namespace App\Providers;

use App\Auth\TeamGuard;
use App\Auth\TeamProvider;
use App\Models\Team;
use App\Policies\TeamPolicy;
use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }

    public function register()
    {
        parent::register();

        Auth::provider('teams', function ($app, array $config) {
            dd("work");
            return new TeamProvider();
        });

        Auth::extend('team', function ($app, $name, array $config) {
            return new TeamGuard(Auth::createUserProvider($config['provider']), $app['request']);
        });
    }
}
