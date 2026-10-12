<?php

namespace App\Policies;

use App\Models\LoanApplication;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any loan applications.
     * Super Admin can view applications in their selected SACCO context.
     * Regular users can only view applications in their own SACCO.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view applications in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can only view applications in their own SACCO
        return TenantContext::has();
    }

    /**
     * Determine if the user can view the loan application.
     */
    public function view(User $user, LoanApplication $application)
    {
        // Super Admin can view applications in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if application belongs to that SACCO
            if (TenantContext::has()) {
                return $application->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't view any applications
            return false;
        }

        // Regular users can only view applications in their own SACCO
        return $application->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can create loan applications.
     */
    public function create(User $user)
    {
        // Super Admin can create applications in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can create applications in their own SACCO
        return !$user->hasRole('super_admin'); // Anyone except super_admin can create applications
    }

    /**
     * Determine if the user can update the loan application.
     */
    public function update(User $user, LoanApplication $application)
    {
        // Super Admin can update applications in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if application belongs to that SACCO
            if (TenantContext::has()) {
                return $application->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't update any applications
            return false;
        }

        // Regular users can only update applications in their own SACCO
        // And only if they are the applicant
        return $application->sacco_id === $user->sacco_id &&
               $application->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the loan application.
     */
    public function delete(User $user, LoanApplication $application)
    {
        // Super Admin can delete applications in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if application belongs to that SACCO
            if (TenantContext::has()) {
                return $application->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't delete any applications
            return false;
        }

        // Regular users can only delete applications in their own SACCO
        // And only if they are the applicant
        return $application->sacco_id === $user->sacco_id &&
               $application->user_id === $user->id;
    }
}