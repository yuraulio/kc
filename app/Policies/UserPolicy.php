<?php

namespace App\Policies;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can see the users.
     *
     * @param  \App\Model\User  $user
     * @return boolean
     */
    public function viewAny(User $user)
    {
       
        return $user->isAdmin();
    }

    /**
     * Determine whether the authenticate user can create users.
     *
     * @param  \App\Model\User $user
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the authenticate user can update the user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\User  $model
     * @return boolean
     */
    public function update(User $user, User $model)
    {
        return $user->isAdmin() || $model->id == $user->id;
    }

    /**
     * Determine whether the authenticate user can delete the user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\User  $model
     * @return boolean
     */
    public function delete(User $user, User $model) {
        // return $user->isAdmin() && $user->id != $model->id;
        return $user->isAdmin();
    }

    /**
     * Determine whether the authenticate user can manage other users.
     *
     * @param  \App\Model\User  $user
     * @return boolean
     */
    public function manageUsers(User $user)
    {
        $roles = $user->role->pluck('name')->toArray();
        return (in_array('Super Administrator',$roles) || in_array('Administrator',$roles) || in_array('Manager',$roles) || 
                        in_array('Author',$roles));
    }

}
