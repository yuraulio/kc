<?php

namespace App\Policies;

use App\Model\Admin\Admin;
use App\Model\Admin\Comment;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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
     * @param  \App\Model\Admin\Comment $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $user, Comment $comment)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Model\Admin\Comment $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $user, Comment $comment)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Model\Admin\Admin  $user
     * @param  \App\Model\Admin\Comment $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $user, Comment $comment)
    {
        return true;
    }
}
