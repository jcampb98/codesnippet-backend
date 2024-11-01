<?php

namespace App\Policies;

use App\Models\User;

class CodePolicy
{
    /**
     * Determine if the given user can create a code snippet.
     * 
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user) {
        return $user->id !== null;
    }

    public function showAll(User $user) {
        return $user->id !== null;
    }

    public function update(User $user) {
        return $user->id !== null;
    }

    public function destroy(User $user) {
        return $user->id !== null;
    }
}
