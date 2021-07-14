<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Slug;
use App\Model\Event;
use App\Model\Delivery;
use App\Model\Media;
use App\Model\Logos;
use App\Model\Menu;
use App\Model\Type;
use App\Model\Category;
use View;
use App\Model\Pages;
use Laravel\Cashier\Cashier;
use App\Model\User;
use App\Model\Invoice;
use Auth;

class HomeController extends Controller
{

    public function homePage(){

        //dd($slug->slugable);*/

        $data = [];

        //$data['events'] = Event::with('category', 'medias', 'slugable', 'ticket')->get()->toArray();
        $data['eventsbycategory1'] = Category::with('events.slugable','events.city','events.category')->where('show_homepage', 1)->get()->toArray();
        $data['eventsbycategory'] = [];
        $data['eventsbycategoryFree'] = [];
        $data['inclassEventsbycategoryFree'] = [];
        $data['eventsbycategoryElearning'] = [];

        //dd($data['eventsbycategory1']);
        foreach($data['eventsbycategory1'] as $key => $bcatid){
            //dd($bcatid['id']);
            //$data['eventsbycategory'][$key] = $bcatid;
            //dd($bcatid);

            foreach($bcatid['events'] as $key1 => $event){
                if($event['status']!=0 && $event['status'] != 2){
                    continue;
                }
                if($event['view_tpl'] != 'elearning_free' && $event['view_tpl'] != 'event_free' && $event['view_tpl'] != 'event_free_coupon' && $event['view_tpl'] != 'elearning_event')
                {
                    
                    $data['eventsbycategory'][$bcatid['id']]['events'][] =  $event;
                    $data['eventsbycategory'][$bcatid['id']]['category'][] = $bcatid;
                    $data['eventsbycategory'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['eventsbycategory'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['eventsbycategory'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                }else if($event['view_tpl'] == 'elearning_event' || $event['view_tpl'] == 'elearning_pending'){

                    $data['eventsbycategoryElearning'][$bcatid['id']]['events'][] = $event;
                    $data['eventsbycategoryElearning'][$bcatid['id']]['category'][] = $bcatid;
                    $data['eventsbycategoryElearning'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['eventsbycategoryElearning'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['eventsbycategoryElearning'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                }else if( $event['view_tpl'] == 'event_free' || $event['view_tpl'] == 'event_free_coupon'){
                    //dd('asd');
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['events'][] = $event;
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['category'][] = $bcatid;
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['inclassEventsbycategoryFree'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                    
                }else if($event['view_tpl'] == 'elearning_free'){
                    //dd($event);
                    $data['eventsbycategoryFree'][$bcatid['id']]['events'][] = $event;
                    $data['eventsbycategoryFree'][$bcatid['id']]['category'][] = $bcatid;
                    $data['eventsbycategoryFree'][$bcatid['id']]['cat']['name'] = $bcatid['name'];
                    $data['eventsbycategoryFree'][$bcatid['id']]['cat']['description'] = $bcatid['description'];
                    $data['eventsbycategoryFree'][$bcatid['id']]['cat']['hours'] = $bcatid['hours'];
                }
            }

        }

        $data['homeBrands'] = Logos::with('medias')->where('type', 'brands')->inRandomOrder()->take(6)->get()->toArray();
        $data['homeLogos'] = Logos::with('medias')->where('type', 'logos')->inRandomOrder()->take(6)->get()->toArray();
        $data['homePage'] = Pages::where('name','home')->with('mediable')->first()->toArray();
        //dd($data['homePage']);
        //$data['header_menus'] = [];
        
        //dd($data['eventsbycategory'][46]['events']);
        return view('theme.home.homepage',$data);

    }

    public function addPaymentMethod(Request $request){
        $user = User::find(1359);
        //dd($request->all());
        $user->updateDefaultPaymentMethod($request->payment_method);
        dd($user->paymentMethods());
    }

    public function index(Slug $slug){


        //dd($slug->slugable == Type::class);
        //dd(get_class($slug->slugable) == Event::class);
        //dd(get_class($slug->slugable) == Delivery::class);

        switch (get_class($slug->slugable)) {
            case Event::class:
                //dd('asd');
                return $this->event($slug->slugable);
                break;

            case Delivery::class:
                return $this->delivery($slug->slugable);
                //dd('test');
                //return view('theme.pages.category', $data);
                break;

            case Type::class:
                //dd($slug);
                return $this->types($slug->slugable);
                //dd('test');
                //return view('theme.pages.category', $data);
                break;

        }

    }

    private function types($type){
        $data['header_menus'] = $this->header();

        $data['type'] = $type;

        //dd($type->events()->with('category','slugable', 'city', 'ticket','summary1')->where('status', 0)->get()->toArray());

        $data['openlist'] = $type->events()->with('category','slugable', 'city', 'ticket','summary1')->where('status', 0)->get();
        $data['completedlist'] = $type->events()->with('category','slugable', 'city', 'ticket', 'summary1')->where('status', 3)->get();

        return view('theme.pages.category' ,$data);

    }

    private function delivery($delivery){

        $data['header_menus'] = $this->header();


        $data['delivery'] = $delivery;
        $data['openlist'] = $delivery->event()->with('category','slugable', 'city', 'ticket','summary1')->where('status', 0)->get();
        $data['completedlist'] = $delivery->event()->with('category','slugable', 'city', 'ticket', 'summary1')->where('status', 3)->get();
        //dd($data['completedlist']);

        return view('theme.pages.category' ,$data);
    }


    private function event($event){
       
        $data = $event->topicsLessonsInstructors();
        $data['event'] = $event;
        $data['benefits'] = $event->benefits;
        $data['summary'] = $event->summary1()->get()->toArray();
        $data['sections'] = $event->sections->groupBy('section');
        $data['faqs'] = $event->getFaqs();
        $data['testimonials'] = isset($event->category->toArray()[0]) ? $event->category->toArray()[0]['testimonials'] : [];
        $data['tickets'] = $event->ticket->toArray();
        $data['venues'] = $event->venues->toArray();
        $data['syllabus'] = $event->syllabus->toArray();

        $data['is_event_paid'] = 0;
        if(Auth::user() && count(Auth::user()->events->where('id',$event->id)) > 0){
            $data['is_event_paid'] = 1;
        }
        
        return view('theme.event.' . $event->view_tpl,$data);

    }

    private function header(){
        $menus = Menu::where('name', 'Header')->get();
        $result = array();
        foreach ($menus as $key => $element) {
            $result[$element['name']][] = $element;

            $model = app($element['menuable_type']);

            $element->data = $model::with('slugable')->find($element['menuable_id']);
            //dd($element->data);

        }
        return $result;
    }
}
