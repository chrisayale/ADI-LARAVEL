<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\TenantMiddleware;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the tenant middleware
        $this->app['router']->aliasMiddleware('tenant', TenantMiddleware::class);
        
        // Add tenant middleware to web group
        $this->app['router']->pushMiddlewareToGroup('web', TenantMiddleware::class);
    }
}
