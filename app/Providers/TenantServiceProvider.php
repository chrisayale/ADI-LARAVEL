<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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

        // Restore default Laravel CSRF exceptions that were accidentally removed
        VerifyCsrfToken::except([
            '/login',
            '/logout',
            '/password/*',
        ]);
    }
}
