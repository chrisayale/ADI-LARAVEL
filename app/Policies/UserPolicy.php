<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Sacco;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any users.
     * Super Admin can view users in their selected SACCO context.
     * Regular users can only view users in their own SACCO.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view users in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can only view users in their own SACCO
        return TenantContext::has();
    }

    /**
     * Determine if the user can view the user.
     */
    public function view(User $user, User $targetUser)
    {
        // Super Admin can view users in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if target user belongs to that SACCO
            if (TenantContext::has()) {
                return $targetUser->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't view any users (should be redirected to select SACCO)
            return false;
        }

        // Regular users can only view users in their own SACCO
        return $targetUser->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user)
    {
        // Super Admin can create users in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users cannot create users
        return false;
    }

    /**
     * Determine if the user can update the user.
     */
    public function update(User $user, User $targetUser)
    {
        // Super Admin can update users in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if target user belongs to that SACCO
            if (TenantContext::has()) {
                return $targetUser->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't update any users
            return false;
        }

        // Regular users can only update themselves
        return $user->id === $targetUser->id;
    }

    /**
     * Determine if the user can delete the user.
     */
    public function delete(User $user, User $targetUser)
    {
        // Super Admin can delete users in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if target user belongs to that SACCO
            if (TenantContext::has()) {
                return $targetUser->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't delete any users
            return false;
        }

        // Regular users cannot delete users
        return false;
    }
}