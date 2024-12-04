<?php

declare(strict_types=1);

namespace App\Events;

use App\Model\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public User|Authenticatable      $user,
        public string                    $title,
        public string                    $description,
        public null|User|Authenticatable $who = null,
        public ?Model                    $entity = null,
    )
    {
    }
}
