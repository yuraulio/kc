<?php

declare(strict_types=1);

namespace App\Policies;

use App\Model\Tag;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
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

    public function update(User $user, Tag $tag): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $user->isAdmin() || $user->isCreator();
    }
}
