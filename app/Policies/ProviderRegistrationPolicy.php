<?php

namespace App\Policies;

use App\Models\ProviderRegistration;
use App\Models\User;

class ProviderRegistrationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isEcStaff();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProviderRegistration $providerRegistration): bool
    {
        return $user->isEcStaff() || $user->id === $providerRegistration->submitted_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can submit a registration
    }

    /**
     * Determine whether the user can update the model (approve/reject).
     */
    public function update(User $user, ProviderRegistration $providerRegistration): bool
    {
        return $user->isEcStaff() && $providerRegistration->isPending();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProviderRegistration $providerRegistration): bool
    {
        return $user->isEcStaff();
    }
}
