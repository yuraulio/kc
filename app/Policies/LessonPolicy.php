<?php

namespace App\Policies;

use App\Model\Lesson;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LessonPolicy
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

    public function update(User $user, Lesson $lesson)
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
