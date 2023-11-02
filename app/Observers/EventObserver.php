<?php

namespace App\Observers;

use App\Model\Event;
use Illuminate\Support\Facades\Cache;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        //
    }

    /**
     * Handle the Event "updated" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        Cache::forget('topics-event-status-'.$event->id);
    }

    /**
     * Handle the Event "deleted" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function deleted(Event $event)
    {
        Cache::forget('topics-event-status-'.$event->id);
    }

    /**
     * Handle the Event "restored" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function restored(Event $event)
    {
        Cache::forget('topics-event-status-'.$event->id);
    }

    /**
     * Handle the Event "force deleted" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function forceDeleted(Event $event)
    {
        Cache::forget('topics-event-status-'.$event->id);
    }
}
