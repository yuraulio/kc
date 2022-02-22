<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Media;
use App\Model\User;
use App\Model\ExamResult;
use App\Model\Topic;
use App\Model\Lesson;
use App\Model\Summary;
use App\Model\Subscription;
use App\Model\Instructor;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;
use App\Model\Event;
use URL;
use Illuminate\Support\Facades\Hash;
use App\Model\Activation;
use \Carbon\Carbon;
use Session;
use Mail;
use App\Model\PaymentMethod;
use Validator;
use Image;
use App\Model\Plan;
use App\Model\Invoice;
use App\Notifications\ExamActive;

class StudentController extends Controller
{

    public function __construct()
    {
        $this->middleware('event.check')->only('elearning');
    }

    public function infoValidation(Request $request){

        $validatorArray = [];

        //$validatorArray['firstname'] = 'required';
        //$validatorArray['lastname'] = 'required';
        $validatorArray['mobileCheck'] = 'phone:AUTO';


        if($request->email != Auth::user()->email){
            $validatorArray['email'] = 'required|email|unique:users,email';
        }

        $validator = Validator::make($request->all(), $validatorArray);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => '',
            ];

        }else{
            return [
                'status' => 1,
                'message' => '',
            ];
        }

    }

    protected function logout(){

        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        $url = URL::to('/');
        return redirect($url);
    }

    public function index(){

        $user = Auth::user();

        $instructor = count($user->instructor) > 0;

        if($instructor){
            $data = $this->instructorEvents();
        }else{
            $data = $this->studentsEvent();
        }
        $data['instructor'] = $instructor;

        /*$paymentMethod = PaymentMethod::where('method_slug','stripe')->first();
        $data['stripe_key'] = env('PAYMENT_PRODUCTION') ? $paymentMethod->processor_options['key'] :
                                                                    $paymentMethod->test_processor_options['key'];

        $secretKey = env('PAYMENT_PRODUCTION') ? $paymentMethod->processor_options['secret_key'] :
                                                    $paymentMethod->test_processor_options['secret_key'];

        session()->put('payment_method',$paymentMethod->id);
        Stripe::setApiKey($secretKey);
        $user->asStripeCustomer();


        $data['defaultPaymetnt'] = [];
        $data['defaultPaymetntId'] = -1;
        $card = $user->defaultPaymentMethod() ? $user->defaultPaymentMethod()->toArray() : [];

        if(!empty($card)){
            $data['defaultPaymetntId'] = $card['id'];
            $data['defaultPaymetnt'][] = ['brand' => $card['card']['brand'] ,'last4' => $card['card']['last4'],
             'exp_month' => $card['card']['exp_month'], 'exp_year' => $card['card']['exp_year']];
        }

        $data['cards'] = $user->paymentMethods()->toArray();*/


       //dd($data);
        return view('theme.myaccount.student', $data);

    }

    public function instructorEvents(){


        $user = Auth::user();
        $data['user'] = User::with('image', 'instructor','events')->find($user->id);
        $data['masterClassAccess'] = true;
        $instructor = $user->instructor->first();

        $data['elearningAccess'] = 0;
        $data['cards'] = [];
        $data['subscriptionAccess'] = [];
        $data['mySubscriptions'] = [];
        //$data['user'] = $data['user'];
        //[$subscriptionAccess, $subscriptionEvents] = $user->checkUserSubscriptionByEvent();
        //$data['subscriptionAccess'] =  false;//$subscriptionAccess;
        //$data['mySubscriptionEvents'] = [];

        [$subscriptionAccess, $subscriptionEvents] = $user->checkUserSubscriptionByEvent();
        $data['subscriptionAccess'] =  $subscriptionAccess;
        $data['mySubscriptionEvents'] = [];

        $eventSubscriptions = [];
        $data['user']['events'] = $instructor['event'];
        $data['eventss'] = [];
        foreach($data['user']['events'] as $key => $event){
            //if elearning assign progress for this event
            if($event->is_elearning_course()){

                //$data['user']['events'][$event->id]['topics'] = $event['topic']->unique()->groupBy('topic_id')->toArray();
                $data['events'][$event->id]['videos_progress'] = 0;//intval($event->progress($user));
                $data['events'][$event->id]['videos_seen'] = '0/0';//$event->video_seen($user);
                $data['events'][$event->id]['certs'] = [];

                $data['events'][$event->id]['mySubscription'] = [];
                $data['events'][$event->id]['plans'] = [];

                $data['events'][$event->id]['exams'] = [];
                $data['events'][$event->id]['exam_access'] = false;

                $eventSubscriptions[] = [];
                $data['events'][$event->id]['video_access'] = true;
                $data['events'][$event->id]['title'] = $event->title;
                $data['events'][$event->id]['view_tpl'] = $event->view_tpl;
                $data['events'][$event->id]['category'] = $event->category;
                $data['events'][$event->id]['summary1'] = $event->summary1;
                $data['events'][$event->id]['hours'] = $event->hours;
                $data['events'][$event->id]['slugable'] = $event->slugable;
                $data['events'][$event->id]['release_date_files'] = $event->release_date_files;
                $data['events'][$event->id]['plans'] = [];
                $data['events'][$event->id]['exam_access'] =false;
                $data['events'][$event->id]['videos_progress'] = 0;
                $data['events'][$event->id]['expiration'] = false;
                $data['events'][$event->id]['status'] = $event->status;

                $eventSubscriptions[] = $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first() ?
                $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first()->id : -1;

            }else{
                $data['events'][$event->id]['topics'] = $event->topicsLessonsInstructors()['topics'];
                $data['events'][$event->id]['exams'] = [];
                $data['events'][$event->id]['certs'] = [];
                $data['events'][$event->id]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event->id]['category'] = $event['category'];
                $data['events'][$event->id]['summary1'] = $event['summary1'];
                $data['events'][$event->id]['hours'] = $event['hours'];
                $data['events'][$event->id]['slugable'] = $event['slugable']->toArray();
                $data['events'][$event->id]['title'] = $event['title'];
                $data['events'][$event->id]['release_date_files'] = $event->release_date_files;
                $data['events'][$event->id]['plans'] = [];
                $data['events'][$event->id]['status'] = $event->status;



            }


            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if($find !== false){
                $find = true;
                $data['elearningAccess'] = $find;
            }

        }
        $eventSubscriptions = [];
        foreach($user['events'] as $key => $event){
            //if elearning assign progress for this event
            if($event->is_elearning_course()){

                //$data['user']['events'][$event->id]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['events'][$event->id]['videos_progress'] = intval($event->progress($user));
                $data['events'][$event->id]['videos_seen'] = $event->video_seen($user);
                $data['events'][$event->id]['cert'] = [];

                $data['events'][$event->id]['mySubscription'] = $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first();
                $data['events'][$event->id]['plans'] = $event['plans'];

                $data['events'][$event->id]['certs'] = $event->certificatesByUser($user->id);
                $data['events'][$event->id]['exams'] = $event->getExams();
                $data['events'][$event->id]['exam_access'] = $event->examAccess($user,0.8);//$user->examAccess(0.8,$event->id);
                $data['events'][$event->id]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event->id]['category'] = $event['category'];
                $data['events'][$event->id]['summary1'] = $event['summary1'];
                $data['events'][$event->id]['hours'] = $event['hours'];
                $data['events'][$event->id]['slugable'] = $event['slugable']->toArray();
                $data['events'][$event->id]['title'] = $event['title'];
                $data['events'][$event->id]['release_date_files'] = $event->release_date_files;
                $data['events'][$event->id]['expiration'] = date('d M Y',strtotime($event->pivot->expiration));
                $data['events'][$event->id]['status'] = $event->status;
                //$data['user']['events'][$event->id]['exam_results'] = $user->examAccess(0.8,$event->id);

                $eventSubscriptions[] = $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first() ?
                                            $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first()->id : -1;

                //$eventSubscriptions[] =  array_values($user->eventSubscriptions()->wherePivot('event_id',$event['id'])->pluck('id')->toArray());

                $video_access = false;
                $expiration_event = $event->pivot['expiration'];

                $expiration_event = strtotime($expiration_event);

                $now = strtotime(date('Y-m-d'));
                //dd($now);
                //dd($expiration_event >= $now);
                if($expiration_event >= $now || !$expiration_event)
                    $video_access = true;

                $data['events'][$event->id]['video_access'] = $video_access;

                //$this->updateUserStatistic($event,$statistics,$user);

            }else{
      
                $data['events'][$event->id]['topics'] = $event->topicsLessonsInstructors()['topics'];
                $data['events'][$event->id]['exams'] = [];
                $data['events'][$event->id]['certs'] = $event->certificatesByUser($user->id);
                $data['events'][$event->id]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event->id]['category'] = $event['category'];
                $data['events'][$event->id]['summary1'] = $event['summary1'];
                $data['events'][$event->id]['hours'] = $event['hours'];
                $data['events'][$event->id]['slugable'] = $event['slugable']->toArray();
                $data['events'][$event->id]['title'] = $event['title'];
                $data['events'][$event->id]['release_date_files'] = $event->release_date_files;
                $data['events'][$event->id]['plans'] = [];
                $data['events'][$event->id]['status'] = $event->status;



            }


            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if($find !== false){
                $find = true;
                $data['elearningAccess'] = $find;
            }

        }

        foreach($user['eventSubscriptions']->whereNotIn('id',$eventSubscriptions) as $key => $subEvent){
            $event = $subEvent['event']->first();
            if($event->is_elearning_course()){
                //$data['mySubscriptionEvents'][$key]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['mySubscriptionEvents'][$key]['title'] = $event['title'];
                $data['mySubscriptionEvents'][$key]['videos_progress'] = intval($event->progress($user));
                $data['mySubscriptionEvents'][$key]['videos_seen'] = $event->video_seen($user);
                $data['mySubscriptionEvents'][$key]['view_tpl'] = $event['view_tpl'];

                $data['mySubscriptionEvents'][$key]['mySubscription'] = $user->subscriptions()->where('id',$subEvent['id'])->first();
                $video_access = false;
                $expiration_event = $event->pivot['expiration'];
                $expiration_event = strtotime($expiration_event);
                $now = strtotime("now");

                //dd($expiration_event >= $now);
                if($expiration_event >= $now)
                    $video_access = true;

                $data['mySubscriptionEvents'][$key]['video_access'] = $video_access;

            }else{

            }

            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if($find !== false){
                $find = true;
                $data['elearningAccess'] = $find;
            }

        }

        $data['instructors'] = Instructor::with('slugable', 'medias')->get()->groupby('id');
        $data['subscriptionEvents'] = Event::whereIn('id',$subscriptionEvents)->with('slugable')->get();
        //dd($data);
        return $data;
    }

    public static function updateConsent(Request $request)
    {
        $user = Auth::user();

        if($user){

            $now = date('Y-m-d H:i:s');
            $clientip = '';
            $clientip = \Request::ip();
            $user->terms = 1;
            $user->consent = '{"ip": "' . $clientip . '", "date": "'.$now.'" }';
            $user->save();

            return ['status' => 1, 'message' => 'Thank you!'];
        }

        return ['status' => 0, 'message' => 'No student found'];
    }

    /*public function updateUserStatistic($event,$statistics,$user){

        $tab = $event['title'];
                $tab = str_replace(' ','_',$tab);
                $tab = str_replace('-','',$tab);
                $tab = str_replace('&','',$tab);
                $tab = str_replace('_','',$tab);


                $statistic = $statistics;


                if($statistic['videos'] != ''){
                    $notes = json_decode($statistic['notes'], true);
                    $videos = json_decode($statistic['videos'], true);
                }

                $count_lesson = 0;

                foreach($event->topicsLessonsInstructors()['topics'] as $key => $topic){
                    //dd($topic);

                     foreach($topic['lessons'] as $key1 => $lesson){
                         // if(isset($lesson) && $lesson['vimeo_video'] != null){
                             //dd($lesson);

                             $vimeo_id = str_replace('https://vimeo.com/', '', $lesson['vimeo_video']);

                             if($statistic['videos'] != ''){
                                 if(!isset($videos[$vimeo_id])){
                                     //append new vimeo id
                                     $videos[$vimeo_id] = [];
                                     $videos[$vimeo_id]['seen'] = 0;
                                     $videos[$vimeo_id]['tab'] =
                                     $videos[$vimeo_id]['lesson_id'] = $lesson['id'];
                                     $videos[$vimeo_id]['stop_time'] = 0;
                                     $videos[$vimeo_id]['percentMinutes'] = 0;
                                     $videos[$vimeo_id]['tab'] = $tab.$count_lesson;

                                     $notes[$vimeo_id] = '';

                                 }
                             }else{
                                 $videos[$vimeo_id] = [];
                                 $videos[$vimeo_id]['seen'] = 0;
                                 $videos[$vimeo_id]['tab'] =
                                 $videos[$vimeo_id]['lesson_id'] = $lesson['id'];
                                 $videos[$vimeo_id]['stop_time'] = 0;
                                 $videos[$vimeo_id]['percentMinutes'] = 0;
                                 $videos[$vimeo_id]['tab'] = $tab.$count_lesson;

                                 $notes[$vimeo_id] = '';
                             }
                             //var_dump($vimeo_id);


                         // }
                         $count_lesson++;

                     }

                 }

                 //dd($videos);
                $user->statistic()->wherePivot('event_id', $event['id'])->updateExistingPivot($event['id'],['videos' => $videos, 'notes' => $notes], false);

    }*/

    /*public function studentsEvent(){

        $user = Auth::user();

        $data['elearningAccess'] = 0;
        $data['cards'] = [];
        $data['subscriptionAccess'] = [];
        $data['mySubscriptions'] = [];

        $data['user'] = User::find($user->id);
        $statistics = $data['user']['statistic']->groupBy('id');//$user->statistic()->get()->groupBy('id');
        //dd($statistics);
        [$subscriptionAccess, $subscriptionEvents] = $user->checkUserSubscriptionByEvent();
        $data['subscriptionAccess'] =  $subscriptionAccess;
        $data['mySubscriptionEvents'] = [];

        $eventSubscriptions = [];

        foreach($data['user']['events'] as $key => $event){

            //if elearning assign progress for this event
            if($event->is_elearning_course()){

                //$data['user']['events'][$event->id]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['events'][$event->id]['videos_progress'] = intval($event->progress($user));
                $data['events'][$event->id]['videos_seen'] = $event->video_seen($user);
                $data['events'][$event->id]['cert'] = [];

                $data['events'][$event->id]['mySubscription'] = $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first();
                $data['events'][$event->id]['plans'] = $event['plans'];

                $data['events'][$event->id]['certs'] = $event->certificatesByUser($user->id);
                $data['events'][$event->id]['exams'] = $event->getExams();
                $data['events'][$event->id]['exam_access'] = $event->examAccess($user,0.8);//$user->examAccess(0.8,$event->id);
                $data['events'][$event->id]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event->id]['category'] = $event['category'];
                $data['events'][$event->id]['summary1'] = $event['summary1'];
                $data['events'][$event->id]['hours'] = $event['hours'];
                $data['events'][$event->id]['slugable'] = $event['slugable']->toArray();
                $data['events'][$event->id]['title'] = $event['title'];
                $data['events'][$event->id]['release_date_files'] = $event->release_date_files;
                $data['events'][$event->id]['expiration'] = $event->pivot->expiration;
                $data['events'][$event->id]['status'] = $event->status;
                //$data['user']['events'][$event->id]['exam_results'] = $user->examAccess(0.8,$event->id);

                $eventSubscriptions[] = $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first() ?
                                            $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first()->id : -1;

                //$eventSubscriptions[] =  array_values($user->eventSubscriptions()->wherePivot('event_id',$event['id'])->pluck('id')->toArray());

                $video_access = false;
                $expiration_event = $event->pivot['expiration'];

                $expiration_event = strtotime($expiration_event);

                $now = strtotime(date('Y-m-d'));
                //dd($now);
                //dd($expiration_event >= $now);
                if($expiration_event >= $now || !$expiration_event)
                    $video_access = true;

                $data['events'][$event->id]['video_access'] = $video_access;

                //$this->updateUserStatistic($event,$statistics,$user);


            }else{
                $data['events'][$event->id]['topics'] = $event->topicsLessonsInstructors()['topics'];
                //dd($data['user']['events'][$event->id]['topics']);
                $video_access = false;
                $expiration_event = $event->pivot['expiration'];
                $expiration_event = strtotime($expiration_event);
                $data['events'][$event->id]['exams'] = $event->getExams();
                $data['events'][$event->id]['certs'] = $event->certificatesByUser($user->id);
                $data['events'][$event->id]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event->id]['category'] = $event['category'];
                $data['events'][$event->id]['summary1'] = $event['summary1'];
                $data['events'][$event->id]['hours'] = $event['hours'];
                $data['events'][$event->id]['slugable'] = $event['slugable']->toArray();
                $data['events'][$event->id]['title'] = $event['title'];
                $data['events'][$event->id]['release_date_files'] = $event->release_date_files;
                $data['events'][$event->id]['status'] = $event->status;
                //$data['user']['events'][$event->id]['exam_access'] = $user->examAccess(0.8,$event->id);

            }


            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if($find !== false){
                $find = true;
                $data['elearningAccess'] = $find;
            }

        }

        foreach($user['eventSubscriptions']->whereNotIn('id',$eventSubscriptions) as $key => $subEvent){
            if(!($event = $subEvent['event']->first())){
            	continue;
            }
            if($event->is_elearning_course()){
                //$data['mySubscriptionEvents'][$key]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['mySubscriptionEvents'][$key]['title'] = $event['title'];
                $data['mySubscriptionEvents'][$key]['videos_progress'] = intval($event->progress($user));
                $data['mySubscriptionEvents'][$key]['videos_seen'] = $event->video_seen($user);
                $data['mySubscriptionEvents'][$key]['view_tpl'] = $event['view_tpl'];

                $data['mySubscriptionEvents'][$key]['mySubscription'] = $user->subscriptions()->where('id',$subEvent['id'])->first();
                $data['mySubscriptionEvents'][$key]['plans'] = $event['plans'];
                $video_access = false;
                $expiration_event = $event->pivot['expiration'];
                $expiration_event = strtotime($expiration_event);
                $now = strtotime("now");

                //dd($expiration_event >= $now);
                if($expiration_event >= $now)
                    $video_access = true;

                $data['mySubscriptionEvents'][$key]['video_access'] = $video_access;

            }else{

            }

            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if($find !== false){
                $find = true;
                $data['elearningAccess'] = $find;
            }

        }
        $data['instructors'] = Instructor::with('slugable', 'medias')->get()->groupby('id');
        $data['subscriptionEvents'] = Event::whereIn('id',$subscriptionEvents)->with('slugable')->get();

        if(isset($data['events'][2304])){
            $value = $data['events'][2304];
            unset($data['events'][2304]);
            array_unshift($data['events'], $value);
        }

        //dd($data['user']['events'][0]);
        return $data;

    }*/

    public function studentsEvent(){

        $user = Auth::user();

        $data['elearningAccess'] = 0;
        $data['masterClassAccess'] = false;
        $data['cards'] = [];
        $data['plans'] = Plan::where('published',true)->with('events')->get();
        $data['subscriptionAccess'] = [];
        $data['mySubscriptions'] = [];

        $data['user'] = User::find($user->id);
        $statistics = $data['user']['statistic']->groupBy('id');//$user->statistic()->get()->groupBy('id');
        //dd($statistics);
        [$subscriptionAccess, $subscriptionEvents] = $user->checkUserSubscriptionByEvent();

        $data['subscriptionAccess'] =  $subscriptionAccess;
        $data['mySubscriptionEvents'] = [];

        $eventSubscriptions = [];

        foreach($data['user']['events'] as $key => $event){

            //if elearning assign progress for this event
            if($event->is_elearning_course()){

                //$data['user']['events'][$event->id]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['events'][$event->id]['videos_progress'] = intval($event->progress($user));
                $data['events'][$event->id]['videos_seen'] = $event->video_seen($user);
                $data['events'][$event->id]['cert'] = [];

                $data['events'][$event->id]['mySubscription'] = $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first();
                $data['events'][$event->id]['plans'] = $event['plans'];

                $data['events'][$event->id]['certs'] = $event->certificatesByUser($user->id);
                $data['events'][$event->id]['exams'] = $event->getExams();
                $data['events'][$event->id]['exam_access'] = $event->examAccess($user,0.8);//$user->examAccess(0.8,$event->id);
                $data['events'][$event->id]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event->id]['category'] = $event['category'];
                $data['events'][$event->id]['summary1'] = $event['summary1'];
                $data['events'][$event->id]['hours'] = $event['hours'];
                $data['events'][$event->id]['slugable'] = $event['slugable']->toArray();
                $data['events'][$event->id]['title'] = $event['title'];
                $data['events'][$event->id]['release_date_files'] = $event->release_date_files;
                $data['events'][$event->id]['expiration'] = $event->pivot->expiration ? date('d M Y',strtotime($event->pivot->expiration)) : '';
                $data['events'][$event->id]['status'] = $event->status;
                //$data['user']['events'][$event->id]['exam_results'] = $user->examAccess(0.8,$event->id);

                $eventSubscriptions[] = $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first() ?
                                            $user->eventSubscriptions()->wherePivot('event_id',$event['id'])->first()->id : -1;

                //$eventSubscriptions[] =  array_values($user->eventSubscriptions()->wherePivot('event_id',$event['id'])->pluck('id')->toArray());

                $video_access = false;
                $expiration_event = $event->pivot['expiration'];

                $expiration_event = strtotime($expiration_event);

                $now = strtotime(date('Y-m-d'));
                //dd($now);
                //dd($expiration_event >= $now);
                if($expiration_event >= $now || !$expiration_event)
                    $video_access = true;

                $data['events'][$event->id]['video_access'] = $video_access;
               
                if($event->id == 2304 && $video_access){
                    $data['masterClassAccess'] = true;
                }

                //$this->updateUserStatistic($event,$statistics,$user);


            }else{
                $data['events'][$event->id]['topics'] = $event->topicsLessonsInstructors()['topics'];
                //dd($data['user']['events'][$event->id]['topics']);
                $video_access = false;
                $expiration_event = $event->pivot['expiration'];
                $expiration_event = strtotime($expiration_event);
                $data['events'][$event->id]['exams'] = $event->getExams();
                $data['events'][$event->id]['certs'] = $event->certificatesByUser($user->id);
                $data['events'][$event->id]['view_tpl'] = $event['view_tpl'];
                $data['events'][$event->id]['category'] = $event['category'];
                $data['events'][$event->id]['summary1'] = $event['summary1'];
                $data['events'][$event->id]['hours'] = $event['hours'];
                $data['events'][$event->id]['slugable'] = $event['slugable']->toArray();
                $data['events'][$event->id]['title'] = $event['title'];
                $data['events'][$event->id]['release_date_files'] = $event->release_date_files;
                $data['events'][$event->id]['status'] = $event->status;
                //$data['user']['events'][$event->id]['exam_access'] = $user->examAccess(0.8,$event->id);

            }


            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if($find !== false){
                $find = true;
                $data['elearningAccess'] = $find;
            }

        }
        
        foreach($user['eventSubscriptions']->whereNotIn('id',$eventSubscriptions) as $key => $subEvent){
            if(!($event = $subEvent['event']->first())){
            	continue;
            }
            if($event->is_elearning_course()){
                //$data['mySubscriptionEvents'][$key]['topics'] = $event['topic']->unique()->groupBy('topic_id');
                $data['mySubscriptionEvents'][$key]['title'] = $event['title'];
                $data['mySubscriptionEvents'][$key]['videos_progress'] = intval($event->progress($user));
                $data['mySubscriptionEvents'][$key]['videos_seen'] = $event->video_seen($user);
                $data['mySubscriptionEvents'][$key]['view_tpl'] = $event['view_tpl'];

                $data['mySubscriptionEvents'][$key]['mySubscription'] = $user->subscriptions()->where('id',$subEvent['id'])->first();
                $data['mySubscriptionEvents'][$key]['plans'] = $event['plans'];
                $video_access = false;
                $expiration_event = $event->pivot['expiration'];
                $expiration_event = strtotime($expiration_event);
                $now = strtotime("now");

                //dd($expiration_event >= $now);
                if($expiration_event >= $now)
                    $video_access = true;

                $data['mySubscriptionEvents'][$key]['video_access'] = $video_access;
                $data['masterClassAccess'] = true;

            }else{

            }

            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if($find !== false){
                $find = true;
                $data['elearningAccess'] = $find;
            }

        }
        $data['instructors'] = Instructor::with('slugable', 'medias')->get()->groupby('id');
        $data['subscriptionEvents'] = Event::whereIn('id',$subscriptionEvents)->with('slugable')->get();

        if(isset($data['events'][2304])){
            $value = $data['events'][2304];
            unset($data['events'][2304]);
            array_unshift($data['events'], $value);
        }

        //dd($data['user']['events'][0]);
        return $data;

    }

    public function removeProfileImage(){
        //dd('from remove');
        $user = Auth::user();
        $media = $user->image;
        if(!$media){
            return;
        }
        $path_crop = explode('.', $media['original_name']);
        $path_crop = $media['path'].$path_crop[0].'-crop'.$media['ext'];
        $path_crop = substr_replace($path_crop, "", 0, 1);

        $path = $media['path'].$media['original_name'];
        $path = substr_replace($path, "", 0, 1);

        if(file_exists($path_crop)){
            //unlink crop image
            unlink($path_crop);
        }

        if(file_exists($path)){
            //unlink crop image
            unlink($path);
        }

        //null db image
        $media->original_name = null;
        $media->name = null;
        $media->path = null;
        $media->ext = null;
        $media->file_info = null;
        $media->height = null;
        $media->width = null;
        $media->details = null;

        $media->save();

    }

    public function uploadProfileImage(Request $request){
       
        $this->removeProfileImage();
        
        $user = Auth::user();
        $media = $user->image;

        if(!$media){        
            $media = $user->createMedia();
        }

        $content = $request->file('dp_fileupload');
        $name1 = explode(".",$content->getClientOriginalName());

        $path_name = $request->dp_fileupload->store('profile_user', 'public');

        $image = Image::make(public_path('/uploads/').$path_name);

        if($image->width() > $image->height()){
            $image->heighten(470)->crop(470, 470);
        }elseif($image->width() < $image->height()){
            $image->widen(470)->crop(470, 470);
        }else{
            $image->resize(470, 470);
        }

        $image->save(public_path('/uploads/').$path_name, 60);

        $name = explode('profile_user/',$path_name);
        $size = getimagesize('uploads/'.$path_name);
        $media->name = $name1[0];
        $media->ext = '.'.$content->guessClientExtension();
        $media->original_name = $name[1];
        $media->file_info = $content->getClientMimeType();
        $string = $path_name;
        $media->details = null;

        $string = explode('/', $string);
        array_pop($string);
        $string = implode('/', $string);
        $media->path = '/'.'uploads/'.$string.'/';


        $media->width = $size[0];
        $media->height = $size[1];
        $media->save();

        return response()->json([
            'message' => 'Change profile photo successfully!!',
            'data' => $media->path.$media->original_name
        ]);

    }

    public function updatePersonalInfo(Request $request){
        $user = Auth::user();

        if($user->email !== $request->email && !$request->has("password")){

            $this->validate($request, [
                'firstname' => ['required', 'min:3'],
                'lastname' => ['required', 'min:3'],
                'email' => [
                    'required', 'email','unique:users,email'
                ]
                ]);
        }else if(!$request->has("password")){
            $this->validate($request,[
                'firstname' => ['required', 'min:3'],
                'lastname' => ['required', 'min:3'],
                'email' => [
                  'required', 'email',
                ]
                ]);
        }

        $hasPassword = $request->get("password");

        $user->update($request->merge([
            'password' => Hash::make($request->get('password'))
        ])->except([$hasPassword ? '' : 'password']));

        //dd($user);

        return redirect('/myaccount');
    }

    public function updateInvoiceBilling(Request $request){
        $data = array();
        $currentuser = Auth::user();
        if($currentuser) {
            $pay_invoice_data = [];
            $pay_invoice_data['billing'] = 2;
            $pay_invoice_data['companyname'] = $request->input('companyname');
            $pay_invoice_data['companyprofession'] = $request->input('companyprofession');
            $pay_invoice_data['companyafm'] = $request->input('companyafm');
            $pay_invoice_data['companydoy'] = $request->input('companydoy');
            $pay_invoice_data['companyaddress'] = $request->input('companyaddress');
            $pay_invoice_data['companyaddressnum'] = $request->input('companyaddressnum');
            $pay_invoice_data['companypostcode'] = $request->input('companypostcode');
            $pay_invoice_data['companycity'] = $request->input('companycity');
            $currentuser->invoice_details = json_encode($pay_invoice_data);
            $currentuser->save();
            return ['status' => 1, 'saveddata' => $pay_invoice_data];
        }
        else {
            return ['status' => 0];
        }
    }

    public function updateReceiptBilling(Request $request){
        $data = array();
        $currentuser = Auth::user();
        if($currentuser) {
            $pay_receipt_data = [];
            $pay_receipt_data['billing'] = 1;
            $pay_receipt_data['billname'] = $request->input('billname');
            $pay_receipt_data['billemail'] = $request->input('billemail');
            $pay_receipt_data['billaddress'] = $request->input('billaddress');
            $pay_receipt_data['billaddressnum'] = $request->input('billaddressnum');
            $pay_receipt_data['billpostcode'] = $request->input('billpostcode');
            $pay_receipt_data['billcity'] = $request->input('billcity');
            $pay_receipt_data['billafm'] = $request->input('billafm');
            $pay_receipt_data['billcountry'] = $request->input('billcountry');
            $pay_receipt_data['billstate'] = $request->input('billstate');

            $currentuser->receipt_details = json_encode($pay_receipt_data);
            $currentuser->save();
            return back();
        }
        else {
            return back();
        }
    }

    public static function downloadMyData()
    {

        $currentuser = Auth::user();
        if($currentuser) {
            // prepare content
             /*$content = 'First name,Last name,E-mail,Company,Job title,Mobile,Phone,Address,Post code,City,Vat'.PHP_EOL;
             $content .= $currentuser->first_name.','
                        .$currentuser->last_name.','
                        .$currentuser->email.','
                        .$currentuser->company.','
                        .$currentuser->job_title.','
                        .$currentuser->mobile.','
                        .$currentuser->telephone.','
                        .$currentuser->address.' '.$currentuser->address_num.','
                        .$currentuser->post_code.','
                        .$currentuser->city.','
                        .$currentuser->afm.PHP_EOL;*/
            $content = 'KnowCrunch data for: '.PHP_EOL;
            $content .= '------------------------'. PHP_EOL;
            $content .= PHP_EOL;
            $content .= 'First name: '.$currentuser->firstname.PHP_EOL;
            $content .= 'Last name: '.$currentuser->lastname.PHP_EOL;
            $content .= 'E-mail: '.$currentuser->email.PHP_EOL;
            $content .= 'Company: '.$currentuser->company.PHP_EOL;
            $content .= 'Job title: '.$currentuser->job_title.PHP_EOL;
            $content .= 'Mobile: '.$currentuser->mobile.PHP_EOL;
            $content .= 'Phone: '.$currentuser->telephone.PHP_EOL;
            $content .= 'Address: '.$currentuser->address.' '.$currentuser->address_num.PHP_EOL;
            $content .= 'Post code: '.$currentuser->post_code.PHP_EOL;
            $content .= 'City: '.$currentuser->city.PHP_EOL;
            $content .= 'Vat: '.$currentuser->afm.PHP_EOL;
            $content .= '------------------------'. PHP_EOL;
            $content .= PHP_EOL;
            /*                    'billemail' => 'Email',
                    'billmobile' => 'Mobile',*/
            $hone = [
                    'billname' => 'First name',
                    'billsurname' => 'Last name',
                    'billaddress' => 'Address',
                    'billaddressnum' => 'Street number',
                    'billpostcode' => 'Postcode',
                    'billcity' => 'City',
                    'billafm' => 'Vat number'
                ];
            $htwo = [
                    'companyname' => 'Company name',
                    'companyprofession' => 'Profession',
                    'companyafm' => 'Vat number',
                    'companydoy' => 'Tax area',
                    'companyaddress' => 'Address',
                    'companyaddressnum' => 'Steet number',
                    'companypostcode' => 'Postcode',
                    'companycity' => 'City',
                    'companyemail' => 'Company Email'
                ];
            if($currentuser->invoice_details != '') {
                $content .= 'Invoice Details: ' . PHP_EOL;
                $content .= '------------------------'. PHP_EOL;
                $invoice_details = json_decode($currentuser->invoice_details, true);
                foreach ($invoice_details as $key => $value) {
                    if($key != 'billing') {
                         $content .= $htwo[$key]. ': '. $value . PHP_EOL;
                    }
                }
                $content .= PHP_EOL;
            }
            if($currentuser->receipt_details != '') {
                $content .= 'Receipt Details: ' . PHP_EOL;
                $content .= '------------------------'. PHP_EOL;
                $receipt_details = json_decode($currentuser->receipt_details, true);
                foreach ($receipt_details as $key => $value) {
                    if($key != 'billing' && isset($hone[$key])) {
                         $content .= $hone[$key]. ': '. $value . PHP_EOL;
                    }
                }
            }
            // file name that will be used in the download
            $fileName = 'my_knowcrunch_data.txt';
            // use headers in order to generate the download  text/plain
            $headers = [
              'Content-type' => 'text/plain',
              'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName)
            ];
           // ,  'Content-Length' => sizeof($content)
            // make a response, with the content, a 200 response code and the headers
            /*$response = new StreamedResponse();
            $response->setCallBack(function () use($content) {
                  echo $content;
            });
            $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);
            $response->headers->set('Content-Disposition', $disposition);
            return $response;*/
            return \Response::make($content, 200, $headers);
        }
    }

    public function elearning($course){
        
        $user = Auth::user();

        $has_access = false;
        $event = Event::where('title', $course)->with('slugable','category')->first();

        if($user->instructor->first()){
            $data['instructor_topics'] = true;
            $eventt = $user->instructor->first()->event()->wherePivot('instructor_id', $user->instructor->first()->id)->wherePivot('event_id', $event['id'])->first();
            
            if(!$eventt){
                $data['instructor_topics'] = false;
                $eventt = $user->events()->wherePivot('event_id', $event['id'])->first() ? $user->events()->wherePivot('event_id', $event['id'])->first() :
                            $user->subscriptionEvents->where('id',$event['id'])->first();
            }
            $event = $eventt;
        }else{
            $event = $user->events()->wherePivot('event_id', $event['id'])->first() ? $user->events()->wherePivot('event_id', $event['id'])->first() :
            $user->subscriptionEvents->where('id',$event['id'])->first();
            $data['instructor_topics'] = false;
        }
        
        $data['details'] = $event->toArray();
        $data['details']['slug'] = $event['slugable']['slug'];

        $data['files'] = !$user->instructor->first() && isset($event['category'][0]['dropbox'][0]) ? $event['category'][0]['dropbox'][0]->toArray() : [];
        //dd($data['files']);
        //dd($data['details']);

        $data['videos_progress'] = $event->progress($user);
        $data['course'] = $event['title'];
        //dd($data['course']);

        $statistic =  ($statistic = $user->statistic()->wherePivot('event_id',$event['id'])->first()) ?
                            $statistic->toArray() : ['pivot' => [], 'videos' => ''];

        //$this->updateUserStatistic($event,$statistic['pivot'],$user);
        $statistic = $user->updateUserStatistic($event,$statistic['pivot']);
        $data['lastVideoSeen'] = $statistic['pivot']['lastVideoSeen'];
        $data['event_statistic_id'] = $statistic['pivot']['id'];
        $data['event_id'] = $statistic['pivot']['event_id'];
        //dd($statistic);
        //load videos
        $data['videos'] = $statistic['pivot']['videos'];
        //load notes
        $data['notes'] = $statistic['pivot']['notes'];
        //dd($data['notes']);
        //load statistic

        //$data['instructor_topics'] = count($user->instructor) > 0;
        //expiration event for user
        $expiration_event_user = $event['pivot']['expiration'];
        $data['topics'] = $event->topicsLessonsInstructors();

        return view('theme.myaccount.newelearning', $data);


    }

    public function saveNote(Request $request){
        $user = Auth::user();
        $user = User::find($user['id']);

        //dd($user->statistic()->wherePivot('event_id', $request->event)->get());
        $notess = $user->statistic()->wherePivot('event_id', $request->event)->first();
        if($notess != ""){
            $notes = json_decode($notess->pivot['notes'],true);
            //dd($notes);
            foreach($notes as $key => $note){
                if($key == $request->vimeoId){
                    $note =  preg_replace( '/[^A-Za-z0-9\-]/', ' ',  $request->text );
                    $note = preg_replace('/\s+/', ' ', $note);
                    //$note =  preg_replace( "/\r|\n/", "||", $request->text );

                    $notes[$key] = $note;
                }
            }
        }


        $user->statistic()->wherePivot('event_id', $request->event)->updateExistingPivot($request->event,['notes' => json_encode($notes)], false);



        return response()->json([
            'success' => true,
            'text' => $request->text,
            'vimeoId' =>$request->vimeoId
        ]);

    }

    public function saveElearning(Request $request){
        $user = Auth::user();
        $user = User::find($user['id']);

        $examAccess = false;

        if($user->statistic()->wherePivot('event_id',$request->event)->first()){

          $videos = $user->statistic()->wherePivot('event_id',$request->event)->first()->pivot['videos'];
          $videos = json_decode($videos,true);
          foreach($request->videos as $key => $video){


            if(!isset( $videos[$key])){
                continue;
            }

            //$videos[$key]['seen'] = isset($video['seen']) ? $video['seen'] : 0;
            $videos[$key]['stop_time'] = isset($video['stop_time']) ? $video['stop_time'] : 0;
            $videos[$key]['percentMinutes'] = isset($video['stop_time']) ? $video['percentMinutes'] : 0;

            if( (int) $video['seen'] == 1 && (int) $videos[$key]['seen'] == 0){
                $videos[$key]['seen'] = (int) $video['seen'];

            }

          }

            $user->statistic()->wherePivot('event_id',$request->event)->updateExistingPivot($request->event,[
                'lastVideoSeen' => $request->lastVideoSeen,
                'videos' => json_encode($videos)
            ], false);


            /*if($user->events()->where('event_id',2068)->first() && $user->events()->where('event_id',2068)->first() &&
                $user->events()->where('event_id',2068)->first()->tickets()->wherePivot('user_id',$user->id)->first()){

                    $user->events()->where('event_id',2068)->first()->certification($user);

            }*/

            $event=$user->events()->where('event_id',$request->event)->first();

            if(isset($_COOKIE['examMessage-'.$request->event_statistic])){

                $examAccess = false;

            }else if( $event && count($event->getExams()) > 0 ){

                $examAccess = $event->examAccess($user);

                if($examAccess){

                    $adminemail = 'info@knowcrunch.com';

                   

                     
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['fbGroup'] = $event->fb_group;
                    $data['subject'] = 'Knowcrunch - ' . $data['firstName'] .'  you exams are active now';
                    $data['template'] = 'emails.user.exam_activate';

                    $user->notify(new ExamActive($data));

                    /*$muser['name'] = $user->firstname . ' ' . $user->lastname;
                    $muser['first'] = $user->firstname;
                    $muser['eventTitle'] =  $event->title;
                    $muser['email'] = $user->email;

                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;

                    $sent = Mail::send('emails.student.exam_activate', $data, function ($m) use ($adminemail, $muser) {

                        $fullname = $muser['name'];
                        $first = $muser['first'];
                        $sub =  $first . ' – Your exams on the ' . $muser['eventTitle'] . ' have been activated!';
                        $m->from($adminemail, 'Knowcrunch');
                        $m->to($muser['email'], $fullname);
                        //$m->cc($adminemail);
                        $m->subject($sub);

                    });*/

                }

            }else if( $event /*&& $event->view_tpl != 'elearning_free'*/ && count($event->getExams()) == 0 ){
                $event->certification($user);
            }

        }

        return response()->json([
            'success' => true,
            'videos' => $request->videos,
            'loged_in' => true,
            'exam_access' => $examAccess,
            // 'progress' => $progress
        ]);

    }

    public function activate($code)
    {
        $activation = Activation::where('code',$code)->first();
        if (!$activation) {

        	Session::flash('opmessage', 'Invalid or expired activation code.');
            Session::flash('opstatus', 0);
            return redirect('/')->withErrors('Invalid or expired activation code.');

        }

        $user = $activation->user;
        $input = $user->only('email','password');
        Auth::login($user);
        if(Auth::check()){

            $activation->completed = true;
            $activation->completed_at = Carbon::now();
            $activation->save();

            Session::flash('opmessage', 'Your account is now activated. You may login now!');
            Session::flash('opstatus', 1);

            return redirect('/myaccount')->withInput()->with('message','Your account is now activated. You may login now!');
        }

        return redirect('/')->withErrors('Invalid or expired activation code.');

    }

    public static function getDownloadLink(Request $request)
    {
        $data = $request->all();
        $dropboxPath = $data['dir'];
        $fileName = $data['fname'];
        $accessToken = env('DROPBOX_TOKEN');
        $client = new \Spatie\Dropbox\Client($accessToken);
        return $client->getTemporaryLink($dropboxPath);
    }

    public function createPassIndex($slug){

        decrypt($slug);

        try{
            $user = decrypt($slug);

            if(!User::where('id',$user['id'])->where('email',$user['email'])->first()){
                abort(404);
            }

            $create = $user['create'];
            return view('auth.passwords.complete',compact('slug','create'));

        }catch(\Exception $e){
            abort(404);
        }


    }

    public function createPassStore(Request $request,$slug){

        $user = decrypt($slug);

        if( !($user = User::where('id',$user['id'])->where('email',$user['email'])->first()) ){
            return response()->json([

                'success' => false,
                'pass_confirm' => true,
                'message' => 'The user no longer exists.',
                'redirect' =>'/'

            ]);
        }

        $val =Validator::make($request->all(), [
            'password' => 'required|confirmed',

         ]);

         if($val->fails()){
            return response()->json([
                'success' => false,
                'pass_confirm' => false,
                'message' => $val->errors()->first()
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $user->statusAccount->completed = true;
        $user->statusAccount->completed_at = Carbon::now();
        $user->statusAccount->save();

        Auth::login($user);

        return response()->json([

            'success' => true,
            'pass_confirm' => true,
            'message' => 'Password was successfully resetted.',
            'redirect' =>'/myaccount'

        ]);

    }

    public function downloadMyInvoice($slug){

        try{

            $invoice = decrypt($slug);
            $invoice = explode('-',$invoice);
    
            if(count($invoice) < 2 || count($invoice) > 3){
                abort(404);
            }
    
            if( !$user = User::find($invoice[0]) ){
                abort(404);
            }
    
            if( !$inv = Invoice::find($invoice[1]) ){
                abort(404);
            }

            $planDescription = isset($invoice[2]) ? $invoice[2] : false;
        
            $users = $inv->user;
            $userIds = [];
    
            foreach($users as $us){
                $userIds[] = $us->id;
            }
    
        
            if( !in_array($invoice[0], $userIds)){
                abort(404);
            }
    
            return $inv->getInvoice($planDescription);

        }catch(\Exception $e){

            abort(404);

        }

       

    }

}
