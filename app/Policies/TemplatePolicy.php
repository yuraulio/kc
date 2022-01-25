<?php

namespace App\Policies;

use App\Model\User;
use App\Model\Admin\Template;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
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
     * @param  \App\Template  $template
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Template $template)
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
     * @param  \App\Template  $template
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Template $template)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Template  $template
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Template $template)
    {
        return true;
    }
}
