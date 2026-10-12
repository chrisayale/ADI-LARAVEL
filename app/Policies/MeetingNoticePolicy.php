<?php

namespace App\Policies;

use App\Models\MeetingNotice;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingNoticePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any meeting notices.
     * Super Admin can view notices in their selected SACCO context.
     * Regular users can only view notices in their own SACCO.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view notices in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can only view notices in their own SACCO
        return TenantContext::has();
    }

    /**
     * Determine if the user can view the meeting notice.
     */
    public function view(User $user, MeetingNotice $notice)
    {
        // Super Admin can view notices in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if notice belongs to that SACCO
            if (TenantContext::has()) {
                return $notice->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't view any notices
            return false;
        }

        // Regular users can only view notices in their own SACCO
        return $notice->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can create meeting notices.
     */
    public function create(User $user)
    {
        // Super Admin can create notices in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can create notices in their own SACCO
        return !$user->hasRole('super_admin'); // Anyone except super_admin can create notices
    }

    /**
     * Determine if the user can update the meeting notice.
     */
    public function update(User $user, MeetingNotice $notice)
    {
        // Super Admin can update notices in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if notice belongs to that SACCO
            if (TenantContext::has()) {
                return $notice->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't update any notices
            return false;
        }

        // Regular users can only update notices in their own SACCO
        return $notice->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can delete the meeting notice.
     */
    public function delete(User $user, MeetingNotice $notice)
    {
        // Super Admin can delete notices in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if notice belongs to that SACCO
            if (TenantContext::has()) {
                return $notice->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't delete any notices
            return false;
        }

        // Regular users can only delete notices in their own SACCO
        return $notice->sacco_id === $user->sacco_id;
    }
}