<?php

namespace App\Console\Commands;

use App\Model\Category;
use App\Model\Event;
use Illuminate\Console\Command;

class FixOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    /*public function handle()
    {
        $masterEvent = Event::find(2304);
        $events = Event::whereIn('id',[4628,4627,4626,4625,4624,4623,4622,4621])->get();

        $lessonsOrder = [];

        foreach($masterEvent->lessons as $lesson){
            $lessonsOrder[$lesson->id] = $lesson->pivot->priority;
        }


        foreach($events as $event){
            $category = Category::find($event->category->first()->id);


            foreach($lessonsOrder as $key => $leso){
                $event->allLessons()->wherePivot('lesson_id', $key)->updateExistingPivot($key, ['priority' =>  $leso],false);
                $category->lessons()->wherePivot('lesson_id',$key)->updateExistingPivot($key, ['priority' =>  $leso],false);
            }



        }


        return 0;
    }*/

    public function handle()
    {
        /*$events = Event::
        whereHas('event_info1',function($query){
            $query->where('course_delivery',143);
        })
        ->get();*/

        $events = Event::where('id', 4632)
        ->get();

        foreach ($events as $event) {
            $event->fixOrder();

            $category = $event->category->first();

            foreach ($event->allLessons()->orderBy('priority')->get() as  $pLesson) {
                $topicId = $pLesson->pivot->topic_id;
                $lessonId = $pLesson->pivot->lesson_id;
                $priority = $pLesson->pivot->priority;

                if ($lessonCategory = $category->lessons()->wherePivot('topic_id', $topicId)->wherePivot('lesson_id', $lessonId)->first()) {
                    $lessonCategory->pivot->priority = $priority;
                    $lessonCategory->pivot->save();
                } else {
                    $category->topic()->attach($topicId, ['lesson_id' => $lessonId, 'priority'=>$priority]);
                }
            }
        }

        $categories = Category::all();

        foreach ($categories as $category) {
            $category->fixOrder();
        }

        return 0;
    }
}
