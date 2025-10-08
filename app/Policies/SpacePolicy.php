<?php

namespace App\Policies;

use App\Models\Space;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SpacePolicy
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
    public function view(User $user, Space $space): bool
    {
        if ($space->isPublic()) {
            return true;
        }

        return $space->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isEcStaff();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Space $space): bool
    {
        $membership = $space->memberships()->where('user_id', $user->id)->first();

        return $membership && ($membership->isOwner() || $membership->isModerator());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Space $space): bool
    {
        $membership = $space->memberships()->where('user_id', $user->id)->first();

        return $membership && $membership->isOwner();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Space $space): bool
    {
        return $user->isEcStaff();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Space $space): bool
    {
        return $user->isEcStaff();
    }

    /**
     * Determine whether the user can manage the space.
     */
    public function manage(User $user, Space $space): bool
    {
        $membership = $space->memberships()->where('user_id', $user->id)->first();

        return $membership && ($membership->isOwner() || $membership->isModerator());
    }
}
