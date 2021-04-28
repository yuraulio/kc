<?php

namespace App\Policies;

use App\Pages;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagesPolicy
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
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pages  $pages
     * @return mixed
     */
    public function view(User $user, Pages $pages)
    {
        //
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pages  $pages
     * @return mixed
     */
    public function update(User $user, Pages $pages)
    {
        //
        return $user->isAdmin() || $model->id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pages  $pages
     * @return mixed
     */
    public function delete(User $user, Pages $pages)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pages  $pages
     * @return mixed
     */
    public function restore(User $user, Pages $pages)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pages  $pages
     * @return mixed
     */
    public function forceDelete(User $user, Pages $pages)
    {
        //
    }
}
