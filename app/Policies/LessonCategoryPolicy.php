<?php

namespace App\Policies;

use App\Model\LessonCategory;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LessonCategoryPolicy
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

    public function update(User $user, LessonCategory $category): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, LessonCategory $category): Response|bool
    {
        return $user->isAdmin() || $user->isCreator();
    }
}
