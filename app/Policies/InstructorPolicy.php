<?php

namespace App\Policies;

use App\Instructor;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstructorPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Instructor  $instructor
     * @return mixed
     */
    public function view(User $user, Instructor $instructor)
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
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Instructor  $instructor
     * @return mixed
     */
    public function update(User $user, Instructor $instructor)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Instructor  $instructor
     * @return mixed
     */
    public function delete(User $user, Instructor $instructor)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Instructor  $instructor
     * @return mixed
     */
    public function restore(User $user, Instructor $instructor)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Instructor  $instructor
     * @return mixed
     */
    public function forceDelete(User $user, Instructor $instructor)
    {
        //
    }
}
