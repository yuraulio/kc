<?php

namespace App\Console\Commands;

use App\Model\Category;
use App\Model\Event;
use App\Model\Lesson;
use App\Model\Topic;
use Illuminate\Console\Command;

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
        $events = Event::where('published', true)->whereIn('status', [0, 2, 3, 4])->get();

        foreach ($events as $event) {
            if (!$event->category->first()) {
                continue;
            }
            $category = $event->category->first()->id;
            //$event->category->first()->lessons()->detach();
            foreach ($event->lessons->groupBy('topic_id') as $key => $lessons) {
                foreach ($lessons as $lesson) {
                    if ($lesson->pivot) {
                        //$lessonss[$category] = $lesson->pivot->priority;
                        $lessonss[$category][$lesson->pivot->topic_id][$lesson->id] = $lesson->pivot->priority;
                        $topics[$category][$lesson->pivot->topic_id] = $lesson->pivot->priority;
                    }
                }
            }
        }

        foreach ($lessonss as $categoryId => $topicsIds) {
            $cat = Category::find($categoryId);
            //$cat->lessons()->detach();
            foreach ($topicsIds as $topicId => $lessonsIds) {
                $t = Topic::find($topicId);
                $cat->topics()->wherePivot('categoryable_id', $topicId)->detach();
                $cat->topics()->save($t);

                foreach ($lessonsIds as $keyLes => $lessonId) {
                    $cat->lessons()->wherePivot('topic_id', $topicId)->wherePivot('lesson_id', $keyLes)->detach();
                    $cat->lessons()->attach($keyLes, ['topic_id'=>$topicId]);
                }
            }
        }

        $topicsAll = Topic::all();

        foreach ($topicsAll as $topicAll) {
            foreach ($topicAll->lessonsCategory as $topic) {
                if (!isset($lessonss[$topic->pivot->category_id][$topic->pivot->topic_id][$topic->pivot->lesson_id])) {
                    continue;
                }/*else{
                    $topicAll->lessonsCategory()->attach($topic->pivot->lesson_id,['category_id'=>$topic->pivot->category_id]);
                }*/
                //dd($lessonss[$topic->pivot->category_id][$topic->pivot->topic_id][$topic->pivot->lesson_id]);
                $topic->pivot->priority = $lessonss[$topic->pivot->category_id][$topic->pivot->topic_id][$topic->pivot->lesson_id];
                $topic->pivot->save();
                //dd($topic->pivot->priority);
            }

            foreach ($topicAll->category as $category) {
                if (!isset($topics[$category->pivot->category_id][$category->pivot->categoryable_id])) {
                    continue;
                }

                $category->pivot->priority = $topics[$category->pivot->category_id][$category->pivot->categoryable_id];
                $category->pivot->save();
            }
            //dd($topicAll->lessonsCategory)
        }

        $categories = Category::all();
        $deleteTopics = [];
        foreach ($categories as $category) {
            //dd($category);

            foreach ($category->events as $event) {
                foreach ($category->lessons as $lesson) {
                    if (count($event->lessons()->wherePivot('topic_id', $lesson->pivot->topic_id)->wherePivot('lesson_id', $lesson->pivot->lesson_id)->get()) > 0) {
                        $deleteTopics[$category->id][$lesson->pivot->topic_id][$lesson->pivot->lesson_id] = true;
                    }
                }
            }

            foreach ($category->lessons as $lesson) {
                if (!isset($deleteTopics[$category->id][$lesson->pivot->topic_id][$lesson->pivot->lesson_id])) {
                    $category->lessons()->wherePivot('lesson_id', $lesson->pivot->lesson_id)->wherePivot('topic_id', $lesson->pivot->topic_id)->detach();
                }
            }
        }

        /*foreach($events as $event){
            if(!$event->category->first()){
                continue;
            }
            $category = $event->category->first()->id;

            foreach($event->lessons->groupBy('topic_id') as $key => $lessons){

                foreach($lessons as $lesson){

                }
            }

        }*/

        /*foreach($topicsAll as $topicAll){
            foreach($topicAll->lessonsCategory as $topic){

                if($topic->pivot->priority  == 0){
                    $topic->pivot->delete();
                }


            }

        }*/

        return 0;
    }
}
