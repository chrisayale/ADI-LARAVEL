<?php

namespace App\Policies;

use App\Models\MemberNotification;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberNotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any member notifications.
     * Super Admin can view notifications in their selected SACCO context.
     * Regular users can only view notifications in their own SACCO.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view notifications in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can only view notifications in their own SACCO
        return TenantContext::has();
    }

    /**
     * Determine if the user can view the member notification.
     */
    public function view(User $user, MemberNotification $notification)
    {
        // Super Admin can view notifications in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if notification belongs to that SACCO
            if (TenantContext::has()) {
                return $notification->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't view any notifications
            return false;
        }

        // Regular users can only view notifications in their own SACCO
        return $notification->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can create member notifications.
     */
    public function create(User $user)
    {
        // Super Admin can create notifications in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can create notifications in their own SACCO
        return !$user->hasRole('super_admin'); // Anyone except super_admin can create notifications
    }

    /**
     * Determine if the user can update the member notification.
     */
    public function update(User $user, MemberNotification $notification)
    {
        // Super Admin can update notifications in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if notification belongs to that SACCO
            if (TenantContext::has()) {
                return $notification->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't update any notifications
            return false;
        }

        // Regular users can only update notifications in their own SACCO
        return $notification->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can delete the member notification.
     */
    public function delete(User $user, MemberNotification $notification)
    {
        // Super Admin can delete notifications in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if notification belongs to that SACCO
            if (TenantContext::has()) {
                return $notification->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't delete any notifications
            return false;
        }

        // Regular users can only delete notifications in their own SACCO
        return $notification->sacco_id === $user->sacco_id;
    }
}