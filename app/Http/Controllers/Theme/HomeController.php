<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Slug;
use App\Model\Event;

class HomeController extends Controller
{
    public function index(Slug $slug){
        
        //dd($slug->slugable);
        //dd(get_class($slug->slugable) == Event::class);

        switch (get_class($slug->slugable)) {
            case Event::class:

                $this->event($slug->slugable);
                break;
           
        }

    }


    private function event($event){
        dd($event->topicsLessonsInstructors()->groupBy('topic_id'));
    }
}
