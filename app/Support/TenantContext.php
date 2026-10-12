<?php

namespace App\Support;

use App\Models\Sacco;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class TenantContext
{
    /**
     * Obtenir le SACCO courant depuis le conteneur d'application
     */
    public static function get(): ?Sacco
    {
        return app()->bound('currentSacco') ? app('currentSacco') : null;
    }
    
    /**
     * Définir le SACCO courant dans le conteneur d'application
     */
    public static function set(Sacco $sacco): void
    {
        app()->instance('currentSacco', $sacco);
        View::share('currentSacco', $sacco);
    }
    
    /**
     * Obtenir l'ID du SACCO courant
     */
    public static function id(): ?int
    {
        $sacco = self::get();
        return $sacco ? $sacco->id : null;
    }
    
    /**
     * Vérifier si un SACCO courant est défini
     */
    public static function has(): bool
    {
        return app()->bound('currentSacco') && self::get() !== null;
    }
    
    /**
     * Effacer le SACCO courant du contexte
     */
    public static function clear(): void
    {
        app()->forgetInstance('currentSacco');
        View::share('currentSacco', null);
    }
    
    /**
     * Vérifier si le contexte courant est un contexte Super Admin
     * (c'est-à-dire qu'un Super Admin a sélectionné un SACCO différent du sien)
     */
    public static function isSuperAdminContext(): bool
    {
        if (!Auth::check() || !Auth::user()->hasRole('super_admin')) {
            return false;
        }
        
        $currentSacco = self::get();
        $userSaccoId = Auth::user()->sacco_id;
        
        // Si aucun contexte défini, ce n'est pas un contexte Super Admin spécial
        if (!$currentSacco) {
            return false;
        }
        
        // Si le contexte SACCO est différent du SACCO de l'utilisateur, c'est un contexte Super Admin
        return $currentSacco->id !== $userSaccoId;
    }
    
    /**
     * Vérifier si l'utilisateur courant appartient au SACCO spécifié
     */
    public static function belongsTo(int $saccoId): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        // Super Admin : accès autorisé (validation faite dans les policies)
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        return $user->sacco_id === $saccoId;
    }
    
    /**
     * Obtenir une requête builder avec scope SACCO appliqué
     * 
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public static function scope($query)
    {
        if (!self::has()) {
            // Pas de SACCO défini - retourner une requête qui ne retourne aucun résultat
            return $query->whereRaw('1 = 0');
        }
        
        return $query->where('sacco_id', self::id());
    }
    
    /**
     * Vérifier si le SACCO courant est actif
     */
    public static function isActive(): bool
    {
        $sacco = self::get();
        return $sacco && $sacco->status === 'active';
    }
}
