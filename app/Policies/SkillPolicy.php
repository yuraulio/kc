<?php

namespace App\Policies;

use App\Model\Skill;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SkillPolicy
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

    public function update(User $user, Skill $skill): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Skill $skill): Response|bool
    {
        return $user->isAdmin() || $user->isCreator();
    }
}
