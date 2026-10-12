<?php

namespace App\Policies;

use App\Models\PaymentTransaction;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentTransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any payment transactions.
     * Super Admin can view transactions in their selected SACCO context.
     * Regular users can only view transactions in their own SACCO.
     */
    public function viewAny(User $user)
    {
        // Super Admin can view transactions in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can only view transactions in their own SACCO
        return TenantContext::has();
    }

    /**
     * Determine if the user can view the payment transaction.
     */
    public function view(User $user, PaymentTransaction $transaction)
    {
        // Super Admin can view transactions in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if transaction belongs to that SACCO
            if (TenantContext::has()) {
                return $transaction->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't view any transactions
            return false;
        }

        // Regular users can only view transactions in their own SACCO
        return $transaction->sacco_id === $user->sacco_id;
    }

    /**
     * Determine if the user can create payment transactions.
     */
    public function create(User $user)
    {
        // Super Admin can create transactions in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            return TenantContext::has(); // Must have a SACCO context selected
        }

        // Regular users can create transactions in their own SACCO
        return !$user->hasRole('super_admin'); // Anyone except super_admin can create transactions
    }

    /**
     * Determine if the user can update the payment transaction.
     */
    public function update(User $user, PaymentTransaction $transaction)
    {
        // Super Admin can update transactions in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if transaction belongs to that SACCO
            if (TenantContext::has()) {
                return $transaction->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't update any transactions
            return false;
        }

        // Regular users can only update transactions in their own SACCO
        // And only if they are the initiator of the transaction
        return $transaction->sacco_id === $user->sacco_id &&
               $transaction->initiated_by_user_id === $user->id;
    }

    /**
     * Determine if the user can delete the payment transaction.
     */
    public function delete(User $user, PaymentTransaction $transaction)
    {
        // Super Admin can delete transactions in their selected SACCO context
        if ($user->hasRole('super_admin')) {
            // If Super Admin has a SACCO context, check if transaction belongs to that SACCO
            if (TenantContext::has()) {
                return $transaction->sacco_id === TenantContext::id();
            }
            // If no SACCO context, they can't delete any transactions
            return false;
        }

        // Regular users can only delete transactions in their own SACCO
        // And only if they are the initiator of the transaction
        return $transaction->sacco_id === $user->sacco_id &&
               $transaction->initiated_by_user_id === $user->id;
    }
}