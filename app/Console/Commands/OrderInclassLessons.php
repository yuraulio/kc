<?php

namespace App\Console\Commands;

use App\Model\Event;
use Illuminate\Console\Command;

class OrderInclassLessons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:inclass-lessons';

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
        $events = Event::all();
        //4611 //1307
        //4612 //lesson 1307

        //
        $lessons = [];
        foreach ($events as $event) {
            $priority = 1;
            if ($event->view_tpl == 'elearning_event' || $event->view_tpl == 'elearning_free') {
                continue;
            }

            if ($event->id == 4612 || $event->id == 4611) {
                $event->lessons()->detach(1307);
            }

            //if($event->id == 4612){

            foreach ($event->lessons as $lesson) {
                //dd($event->title);
                $lesson->pivot->priority = $priority;
                $lesson->pivot->save();
                $priority += 1;
            }

            //}
        }

        return 0;
    }
}
