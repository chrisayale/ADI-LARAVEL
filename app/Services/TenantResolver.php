<?php

namespace App\Services;

use App\Models\Sacco;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Store;
use Illuminate\Validation\ValidationException;
use App\Exceptions\TenantException;

class TenantResolver
{
    protected $session;
    
    public function __construct(Store $session)
    {
        $this->session = $session;
    }
    
    /**
     * Résout le SACCO courant basé sur l'utilisateur authentifié
     * 
     * @return \App\Models\Sacco|null
     * @throws \App\Exceptions\TenantException
     */
    public function resolve(): ?Sacco
    {
        // Utilisateur non authentifié
        if (!Auth::check()) {
            return null;
        }
        
        $user = Auth::user();
        
        // Super Admin avec sélection contextuelle en session
        if ($user->hasRole('super_admin') && $this->session->has('selected_sacco_id')) {
            $saccoId = $this->session->get('selected_sacco_id');

            // Ensure we have a valid integer ID to prevent TypeError
            if (!is_numeric($saccoId) || $saccoId === '') {
                $this->clearSelectedSacco();
                throw TenantException::saccoNotFound($saccoId);
            }
            $saccoId = (int) $saccoId;

            // Validation serveur obligatoire : le SACCO doit exister et être actif
            $sacco = Sacco::find($saccoId);

            if (!$sacco) {
                $this->clearSelectedSacco();
                throw TenantException::saccoNotFound($saccoId);
            }

            if ($sacco->status !== 'active') {
                $this->clearSelectedSacco();
                throw TenantException::saccoInactive($sacco->id, $sacco->status);
            }

            return $sacco;
        }
        
        // Utilisateur normal (ou Super Admin sans sélection) : utiliser son SACCO
        if ($user->sacco_id) {
            return $user->sacco;
        }

        // Super Admin sans SACCO et sans sélection de contexte : retourner null (pas de contexte)
        if ($user->hasRole('super_admin')) {
            return null;
        }

        // Cas extrême : utilisateur sans SACCO (devrait être rare après migration)
        throw TenantException::noSaccoAssigned();
    }
    
    /**
     * Définit le SACCO sélectionné pour un Super Admin
     * 
     * @param int $saccoId
     * @throws \App\Exceptions\TenantException
     */
    public function setSelectedSacco(int $saccoId): void
    {
        // Vérifier que l'utilisateur courant est bien un Super Admin
        if (!Auth::check() || !Auth::user()->hasRole('super_admin')) {
            throw TenantException::onlySuperAdminCanSetContext();
        }
        
        // Vérifier que le SACCO existe et est actif
        $sacco = Sacco::find($saccoId);
        
        if (!$sacco) {
            throw TenantException::saccoNotFound($saccoId);
        }
        
        if ($sacco->status !== 'active') {
            throw TenantException::saccoInactive($sacco->id, $sacco->status);
        }
        
        // Stocker en session
        $this->session->put('selected_sacco_id', $saccoId);
        
        // Journaliser l'action d'audit
        \Log::info('Super Admin changed SACCO context', [
            'user_id' => Auth::id(),
            'sacco_id' => $saccoId,
            'action' => 'set_sacco_context'
        ]);
    }
    
    /**
     * Efface le SACCO sélectionné (revient au SACCO de l'utilisateur)
     */
    public function clearSelectedSacco(): void
    {
        if (Auth::check() && Auth::user()->hasRole('super_admin')) {
            $this->session->forget('selected_sacco_id');
            
            \Log::info('Super Admin cleared SACCO context', [
                'user_id' => Auth::id(),
                'action' => 'cleared_sacco_context'
            ]);
        }
    }
    
    /**
     * Récupère l'ID du SACCO sélectionné en session (pour Super Admin)
     * 
     * @return int|null
     */
    public function getSelectedSacco(): ?int
    {
        return $this->session->get('selected_sacco_id');
    }
    
    /**
     * Vérifie si l'utilisateur courant appartient au SACCO spécifié
     * 
     * @param int $saccoId
     * @return bool
     */
    public function belongsTo(int $saccoId): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        // Super Admin peut accéder à tout SACCO (la validation du contexte est faite ailleurs)
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        return $user->sacco_id === $saccoId;
    }
}
