<?php

namespace App\Policies;

use App\Models\User;

/**
 * Advanced Resource Authorization Policy
 * 
 * Provides granular control over who can perform actions on resources
 * Admins can perform all actions
 * Users can only access their own resources
 */
class UserResourcePolicy
{
    /**
     * Determine if the given user can view the resource
     */
    public function view(User $user, User $resource): bool
    {
        // Admins can view any user resource
        if ($user->role_id === 1) {
            return true;
        }

        // Users can only view their own resources
        return $user->id === $resource->id || $user->uuid === $resource->uuid;
    }

    /**
     * Determine if the given user can update the resource
     */
    public function update(User $user, User $resource): bool
    {
        // Admins can update any resource
        if ($user->role_id === 1) {
            return true;
        }

        // Users can only update their own resources
        return $user->id === $resource->id || $user->uuid === $resource->uuid;
    }

    /**
     * Determine if the given user can delete the resource
     */
    public function delete(User $user, User $resource): bool
    {
        // Only admins can delete user resources
        if ($user->role_id === 1) {
            return true;
        }

        // Regular users cannot delete anything (including themselves)
        return false;
    }

    /**
     * Determine if the given user can restore a soft-deleted resource
     */
    public function restore(User $user, User $resource): bool
    {
        // Only admins can restore resources
        return $user->role_id === 1;
    }

    /**
     * Determine if the given user can permanently delete a resource
     */
    public function forceDelete(User $user, User $resource): bool
    {
        // Only admins can force delete
        return $user->role_id === 1;
    }

    /**
     * Prevent admin users from modifying their own role or permissions
     */
    public function modifyRole(User $user, User $resource): bool
    {
        // Only superadmin (role_id = 1) can modify roles
        if ($user->role_id !== 1) {
            return false;
        }

        // Prevent accidental self-demotion
        if ($user->id === $resource->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine if user can access subscription data of a resource
     */
    public function viewSubscription(User $user, User $resource): bool
    {
        // Admins can view any subscription
        if ($user->role_id === 1) {
            return true;
        }

        // Users can only view their own subscriptions
        return $user->id === $resource->id || $user->uuid === $resource->uuid;
    }

    /**
     * Determine if user can access payment info of a resource
     */
    public function viewPayments(User $user, User $resource): bool
    {
        // Admins can view all payments
        if ($user->role_id === 1) {
            return true;
        }

        // Users can only view their own payment records
        return $user->id === $resource->id || $user->uuid === $resource->uuid;
    }

    /**
     * Determine if user can access trading account data
     */
    public function viewAccounts(User $user, User $resource): bool
    {
        // Admins can view all trading accounts
        if ($user->role_id === 1) {
            return true;
        }

        // Users can only view their own trading accounts
        return $user->id === $resource->id || $user->uuid === $resource->uuid;
    }
}
