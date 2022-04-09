<?php

namespace App\Policies;

use App\Model\Admin\Admin;
use App\Model\Admin\Template;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
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
     * @param  \App\Template  $template
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $user, Template $template)
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
     * @param  \App\Template  $template
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $user, Template $template)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Template  $template
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $user, Template $template)
    {
        return true;
    }
}
