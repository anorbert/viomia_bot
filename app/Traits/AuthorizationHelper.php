<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Authorization Helper Trait
 * 
 * Provides convenient methods for checking user roles and permissions
 * Can be used in controllers, models, or services
 * 
 * Usage:
 * - $this->isAdmin() → Check if current user is admin
 * - $this->isUser() → Check if current user is regular user
 * - $this->isEditor() → Check if current user is editor
 * - $this->ownsResource($userId) → Check if user owns a specific resource
 */
trait AuthorizationHelper
{
    /**
     * Check if the current authenticated user is an admin
     */
    public function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role_id === 1;
    }

    /**
     * Check if the current authenticated user is a regular user (not admin)
     */
    public function isRegularUser(): bool
    {
        return Auth::check() && Auth::user()->role_id === 3;
    }

    /**
     * Check if the current authenticated user is an editor
     */
    public function isEditor(): bool
    {
        return Auth::check() && Auth::user()->role_id === 2;
    }

    /**
     * Check if the current user owns a specific resource
     * 
     * @param int|string $userId The user ID or UUID to check ownership
     * @return bool
     */
    public function ownsResource($userId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        return $user->id === $userId || $user->uuid === $userId;
    }

    /**
     * Check if current user can access admin resources
     */
    public function canAccessAdmin(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if current user can access user resources
     */
    public function canAccessUserResources(): bool
    {
        return !$this->isAdmin();
    }

    /**
     * Get the current user's role name
     */
    public function getCurrentUserRole(): string
    {
        if (!Auth::check()) {
            return 'guest';
        }

        return match(Auth::user()->role_id) {
            1 => 'admin',
            2 => 'editor',
            3 => 'user',
            default => 'unknown'
        };
    }

    /**
     * Assert that current user is admin, throw exception if not
     * 
     * @throws \Illuminate\Auth\AuthorizationException
     */
    public function assertIsAdmin(): void
    {
        if (!$this->isAdmin()) {
            abort(403, 'This action requires administrator privileges.');
        }
    }

    /**
     * Assert that current user is regular user, throw exception if not
     * 
     * @throws \Illuminate\Auth\AuthorizationException
     */
    public function assertIsUser(): void
    {
        if (!$this->isRegularUser()) {
            abort(403, 'This action is only available to regular users.');
        }
    }

    /**
     * Assert that current user owns the resource, throw exception if not
     * 
     * @param int|string $userId
     * @throws \Illuminate\Auth\AuthorizationException
     */
    public function assertOwnsResource($userId): void
    {
        if (!$this->ownsResource($userId)) {
            abort(403, 'You do not have permission to access this resource.');
        }
    }
}
