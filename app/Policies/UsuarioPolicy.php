<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsuarioPolicy {
    
    use HandlesAuthorization;

    /**
     * Determines if the user can create a new user
     * @param \App\Models\User $user
     * @return bool
     */
    public function createUser(User $user) {
        return $user->role === 'admin';
    }

    /**
     * Determines if the user can delete a user
     * @param \App\Models\User $user
     * @return bool
     */
    public function deleteUser(User $user) {
        return $user->role === 'admin';
    }


}
