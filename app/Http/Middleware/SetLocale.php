<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['fr', 'en'];

        // Get locale from session, default to 'fr'
        $locale = session('locale', 'fr');

        // Validate locale is supported
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'fr';
        }

        // Set the application locale
        app()->setLocale($locale);

        return $next($request);
    }
}