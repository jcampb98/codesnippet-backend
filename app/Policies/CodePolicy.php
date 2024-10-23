<?php

namespace App\Policies;

use App\Models\Code;
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

    public function update(User $user, Code $code) {
        return $user->id === $code->user_id;
    }

    public function destroy(User $user, Code $code) {
        return $user->id !== null;
    }
}
