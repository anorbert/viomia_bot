<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $auth, User $user): bool
    {
        // Only admins (role_id === 1) can update users
        return $auth->role_id === 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $auth, User $user): bool
    {
        // Only admins (role_id === 1) can delete users
        // Admins cannot delete themselves
        return $auth->role_id === 1 && $auth->id !== $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $auth, User $user): bool
    {
        // Only admins (role_id === 1) can restore users
        return $auth->role_id === 1;
    }
}
