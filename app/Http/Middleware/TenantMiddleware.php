<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TenantResolver;
use App\Support\TenantContext;
use App\Exceptions\TenantException;

class TenantMiddleware
{
    protected $tenantResolver;
    
    public function __construct(TenantResolver $tenantResolver)
    {
        $this->tenantResolver = $tenantResolver;
    }
    
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Ne pas appliquer sur les routes d'authentification publique
        if ($this->shouldSkip($request)) {
            return $next($request);
        }
        
        try {
            // Résoudre le SACCO courant
            $currentSacco = $this->tenantResolver->resolve();
            
            // Mettre le SACCO courant dans le contexte partagé
            if ($currentSacco !== null) {
                TenantContext::set($currentSacco);
            } else {
                TenantContext::clear();
            }
            
            // Pour les superb_admin, permettre le changement de contexte uniquement via route dédiée
            # (pas via header - voir la route POST /super-admin/sacco-context)
            
            return $next($request);
        } catch (\App\Exceptions\TenantException $e) {
            // Gestion centralisée des erreurs de tenant
            if ($e->getCode() === 401) {
                return redirect()->route('login')->with('error', $e->getMessage());
            }
            
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            // En cas d'erreur inattendue, journaliser et échouer en toute sécurité
            \Log::error('Tenant middleware error: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id() ?? null
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Une erreur interne est survenue. Veuillez vous reconnecter.');
        }
    }
    
    /**
     * Déterminer si le middleware doit être ignoré pour cette requête
     */
    protected function shouldSkip(Request $request): bool
    {
        $skipRoutes = [
            'login',
            'logout',
            'password.*',
            'register',
            'verify-email',
            'password-reset',
            'password/confirm',
        ];
        
        foreach ($skipRoutes as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }
        
        return false;
    }
}
