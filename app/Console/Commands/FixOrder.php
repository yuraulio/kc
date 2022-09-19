<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Event;
use App\Model\Category;

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
    public function handle()
    {
        $masterEvent = Event::find(2304);
        $events = Event::whereIn('id',[2304,4628,4627,4626,4625,4624,4623,4622,4621])->get();

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
    }
}
