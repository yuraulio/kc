<?php

namespace App\Policies;

use App\User;
use App\Model\City;
use Illuminate\Auth\Access\HandlesAuthorization;

class CityPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isCreator();
    }

    public function create(User $user)
    {
        return ($user->isAdmin() || $user->isCreator());
    }

    public function update(User $user, City $city)
    {
        return ($user->isAdmin() || $user->isCreator());
    }

    public function delete(User $user, City $city)
    {
        return ($user->isAdmin() || $user->isCreator());
    }
}
