<?php

namespace App\Policies;

use App\Models\Sacco;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class SaccoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any saccoes.
     * Only Super Admin can view saccoes.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can view the sacco.
     */
    public function view(User $user, Sacco $sacco)
    {
        // Super Admin can view any sacco
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Regular users can only view their own SACCO
        return $user->sacco_id === $sacco->id;
    }

    /**
     * Determine if the user can create saccoes.
     * Only Super Admin can create saccoes.
     */
    public function create(User $user)
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can update the sacco.
     */
    public function update(User $user, Sacco $sacco)
    {
        // Super Admin can update any sacco
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Regular users can only update their own SACCO
        return $user->sacco_id === $sacco->id;
    }

    /**
     * Determine if the user can delete the sacco.
     */
    public function delete(User $user, Sacco $sacco)
    {
        // Super Admin can delete any sacco
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Regular users cannot delete saccoes
        return false;
    }
}