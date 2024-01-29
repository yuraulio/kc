<?php

namespace App\Policies;

use App\Model\User;
use App\Transaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
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
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        $roles = $user->role->pluck('name')->toArray();

        return in_array('Super Administrator', $roles) || in_array('Administrator', $roles) || in_array('Manager', $roles) ||
                        in_array('Author', $roles) || in_array('Knowcrunch Partner', $roles);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Transaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Transaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Transaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Transaction $transaction)
    {
        //
    }
}
