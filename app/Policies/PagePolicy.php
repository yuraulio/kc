<?php

namespace App\Policies;

use App\Model\User;
use App\Model\Admin\Page;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Page $page)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Page $page)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Page $page)
    {
        return true;
    }

    /**
     * Determine whether the user can change published state of the model.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function publish(User $user, Page $page)
    {
        return true;
    }

    /**
     * Determine whether the user can change upload image to the model.
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function imgUpload(User $user)
    {
        return true;
    }
}
