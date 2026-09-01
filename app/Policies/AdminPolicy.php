<?php

namespace App\Policies;

use App\Models\User;

/**
 * A single content policy backs every CMS resource: editors and admins manage
 * content, only super admins touch users and security.
 */
class AdminPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageContent();
    }

    public function view(User $user): bool
    {
        return $user->canManageContent();
    }

    public function create(User $user): bool
    {
        return $user->canManageContent();
    }

    public function update(User $user): bool
    {
        return $user->canManageContent();
    }

    public function delete(User $user): bool
    {
        return $user->canManageContent();
    }
}
