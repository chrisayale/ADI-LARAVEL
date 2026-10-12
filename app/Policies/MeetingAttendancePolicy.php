<?php

namespace App\Policies;

use App\Models\MeetingAttendance;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingAttendancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any meeting attendances.
     * Super Admin can view attendances in their selected SACCO context.
     * Regular users can only view attendances in their own SACCO.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view attendances in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can only view attendances in their own SACCO
        return TenantContext::has();
    }

    /**
     * Determine if the user can view the meeting attendance.
     */
    public function view(User $user, MeetingAttendance $attendance)
    {
        // Super Admin can view attendances in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if attendance belongs to that SACCO
            if (TenantContext::has()) {
                return $attendance->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't view any attendances
            return false;
        }

        // Regular users can only view attendances in their own SACCO
        return $attendance->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can create meeting attendances.
     */
    public function create(User $user)
    {
        // Super Admin can create attendances in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can create attendances in their own SACCO
        return !$user->hasRole('super_admin'); // Anyone except super_admin can create attendances
    }

    /**
     * Determine if the user can update the meeting attendance.
     */
    public function update(User $user, MeetingAttendance $attendance)
    {
        // Super Admin can update attendances in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if attendance belongs to that SACCO
            if (TenantContext::has()) {
                return $attendance->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't update any attendances
            return false;
        }

        // Regular users can only update attendances in their own SACCO
        return $attendance->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can delete the meeting attendance.
     */
    public function delete(User $user, MeetingAttendance $attendance)
    {
        // Super Admin can delete attendances in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if attendance belongs to that SACCO
            if (TenantContext::has()) {
                return $attendance->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't delete any attendances
            return false;
        }

        // Regular users can only delete attendances in their own SACCO
        return $attendance->sacco_id === $user->sacco_id;
    }
}