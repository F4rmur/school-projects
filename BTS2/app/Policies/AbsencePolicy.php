<?php

namespace App\Policies;

use App\Models\absence;
use App\Models\User;

class AbsencePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, absence $absence): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, absence $absence): bool
    {
        return $user->isAdmin() || $user->getKey() === $absence->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, absence $absence): bool
    {
        return $user->isAdmin() || $user->getKey() === $absence->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, absence $absence): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, absence $absence): bool
    {
        return false;
    }
}
