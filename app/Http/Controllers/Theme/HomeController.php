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
use App\Model\City;
use App\Model\Topic;
use App\Model\Instructor;
use App\Model\Category;
use View;
use App\Model\Pages;
use Laravel\Cashier\Cashier;
use App\Model\User;
use App\Model\Invoice;
use Auth;
use Carbon\Carbon;
use App\Model\Transaction;

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


        //dd(get_class($slug->slugable) == Event::class);
        //dd(get_class($slug->slugable) == Delivery::class);

        switch (get_class($slug->slugable)) {
            case Event::class:
                return $this->event($slug->slugable);
                break;

            case Delivery::class:
                return $this->delivery($slug->slugable);
                break;

            case Type::class:
                return $this->types($slug->slugable);
                break;

            case Pages::class:
                return $this->pages($slug->slugable);
                break;

            case Instructor::class:
                return $this->instructor($slug->slugable);
                break;

            case City::class:
                return $this->city($slug->slugable);
                break;

        }

    }

    public function enrollToFreeEvent(Event $content){


        $published = $content->published;
      // dd($estatus);
        if($published == 0){
            return false;
        }

        if(Auth::user() && $user = (Auth::user())){

            $student = $user->events->where('id',$content->id)->first();
            if(!$student){

                //ticket
                $eventticket = $student->ticket->first();

                $payment_method_id = 1;//intval($input["payment_method_id"]);
                $payment_cardtype = 8; //free;
                $amount = 0;
                $namount = (float)$amount;
                $transaction_arr = [

                    'payment_method_id' => $payment_method_id,
                    'account_id' => 17,
                    'payment_status' => 2,
                    'billing_details' => '',//serialize($billing_details),
                    'placement_date' => Carbon::now()->toDateTimeString(),
                    'ip_address' => '127.0.0.1',
                    'type' => $payment_cardtype,//((Sentinel::inRole('super_admin') || Sentinel::inRole('know-crunch')) ? 1 : 0),
                    'status' => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
                    'is_bonus' => 0, //$input['is_bonus'],
                    'order_vat' => 0, //$input['credit'] - ($input['credit'] / (1 + Config::get('dpoptions.order_vat.value') / 100)),
                    'surcharge_amount' => 0,
                    'discount_amount' => 0,
                    'amount' => $namount, //$input['credit'],
                    'total_amount' => $namount,
                    'trial' => false
                ];

                $transaction = Transaction::create($transaction_arr);

                if ($transaction) {
                    // set transaction id in session

                    $pay_seats_data = ["names" => [Auth::user()->first_name],"surnames" => [Auth::user()->last_name],"emails" => [Auth::user()->email],
                    "mobiles" => [Auth::user()->mobile],"addresses" => [Auth::user()->address],"addressnums" => [Auth::user()->address_num],
                    "postcodes" => [Auth::user()->postcode],"cities" => [Auth::user()->city],"jobtitles" => [Auth::user()->job_title],
                    "companies" => [Auth::user()->company],"students" => [""], "afms" => [Auth::user()->afm]];


                    $deree_user_data = [Auth::user()->email => Auth::user()->partner_id];
                    $cart_data = ["manualtransaction" => ["rowId" => "manualtransaction","id" => $eventticket->pivot->ticket_id,"name" => $content->title,"qty" => "1","price" => $amount,"options" => ["type" => "8","event"=> $content->id],"tax" => 0,"subtotal" => $amount]];

                    $status_history[] = [
                    'datetime' => Carbon::now()->toDateTimeString(),
                    'status' => 1,
                    'user' => [
                        'id' => $user, //0, $this->current_user->id,
                        'email' => Auth::user()->email,//$this->current_user->email
                        ],
                    'pay_seats_data' => $pay_seats_data,//$data['pay_seats_data'],
                    'pay_bill_data' => [],
                    'cardtype' => 8,
                    'installments' => 1,
                    'deree_user_data' => $deree_user_data, //$data['deree_user_data'],
                    'cart_data' => $cart_data //$cart
                    ];

                    $transaction->update(['status_history' => json_encode($status_history)/*, 'billing_details' => $tbd*/]);

                    $today = date('Y/m/d');
                    $expiration_date = '';

                    if($content->expiration){
                        $monthsExp = '+' . $event->expiration .'months';
                        $expiration_date = date('Y-m-d', strtotime($monthsExp, strtotime($today)));
                    }

                    $content->users()->save($user,['comment'=>'free','expiration'=>$expiration_date,'paid'=>true]);

                }
            }

        }

        return back();
    }

    private function city($page){
        $data['header_menus'] = $this->header();

        $data['content'] = $page;

        $city = City::with('event')->find($page['id']);

        //$data['content'] = $city;
        $data['city'] = $city;
        $data['openlist'] = $city->event()->with('category','slugable', 'city', 'ticket','summary1')->where('status', 0)->get();
        $data['completedlist'] = $city->event()->with('category','slugable', 'city', 'ticket', 'summary1')->where('status', 3)->get();

        return view('theme.pages.category' ,$data);
    }

    private function instructor($page){
        $data['header_menus'] = $this->header();

        $data['content'] = $page;
        $events = array();

        $instructor = Instructor::with('event.category', 'medias', 'event.lessons', 'event.slugable', 'event.city', 'event.summary1')->find($page['id']);
        //dd($instructor['event'][0]);
        $category = array();


        foreach($instructor['event'] as $key => $event){
            if(($event['status'] == 0 || $event['status'] == 2) && $event->is_inclass_course()){
                foreach($event['lessons'] as $lesson){

                    if($lesson->pivot['date'] != ''){
                        $date = date("Y/m/d", strtotime($lesson->pivot['date']));
                    }else{
                        $date = date("Y/m/d", strtotime($lesson->pivot['time_starts']));
                    }
                    if(strtotime("now") < strtotime($date)){
                        if($lesson['instructor_id'] == $page['id']){
                            $lessons[] = $lesson['title'];
                        }
                    }


                }

            }
        }
        $category = array();

        foreach($instructor['event'] as $key => $event){

                //dd($event['status']);
                if($key == 0){
                    $category[$event['id']] = $event;
                }else{
                    if(!isset($category[$event['id']])){
                        $category[$event['id']] = $event;
                    }
                }

        }

        $new_events = array();

        foreach($category as $category){
            //dd($category['title']);
            if(count($new_events) == 0){
                array_push($new_events, $category);
            }else{
                $find = false;
                foreach($new_events as $event){
                    if($event['title'] == $category['title']){
                        $find = true;
                    }
                }
                if(!$find){
                    array_push($new_events, $category);
                }
            }
        }

        $data['instructorTeaches'] = array_unique($lessons);

        $data['instructorEvents'] = $new_events;
        //dd($new_events);


        return view('theme.pages.instructor_page' ,$data);
    }

    private function pages($page){
        $data['header_menus'] = $this->header();

        $data['page'] = $page;
        if($data['page']['template'] == 'corporate_page'){
            $data['page']['template'] = 'corporate-template';
            $data['benefits'] = $page->benefits;
            $data['corporatebrands'] = Logos::with('medias')->where('type', 'brands')->get();
        }else if($data['page']['template'] == 'instructors'){
            $data['instructors'] =  Instructor::with('medias')->where('status', 1)->get();
        }else if($data['page']['id'] == 800){
            $data['brands'] = Logos::with('medias')->where('type', 'brands')->get();
        }else if($data['page']['id'] == 801){
            $data['logos'] = Logos::with('medias')->where('type', 'logos')->get();
        }

        return view('admin.static_tpls.'.$data['page']['template'].'.frontend' ,$data);
    }

    private function types($type){
        $data['header_menus'] = $this->header();

        $data['type'] = $type;

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
            $data['is_event_paid'] = 0;
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

    public function printSyllabusBySlug($slug = '')
    {

        //Request $request
        $data = array();

        $slug = Slug::where('slug', $slug)->first();
        //dd($slug);
        $data['content'] = $slug->slugable;
        //dd($event);

        $data['content'] = Event::with('category', 'city', 'topic')->find($data['content']['id']);
        //dd($data['content']->topicsLessonsInstructors());

        $data['eventtopics']= $data['content']->topicsLessonsInstructors()['topics'];
        //dd($data['eventtopics']);
        foreach($data['eventtopics'] as $key => $topic){
            //dd($key);
            $topic = Topic::where('title', $key)->first();
            $topicDescription[$key] = $topic['summary'];
        }
        //dd($topicDescription);

        $topicDescription = $data['eventtopics'];
        //dd($topicDescription);

        //dd($data['eventtopics']);
        //dd(Topic::where());
        $data['eventorganisers']=array();
        $data['location']= $data['content']['city'][0];

        $topicDescription = [];

        // if (isset($data['content']->categories) && !empty($data['content']->categories)) :

        // $eventCategory = [];
        // $data['eventtopics']= $data['content']->topicsLessonsInstructors()['topics'];
        // $data['eventorganisers']=array();
        // $data['location']= $data['content']['city'][0];
        // $blockCategory = -1;

        // endif;

        //dd($data['content']);

        // foreach ($data['content']->categories as $category) :
        //     //45 block category
        //     if ($category->depth != 0 && $category->parent_id == 45) {
        //          $blockCategory=$category->id;
        //          $data['blockcat'] = $blockCategory;
        //          break;
        //     }

        // endforeach;

        // if($blockCategory == '') {
        //     $data['blockcat'] = 1;
        //     $blockCategory = 1;
        // }

        // foreach ($data['content']->categories as $category) :
        //         if(!in_array($category->id, $data['eventtopics'])){
        //             if ($category->depth != 0 && $category->parent_id == $blockCategory) {
        //                  $data['eventtopics'][]=$category->id;
        //             }
        //         }
        //         //if(!in_array($category->id, $data['eventorganisers'])){
        //             if ($category->depth != 0 && $category->parent_id == 5) {
        //                  //$data['eventorganisers'][] = array_push($data['eventorganisers'], $category->id);
        //                  $data['eventorganisers'][] = $category;//->allMedia;
        //             }
        //         //}

        //             if ($category->depth != 0 && $category->parent_id == 9) {
        //                  $data['location']=$category;
        //             }
        //             //22 Event Category, 12 Event Type

        //             if ($category->depth != 0 && $category->parent_id == 22) {
        //                  $eventCategory[]=$category->id;
        //             }

        //     endforeach;



        //$data['topics'] = Category::where('parent_id', '=', $blockCategory)->where('status',1)->where('type',0)->whereIn('id', $data['eventtopics'])->orderBy('priority', 'asc')->orderBy('created_at', 'asc')->get();

            dd($data['topics']);
        foreach($data['topics'] as $topic){
            $topicDescription[$topic->name] = $topic->description;
        }

        $filterQuery = EventLessonInstructor::where('event_id', $data['content']->id)->where('type', 1)->with('lesson.featured.media', 'lesson.customFields', 'lesson.categories', 'instructor.featured.media')->orderBy('timestarts', 'ASC')->get();

        $data['etax'] = $filterQuery->toArray();

        $datain = [];
        $datain = EventLessonInstructor::where('event_id', $data['content']->id)->where('type', 1)->groupBy('instructor_id')->with('lesson.featured.media','instructor.featured.media')->orderBy('timestarts', 'ASC')->get()->toArray();


        $insttmp = [];
        foreach ($datain as $key => $value) {
            //echo $value->id;
            $insttmp[] = $value['instructor_id'];
        }

        $data['instructors'] = Content::whereIn('id', $insttmp)->with('categories','tags','author','featured.media')->orderBy('subtitle', 'ASC')->get();
        //->orderBy('priority', 'ASC')

        $data['is_event_paid'] = 1;
        $data['desc'] = $topicDescription;
        $topics = $this->getTopic($data['content'], $data,false);

        $data['topics'] = $topics['topicss'];



        /*foreach($data['topics'] as $key => $value){

            dd($key);

        }*/

        $this->cFieldLib->contentCustomFields($data['instructors']);
        //return view('theme.event.syllabus_print', $data);
        $pdf = PDF::loadView('theme.event.syllabus_print', $data)->setPaper('a4', 'landscape');
        $fn = $slug . '.pdf';
        return $pdf->stream($fn);
       /* $slug
        $pdf = PDF::loadView('pdf.invoice', $data);
        return $pdf->download('invoice.pdf');
        PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->save('myfile.pdf')*/
    }



}
