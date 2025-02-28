<?php

namespace App\Providers;

use App\Models\Client;
use App\Policies\ClientPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Client::class => ClientPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::policy(Client::class, ClientPolicy::class);

        $this->registerPolicies();

        // Define the manage-clients gate
        Gate::define('manage-clients', function ($user) {
            return $user->hasRole('admin');
        });

        // Define gates for other resources if needed
        Gate::define('manage-suppliers', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-brands', function ($user) {
            return $user->hasRole('admin');
        });
    }
}
