<?php

namespace App\Policies;

use App\Models\LoanDocument;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanDocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any loan documents.
     * Super Admin can view documents in their selected SACCO context.
     * Regular users can only view documents in their own SACCO.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view documents in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can only view documents in their own SACCO
        return TenantContext::has();
    }

    /**
     * Determine if the user can view the loan document.
     */
    public function view(User $user, LoanDocument $document)
    {
        // Super Admin can view documents in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if document belongs to that SACCO
            if (TenantContext::has()) {
                return $document->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't view any documents
            return false;
        }

        // Regular users can only view documents in their own SACCO
        return $document->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can create loan documents.
     */
    public function create(User $user)
    {
        // Super Admin can create documents in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can create documents in their own SACCO
        return !$user->hasRole('super_admin'); // Anyone except super_admin can create documents
    }

    /**
     * Determine if the user can update the loan document.
     */
    public function update(User $user, LoanDocument $document)
    {
        // Super Admin can update documents in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if document belongs to that SACCO
            if (TenantContext::has()) {
                return $document->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't update any documents
            return false;
        }

        // Regular users can only update documents in their own SACCO
        // And only if they are the user associated with the loan
        return $document->sacco_id === $user->sacco_id &&
               $document->loan_application->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the loan document.
     */
    public function delete(User $user, LoanDocument $document)
    {
        // Super Admin can delete documents in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if document belongs to that SACCO
            if (TenantContext::has()) {
                return $document->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't delete any documents
            return false;
        }

        // Regular users can only delete documents in their own SACCO
        // And only if they are the user associated with the loan
        return $document->sacco_id === $user->sacco_id &&
               $document->loan_application->user_id === $user->id;
    }
}