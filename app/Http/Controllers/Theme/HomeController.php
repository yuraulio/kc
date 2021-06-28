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
        
        $data = $event->topicsLessonsInstructors();
        $data['event'] = $event;
        $data['benefits'] = $event->benefits;
        $data['summary'] = $event->summary1()->get();
        $data['sections'] = $event->sections->groupBy('section');
        $data['faqs'] = $event->getFaqs();
        $data['testimonials'] = isset($event->category->toArray()[0]) ? $event->category->toArray()[0]['testimonials'] : [];
        $data['tickets'] = $event->ticket->toArray();
        $data['syllabus'] = $event->syllabus->toArray();
        //dd($data['syllabus']);
        return view('theme.event.' . $event->view_tpl,$data);

    }
}
