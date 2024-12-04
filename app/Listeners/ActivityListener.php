<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ActivityEvent;
use App\Model\UserActivity;

class ActivityListener
{
    public function handle(ActivityEvent $event): void
    {
        $activity = new UserActivity(['title' => $event->title, 'description' => $event->description]);
        $activity->user()->associate($event->user);
        $activity->who()->associate($event->who ?? null);
        $activity->entity()->associate($event->entity ?? null);
        $activity->save();
    }
}
