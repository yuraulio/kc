<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Event;

class SetAutomateEmailStatusForTopics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $event = Event::find($this->data['event_id']);

        if(!$event){
            return 0;
        }

        foreach($event->lessons()->wherePivot('topic_id',$this->data['topic_id'])->get() as $lesson){
            $lesson->pivot->automate_mail = $this->data['status'];
            $lesson->pivot->save();
        }

        
    }
}
