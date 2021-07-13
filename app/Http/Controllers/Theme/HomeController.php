<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Slug;
use App\Model\Event;
use App\Model\Media;
use App\Model\Logos;
use App\Model\Menu;
use App\Model\Category;
use View;

use Laravel\Cashier\Cashier;
use App\Model\User;
use App\Model\Invoice;

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
        $data['header_menus'] = [];
        
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
        $data['summary'] = $event->summary1()->get()->toArray();
        $data['sections'] = $event->sections->groupBy('section');
        $data['faqs'] = $event->getFaqs();
        $data['testimonials'] = isset($event->category->toArray()[0]) ? $event->category->toArray()[0]['testimonials'] : [];
        $data['tickets'] = $event->ticket->toArray();
        $data['syllabus'] = $event->syllabus->toArray();
        //dd($data['syllabus']);
        return view('theme.event.' . $event->view_tpl,$data);

    }
}
