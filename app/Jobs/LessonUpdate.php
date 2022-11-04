<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Topic;

class LessonUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $request;
    private $lesson;
    
    public function __construct($request,$lesson)
    {
        $this->request = $request;
        $this->lesson = $lesson;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $catsIds = [];

        foreach($this->request['topic_id'] as $topic)
        {
            $topic = Topic::with('category')->find($topic);
           
            foreach($topic->category as $cat){
                
                if($cat->id == $this->request['category']){
                    echo $cat->id;
                    continue;
                }
                
                if(in_array($cat->id,$catsIds)){
                    continue;
                }

                $catsIds[] = $cat->id;

                $allEvents = $cat->events;
                foreach($allEvents as $event)
                {
                    
                    $allLessons = $event->allLessons->groupBy('id');
                    
                    $date = '';
                    $time_starts = '';
                    $time_ends = '';
                    $duration = '';
                    $room = '';
                    $instructor_id = '';

                    if($existLesson = $event->allLessons()->wherePivot('topic_id',$topic->id)->wherePivot('lesson_id',$this->lesson->id)->first()){
                        $priority =  $existLesson->pivot->priority;
                    }else{
                        $priorityLesson = $event->allLessons()->wherePivot('topic_id',$topic->id)->orderBy('priority')->get();
                        $priority = isset($priorityLesson->last()['pivot']['priority']) ? $priorityLesson->last()['pivot']['priority'] + 1 :count($priorityLesson)+1 ;
                    }

                    if(isset($allLessons[$this->lesson['id']][0])){
                        $date = $allLessons[$this->lesson['id']][0]['pivot']['date'];
                        $time_starts = $allLessons[$this->lesson['id']][0]['pivot']['time_starts'];
                        $time_ends = $allLessons[$this->lesson['id']][0]['pivot']['time_ends'];
                        $duration = $allLessons[$this->lesson['id']][0]['pivot']['duration'];
                        $room = $allLessons[$this->lesson['id']][0]['pivot']['room'];
                        $instructor_id = $allLessons[$this->lesson['id']][0]['pivot']['instructor_id'];
                    }

                    $event->allLessons()->detach($this->lesson['id']);
                    $event->changeOrder($priority);

                    $event->topic()->attach($topic['id'],['lesson_id' => $this->lesson['id'],'date'=>$date,'time_starts'=>$time_starts,
                        'time_ends'=>$time_ends, 'duration' => $duration, 'room' => $room, 'instructor_id' => $instructor_id, 'priority' => $priority]);
                    $event->fixOrder();
                    
                    //$this->lesson->topic()->wherePivot('category_id',$this->request['category'])->detach();
                    //$cat->changeOrder($priority);
                    //$cat->topic()->attach($topic, ['lesson_id' => $this->lesson->id,'priority'=>$priority]);
                    //$cat->fixOrder();
                }

                $this->lesson->topic()->wherePivot('category_id',$this->request['category'])->detach();
                $cat->changeOrder($priority);
                $cat->topic()->attach($topic, ['lesson_id' => $this->lesson->id,'priority'=>$priority]);
                $cat->fixOrder();

            }
                
        }
    }
}
