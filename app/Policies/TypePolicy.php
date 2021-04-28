<?php

namespace App\Policies;

use App\Type;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return ($user->isAdmin() || $user->isCreator());
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function view(User $user, Type $type)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ($user->isAdmin() || $user->isCreator());
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function update(User $user, Type $type)
    {
        return ($user->isAdmin() || $user->isCreator());
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function delete(User $user, Type $type)
    {
        return ($user->isAdmin() || $user->isCreator());
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function restore(User $user, Type $type)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function forceDelete(User $user, Type $type)
    {
        //
    }
}
