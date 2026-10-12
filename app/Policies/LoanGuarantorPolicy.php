<?php

namespace App\Policies;

use App\Models\LoanGuarantor;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanGuarantorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any loan guarantors.
     * Super Admin can view guarantors in their selected SACCO context.
     * Regular users can only view guarantors in their own SACCO.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view guarantors in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can only view guarantors in their own SACCO
        return TenantContext::has();
    }

    /**
     * Determine if the user can view the loan guarantor.
     */
    public function view(User $user, LoanGuarantor $guarantor)
    {
        // Super Admin can view guarantors in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if guarantor belongs to that SACCO
            if (TenantContext::has()) {
                return $guarantor->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't view any guarantors
            return false;
        }

        // Regular users can only view guarantors in their own SACCO
        return $guarantor->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can create loan guarantors.
     */
    public function create(User $user)
    {
        // Super Admin can create guarantors in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can create guarantors in their own SACCO
        return !$user->hasRole('super_admin'); // Anyone except super_admin can create guarantors
    }

    /**
     * Determine if the user can update the loan guarantor.
     */
    public function update(User $user, LoanGuarantor $guarantor)
    {
        // Super Admin can update guarantors in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if guarantor belongs to that SACCO
            if (TenantContext::has()) {
                return $guarantor->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't update any guarantors
            return false;
        }

        // Regular users can only update guarantors in their own SACCO
        // And only if they are the guarantor
        return $guarantor->sacco_id === $user->sacco_id &&
               $guarantor->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the loan guarantor.
     */
    public function delete(User $user, LoanGuarantor $guarantor)
    {
        // Super Admin can delete guarantors in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if guarantor belongs to that SACCO
            if (TenantContext::has()) {
                return $guarantor->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't delete any guarantors
            return false;
        }

        // Regular users can only delete guarantors in their own SACCO
        // And only if they are the guarantor
        return $guarantor->sacco_id === $user->sacco_id &&
               $guarantor->user_id === $user->id;
    }
}