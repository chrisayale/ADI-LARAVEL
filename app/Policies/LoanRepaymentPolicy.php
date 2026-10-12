<?php

namespace App\Policies;

use App\Models\LoanRepayment;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanRepaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any loan repayments.
     * Super Admin can view repayments in their selected SACCO context.
     * Regular users can only view repayments in their own SACCO.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view repayments in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can only view repayments in their own SACCO
        return TenantContext::has();
    }

    /**
     * Determine if the user can view the loan repayment.
     */
    public function view(User $user, LoanRepayment $repayment)
    {
        // Super Admin can view repayments in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if repayment belongs to that SACCO
            if (TenantContext::has()) {
                return $repayment->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't view any repayments
            return false;
        }

        // Regular users can only view repayments in their own SACCO
        return $repayment->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can create loan repayments.
     */
    public function create(User $user)
    {
        // Super Admin can create repayments in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can create repayments in their own SACCO
        return !$user->hasRole('super_admin'); // Anyone except super_admin can create repayments
    }

    /**
     * Determine if the user can update the loan repayment.
     */
    public function update(User $user, LoanRepayment $repayment)
    {
        // Super Admin can update repayments in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if repayment belongs to that SACCO
            if (TenantContext::has()) {
                return $repayment->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't update any repayments
            return false;
        }

        // Regular users can only update repayments in their own SACCO
        // And only if they are the borrower associated with the loan
        return $repayment->sacco_id === $user->sacco_id &&
               $repayment->loanApplication->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the loan repayment.
     */
    public function delete(User $user, LoanRepayment $repayment)
    {
        // Super Admin can delete repayments in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if repayment belongs to that SACCO
            if (TenantContext::has()) {
                return $repayment->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't delete any repayments
            return false;
        }

        // Regular users can only delete repayments in their own SACCO
        // And only if they are the borrower associated with the loan
        return $repayment->sacco_id === $user->sacco_id &&
               $repayment->loanApplication->user_id === $user->id;
    }
}