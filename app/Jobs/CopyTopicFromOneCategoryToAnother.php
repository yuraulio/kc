<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CopyTopicFromOneCategoryToAnother implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $category;
    private $fromCategory;
    private $fromEvent;
    private $lessonsAttached;
    private $topic;

    public function __construct($category, $fromEvent,$fromCategory, $topic, &$lessonsAttached = [])
    {
        $this->category = $category;
        $this->fromEvent = $fromEvent;
        $this->fromCategory = $fromCategory;
        $this->lessonsAttached = $lessonsAttached;
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            
        foreach($this->category->events as $event){
            $lastPriority = count($event->allLessons()->get()) + 1;
                    
            foreach($this->topic->event_lesson()->wherePivot('event_id',$this->fromEvent)->orderBy('priority')->get() as $lesson){
                if(in_array($lesson->id,$this->lessonsAttached) || !in_array($this->fromCategory,$lesson->category->pluck('id')->toArray())){
                    continue;
                }
                
                $lessonsAttached[] = $lesson->id;
                $event->topic()->attach($this->topic,['event_id' => $event->id,
                                                'lesson_id' => $lesson->pivot->lesson_id, 
                                                'instructor_id' => $lesson->pivot->instructor_id,
                                                'date' => $lesson->pivot->date,
                                                'duration' => $lesson->pivot->duration,
                                                'time_starts' => $lesson->pivot->time_starts,
                                                'time_ends' => $lesson->pivot->time_ends,
                                                'priority' => $lastPriority,
                                                'automate_mail' => $lesson->pivot->automate_mail ? $lesson->pivot->automate_mail : false
                                                ]);
                $lastPriority += 1;
            }
        }
    }
}
