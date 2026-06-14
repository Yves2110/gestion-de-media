<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function manageAdmins(User $user): bool
    {
        return $user->role_id === 1;
    }

    public function update(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->role_id === 1) {
            return in_array($targetUser->role_id, [1, 2], true);
        }

        return $currentUser->id === $targetUser->id;
    }

    public function delete(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->id === $targetUser->id) {
            return false;
        }

        return $currentUser->role_id === 1 && in_array($targetUser->role_id, [1, 2], true);
    }

    public function toggleStatus(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->id === $targetUser->id) {
            return false;
        }

        return $currentUser->role_id === 1 && in_array($targetUser->role_id, [1, 2], true);
    }
}
