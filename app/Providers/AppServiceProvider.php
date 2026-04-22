<?php

namespace App\Providers;

use App\Policies\PermissionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::define('gerer-stock', [PermissionPolicy::class, 'gererStock']);
        Gate::define('gerer-ventes', [PermissionPolicy::class, 'gererVentes']);
        Gate::define('admin', [PermissionPolicy::class, 'isAdmin']);
    }
}
