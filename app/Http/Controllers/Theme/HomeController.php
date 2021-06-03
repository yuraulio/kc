<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Slug;
use App\Model\Event;
use View;

class HomeController extends Controller
{
    public function index(Slug $slug){
        
        //dd($slug->slugable);
        //dd(get_class($slug->slugable) == Event::class);

        switch (get_class($slug->slugable)) {
            case Event::class:
                return $this->event($slug->slugable);
                break;
           
        }

    }


    private function event($event){
        //dd($event);
        //dd($event->topicsLessonsInstructors()->groupBy('topic_id'));
        $data = $event->topicsLessonsInstructors();
        $data['event'] = $event;
        $data['benefits'] = $event->benefits;
        $data['summary'] = $event->summary()->get();
       
        $data['sections'] = $event->sections->groupBy('section');
        //dd($data['sections']);
        //dd('theme.event.' . $event->view_tpl);
        return view('theme.event.' . $event->view_tpl,$data);

       
    }
}
