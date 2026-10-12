<?php

namespace App\Policies;

use App\Models\DefaulterTracking;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaulterTrackingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any defaulter tracking records.
     * Super Admin can view records in their selected SACCO context.
     * Regular users can only view records in their own SACCO.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view records in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can only view records in their own SACCO
        return TenantContext::has();
    }

    /**
     * Determine if the user can view the defaulter tracking record.
     */
    public function view(User $user, DefaulterTracking $record)
    {
        // Super Admin can view records in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if record belongs to that SACCO
            if (TenantContext::has()) {
                return $record->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't view any records
            return false;
        }

        // Regular users can only view records in their own SACCO
        return $record->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can create defaulter tracking records.
     */
    public function create(User $user)
    {
        // Super Admin can create records in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can create records in their own SACCO
        return !$user->hasRole('super_admin'); // Anyone except super_admin can create records
    }

    /**
     * Determine if the user can update the defaulter tracking record.
     */
    public function update(User $user, DefaulterTracking $record)
    {
        // Super Admin can update records in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if record belongs to that SACCO
            if (TenantContext::has()) {
                return $record->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't update any records
            return false;
        }

        // Regular users can only update records in their own SACCO
        // And only if they are the user associated with the record
        return $record->sacco_id === $user->sacco_id &&
               $record->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the defaulter tracking record.
     */
    public function delete(User $user, DefaulterTracking $record)
    {
        // Super Admin can delete records in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if record belongs to that SACCO
            if (TenantContext::has()) {
                return $record->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't delete any records
            return false;
        }

        // Regular users can only delete records in their own SACCO
        // And only if they are the user associated with the record
        return $record->sacco_id === $user->sacco_id &&
               $record->user_id === $user->id;
    }
}