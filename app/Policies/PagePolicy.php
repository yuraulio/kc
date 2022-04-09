<?php

namespace App\Policies;

use App\Model\Admin\Admin;
use App\Model\Admin\Page;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Admin $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $user, Page $page)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Admin $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $user, Page $page)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $user, Page $page)
    {
        return true;
    }

    /**
     * Determine whether the user can change published state of the model.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function publish(Admin $user, Page $page)
    {
        return true;
    }

    /**
     * Determine whether the user can change upload image to the model.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function imgUpload(Admin $user)
    {
        return true;
    }
}
