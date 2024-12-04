<?php

namespace App\Policies;

use App\Model\Delivery;
use App\Model\Event;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Delivery $delivery)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Event $event)
    {
        return true;
    }

    public function publish(User $user, Delivery $delivery)
    {
        return true;
    }

    public function delete(User $user, Delivery $delivery)
    {
        return true;
    }
}
