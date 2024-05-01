<?php

namespace App\Policies;

use App\Model\Admin\Admin;
use App\Model\Admin\Page;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Model\Admin\Admin|\App\Model\User  $user
     *
     * @return bool
     */
    public function viewAny(Admin|User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Model\Admin\Admin|\App\Model\User  $user
     * @param  \App\Model\Admin\Page  $page
     *
     * @return bool
     */
    public function view(Admin|User $user, Page $page): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Model\Admin\Admin|\App\Model\User  $user
     *
     * @return bool
     */
    public function create(Admin|User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Model\Admin\Admin|\App\Model\User  $user
     * @param  \App\Model\Admin\Page  $page
     *
     * @return bool
     */
    public function update(Admin|User $user, Page $page): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Model\Admin\Admin|\App\Model\User  $user
     * @param  \App\Model\Admin\Page  $page
     *
     * @return bool
     */
    public function delete(Admin|User $user, Page $page): bool
    {
        return true;
    }

    /**
     * Determine whether the user can change published state of the model.
     *
     * @param  \App\Model\Admin\Admin|\App\Model\User  $user
     * @param  \App\Model\Admin\Page  $page
     *
     * @return bool
     */
    public function publish(Admin|User $user, Page $page): bool
    {
        return true;
    }

    /**
     * Determine whether the user can change upload image to the model.
     *
     * @param  \App\Model\Admin\Admin|\App\Model\User  $user
     *
     * @return bool
     */
    public function imgUpload(Admin|User $user): bool
    {
        return true;
    }
}
