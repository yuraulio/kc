<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;

class RestoreTopicsLessons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore:topics-lessons';

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
        $category = Category::findOrFail(276);
        $category->topic()->detach();
        foreach($category->events as $event){

            foreach($event->allLessons()->orderBy('priority','asc')->get() as $lesson){

                $category->topic()->attach($lesson->pivot->topic_id,['lesson_id'=>$lesson->id, 'priority'=>$lesson->pivot->priority]);

            }
            
            break;

        }

        return 0;
    }
}
