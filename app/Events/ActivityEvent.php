<?php

declare(strict_types=1);

namespace App\Events;

use App\Model\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public User $user,
        public string $title,
        public string $description,
        public ?User $who = null
    )
    {
        //
    }
}
