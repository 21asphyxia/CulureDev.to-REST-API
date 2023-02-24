<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user): bool
    {
        // return true;    
        return $user->role->name === 'admin';
    }
    
    /**
     * Determine whether the user can update the role.
     */

    public function updateRole(User $user, User $editableUser)
    {
        return $user->role->name === 'admin'&& $editableUser->role->name !== 'admin';
    }
}
