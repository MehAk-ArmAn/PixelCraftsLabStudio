<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageSecurity();
    }

    public function view(User $user): bool
    {
        return $user->canManageSecurity();
    }

    public function create(User $user): bool
    {
        return $user->canManageSecurity();
    }

    public function update(User $user): bool
    {
        return $user->canManageSecurity();
    }

    public function delete(User $user, User $target): bool
    {
        return $user->canManageSecurity() && $user->isNot($target);
    }
}
