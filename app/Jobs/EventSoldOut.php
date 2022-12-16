<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Event;

class EventSoldOut implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $event;

    public function __construct($event)
    {
        $this->event = Event::with('ticket')->find($event);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->event){
            Event::where('id', $this->event->id)->update([
                'index' => 0,
                'feed' => 0,
            ]);

            foreach($this->event['ticket'] as $ticket){

                $this->event->ticket()->updateExistingPivot($ticket->id, ['quantity' => 0]);
            }

        }
    }
}
