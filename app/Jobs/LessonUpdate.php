<?php

namespace App\Jobs;

use App\Model\Lesson;
use App\Model\Topic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LessonUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $data;
    private $lesson;

    public function __construct(array $data, Lesson $lesson)
    {
        $this->data = $data;
        $this->lesson = $lesson;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data['topic_id'] as $topic) {
            $catsIds = [];
            $topic = Topic::with('category')->find($topic);

            foreach ($topic->category as $cat) {
                if ($cat->id != $this->data['category']) {
                    continue;
                }

                if (in_array($cat->id, $catsIds)) {
                    //continue;
                }

                $catsIds[] = $cat->id;

                $allEvents = $cat->events;
                /*$allEvents = $cat->events()->whereHas('event_info1',function($query){
                    $query->where('course_delivery',143);
                })->get();*/

                $priority = 0;

                foreach ($allEvents as $event) {
                    add_event_statistic_queue($event->id);

                    $allLessons = $event->allLessons->groupBy('id');

                    $date = '';
                    $time_starts = null;
                    $time_ends = '';
                    $duration = '';
                    $room = '';
                    $instructor_id = '';
                    $automate_mail = false;
                    $send_automate_mail = false;

                    if ($existLesson = $event->allLessons()->wherePivot('topic_id', $topic->id)->wherePivot('lesson_id', $this->lesson->id)->first()) {
                        $priority = $existLesson->pivot->priority;
                    } else {
                        $priorityLesson = $event->allLessons()->wherePivot('topic_id', $topic->id)->orderBy('priority')->get();
                        $priority = isset($priorityLesson->last()['pivot']['priority']) ? $priorityLesson->last()['pivot']['priority'] + 1 : count($priorityLesson) + 1;
                    }

                    if (isset($allLessons[$this->lesson['id']][0])) {
                        $date = $allLessons[$this->lesson['id']][0]['pivot']['date'];
                        $time_starts = $allLessons[$this->lesson['id']][0]['pivot']['time_starts'];
                        $time_ends = $allLessons[$this->lesson['id']][0]['pivot']['time_ends'];
                        $duration = $allLessons[$this->lesson['id']][0]['pivot']['duration'];
                        $room = $allLessons[$this->lesson['id']][0]['pivot']['room'];
                        $instructor_id = $allLessons[$this->lesson['id']][0]['pivot']['instructor_id'];
                        $automate_mail = $allLessons[$this->lesson['id']][0]['pivot']['automate_mail'];
                        $send_automate_mail = $allLessons[$this->lesson['id']][0]['pivot']['send_automate_mail'];
                    }

                    $event->allLessons()->detach($this->lesson['id']);
                    $event->changeOrder($priority);

                    $event->topic()->attach($topic['id'], [
                        'lesson_id' => $this->lesson['id'], 'date' => $date, 'time_starts' => $time_starts,
                        'time_ends' => $time_ends, 'duration' => $duration, 'room' => $room, 'instructor_id' => $instructor_id, 'priority' => $priority,
                        'automate_mail' => $automate_mail, 'send_automate_mail' => $send_automate_mail,
                    ]);
                    $event->fixOrder();

                    $this->lesson->topic()->wherePivot('category_id', $this->data['category'])->detach();
                    $cat->changeOrder($priority);
                    $cat->topic()->attach($topic, ['lesson_id' => $this->lesson->id, 'priority' => $priority]);
                    $cat->fixOrder();

                    $event->resetCache();
                }

                //$this->lesson->topic()->wherePivot('category_id',$this->data['category'])->detach();
                //$cat->changeOrder($priority);
                //$cat->topic()->attach($topic, ['lesson_id' => $this->lesson->id,'priority'=>$priority]);
                //$cat->fixOrder();
            }
        }
    }
}
