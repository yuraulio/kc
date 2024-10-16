<?php

namespace App\Policies;

use App\Model\Role;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Role $role): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Role $role): Response|bool
    {
        $isInUse = User::query()
            ->whereHas('roles', function ($q) use ($role) {
                $q->where('roles.id', $role->id);
            })->exists();

        if ($isInUse) {
            return $this->deny("You can't remove role which have some user.");
        }

        return $user->isAdmin() || $user->isCreator();
    }
}
