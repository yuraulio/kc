<?php

namespace App\Policies;

use App\Model\Topic;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Topic $topic)
    {
        return true;
    }

    public function publish(User $user)
    {
        return true;
    }

    public function delete(User $user)
    {
        return true;
    }
}
