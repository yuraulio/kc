<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Topic;
use App\Model\Lesson;
use App\Model\Category;
use App\Model\Event;

class OrderTopicsLessons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:general';

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
    public function handle()
    {
        $topics = [];
        $lessonss = [];
        $events = Event::where('published',true)->whereIn('status',[0,2,3])->get();

        foreach($events as $event){
            if(!$event->category->first()){
                continue;
            }
            $category = $event->category->first()->id;
            foreach($event->lessons->groupBy('topic_id') as $key => $lessons){

                foreach($lessons as $lesson){
                    if($lesson->pivot->priority){

                        //$lessonss[$category] = $lesson->pivot->priority;
                        $lessonss[$category][$lesson->pivot->topic_id][$lesson->id] = $lesson->pivot->priority;
                        $topics[$category][$lesson->pivot->topic_id] = $lesson->pivot->priority;

                    }

                }
            }

        }
        //dd($lessonss['183'][2]);
        foreach($lessonss as $categoryId => $topicsIds){
            $cat = Category::find($categoryId);
            $cat->lessons()->detach();
            foreach($topicsIds as $topicId => $lessonsIds){


                foreach($lessonsIds as $keyLes => $lessonId){
                   
                    $cat->lessons()->wherePivot('lesson_id',$keyLes)->detach();
                    $cat->lessons()->attach($keyLes,['topic_id'=>$topicId]);
                }
                
            }

        }
        
        $topicsAll = Topic::all();

        foreach($topicsAll as $topicAll){
            foreach($topicAll->lessonsCategory as $topic){
                if(!isset($lessonss[$topic->pivot->category_id][$topic->pivot->topic_id][$topic->pivot->lesson_id])){
                    continue;
                }/*else{
                    $topicAll->lessonsCategory()->attach($topic->pivot->lesson_id,['category_id'=>$topic->pivot->category_id]);
                }*/
                $topic->pivot->priority = $lessonss[$topic->pivot->category_id][$topic->pivot->topic_id][$topic->pivot->lesson_id];
                $topic->pivot->save();
                //dd($topic->pivot->priority);
            }

            foreach($topicAll->category as $category ){

                if(!isset($topics[$category->pivot->category_id][$category->pivot->categoryable_id])){
                    continue;
                }
                
                $category->pivot->priority = $topics[$category->pivot->category_id][$category->pivot->categoryable_id];
                $category->pivot->save();

            }
            //dd($topicAll->lessonsCategory)
        }

        return 0;
    }
}
