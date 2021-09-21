<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Topic;
use App\Model\Event;
use App\Model\Category;

class RemoveTopics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:topics';

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
        $topics = Topic::all();
        $events = Event::all();
        $categories = Category::all();

        foreach($topics as $topic){
            
            $found = false;

            foreach($events as $event){
                if($event->allLessons()->wherePivot('topic_id',$topic->id)->first()){
                    $found = true;
                }
            }

            if(!$found){
                $topic->lessonsCategory()->detach();
                $topic->category()->detach();
                $topic->delete();
            }
        }

        foreach($topics as $topic){
            $found = false;

            foreach($categories as $category){
                foreach($category->events as $event){
                    if($event->allLessons()->wherePivot('topic_id',$topic->id)->first()){
                        $found = true;
                    }
                }
                /*if($category->id == 183 && $topic->id == 186){
                    dd('found '. ' => '. $found);
                }*/
                if(!$found){
                    $topic->category()->wherePivot('category_id',$category->id)->detach();
                }
            }
            

            
        }

        return 0;
    }
}
