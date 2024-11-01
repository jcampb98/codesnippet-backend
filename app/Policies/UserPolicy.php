<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function updateUser(User $user) {
        return $user->id !== null;
    }

    public function deleteUser(User $user) {
        return $user->id !== null;
    }
}
