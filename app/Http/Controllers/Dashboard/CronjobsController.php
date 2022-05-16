<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Invoice;
use Laravel\Cashier\Subscription;
use Mail;
use App\Model\Event;
use Illuminate\Support\Facades\File;
use App\Model\Option;
use App\Model\CartCache;
use App\Notifications\AbandonedCart;
use Carbon\Carbon;
use App\Notifications\ExpirationMails;
use App\Notifications\FailedPayment;
use App\Notifications\PaymentReminder;
use App\Notifications\HalfPeriod;
use App\Notifications\ElearningFQ;
use App\Notifications\SurveyEmail;
use App\Notifications\SubscriptionReminder;
use App\Model\Pages;
use App\Model\Transaction;
use App\Model\Absence;

class CronjobsController extends Controller
{
    
    public function unroll(){

        $students = User::has('events')->with('events')->get();
        $today = date('Y-m-d');
        $today = strtotime($today);
        foreach($students as $student){

            $events = $student->events()->wherePivot('comment','enroll')->get();

            foreach($events as $event){

                if(!$event->pivot->expiration){
                    continue;
                }

                $expiration = strtotime($event->pivot->expiration);

                if($today > $expiration){
                    //$student->events()->detach($event->id);
                    $student->events()->where('id',$event->id)->detach();
                }

            }

        }

    }

    public function sendNonPayment(){

        $adminemail = 'info@knowcrunch.com';

        $date = date('Y-m-d');

        $invoiceUsers = Invoice::doesntHave('subscription')->where('date','<',$date)->where('date','!=', '-')->where('instalments_remaining','>', 0)->where('email_sent',0)->get();
        //dd($invoiceUsers);

        foreach($invoiceUsers as $invoiceUser){

            if(!$invoiceUser->user->first() || !$invoiceUser->event->first()){
                continue;
            }
            $data = [];
            $data['name'] = $invoiceUser->user->first()->firstname . ' ' . $invoiceUser->user->first()->lastname ;
            $data['firstName'] = $invoiceUser->user->first()->firstname;
            $data['eventTitle'] = $invoiceUser->event->first()->title;
            $data['subject'] = 'Knowcrunch - ' . $data['firstName'] .' your payment failed';
            $data['amount'] = round($invoiceUser->amount,2);
            $data['template'] = 'emails.user.failed_payment';
            $data['userLink'] = url('/') . '/admin/user/' . $invoiceUser->user->first()->id . '/edit'; 
            //$data['installments'] =

            $invoiceUser->user->first()->notify(new FailedPayment($data));


            /*$adminemail = $invoiceUser->event->first()->paymentMethod->first() && $invoiceUser->event->first()->paymentMethod->first()->payment_email ?
            $invoiceUser->event->first()->paymentMethod->first()->payment_email : 'info@knowcrunch.com';

            $data['subject'] = 'Knowcrunch - All payments failed';
            $sent = Mail::send('emails.admin.failed_stripe_payment', $data, function ($m) use ($adminemail,$data) {

                $sub =  $data['subject'];
                $m->from('info@knowcrunch.com', 'Knowcrunch');
                $m->to($adminemail, $data['firstName']);
                $m->subject($sub);
            
            });*/

            $invoiceUser->email_sent = true;
            $invoiceUser->save();

        }



    }

    public function sendSubscriptionNonPayment(){

        $adminemail = 'info@knowcrunch.com';

        $today = strtotime( date('Y-m-d'));
        $subscriptions = Subscription::where('must_be_updated','<',$today)->whereIn('stripe_status',['active','trialing'])->where('email_send',false)->where('must_be_updated','!=', 0)->get();
        
        foreach($subscriptions as $subscription){
          
            $subscription->email_send = true;
            $subscription->save();

            //dd($subscription->user);
            $user = $subscription->user;
            $event = $user->subscriptionEvents()->where('subscription_id',$subscription->id)->first();

            $muser['name'] = $user->firstname . ' ' . $user->lastname;
            $muser['first'] = $user->firstname;
            $muser['eventTitle'] =  $event->title;
            $muser['email'] = $user->email;

            $data['firstName'] = $user->firstname;
            $data['eventTitle'] = $event->title;
            $data['subject'] = 'Knowcrunch |' . $data['firstName'] . ' - Subscription Payment Declined';;
            $data['expirationDate'] = $event->pivot->expiration;
            $data['template'] = 'emails.user.subscription_non_payment';
            $data['amount'] = round($subscription->price,2);

            $user->first()->notify(new FailedPayment($data));


            /*$sent = Mail::send('emails.student.subscription.subscription_payment_declined', $data, function ($m) use ($adminemail, $muser) {

                $fullname = $muser['name'];
                $first = $muser['first'];
                $sub = 'Knowcrunch |' . $first . ' - Subscription Payment Declined';
                $m->from($adminemail, 'Knowcrunch');
                $m->to($muser['email'], $fullname);
                //$m->cc($adminemail);
                $m->subject($sub);

            });*/


        }

    }

    public function generateCSVForFB(){

        $destinationPath = public_path().'/csv/fb/';
        if(!File::exists($destinationPath)) {
            //dd('ddd');
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }



        $events = Event::where('view_tpl','!=','event_free')->where('view_tpl','!=','event_free_coupon')->where('view_tpl','!=','elearning_free')->where('published',true)->whereIn('status',[0])->with('category', 'ticket','mediable')->get();
        $columns = array('id', 'title', 'description', 'availability', 'price', 'link', 'image_link', 'brand', 'google_product_category','condition','custom_label_0');

        $file = fopen('csv/fb/fb.csv', 'w');
        fputcsv($file, $columns);

        foreach($events as $event) {

            //$cat = $event->category->first() ? $event->category->first()->name : '';
            $cat = 'Business & Industrial > Advertising & Marketing';
            $amount = 0;

            foreach($event->ticket as $price){
                if($price->pivot->price > 0 && $price->type != 'Alumni' && $price->type != 'Special') {
                    $amount = $price->pivot->price;
                }

            }

            $eventTitle = $event->xml_title ? $event->xml_title : $event->title;
            $summary = $event->xml_description;

        
            $img = url('/') . $event['mediable']['path'] . '/' . $event['mediable']['original_name'];
            fputcsv($file, array($event->id, $eventTitle, trim($summary), 'in stock', $amount . ' EUR', url('/') . '/' . $event->slugable->slug, str_replace('\"', '', $img), 'Knowcrunch',  $cat, 'new',$event->xml_short_description));

        }
        fclose($file);

        return back();


    }

    public function generateCSVForGoogle(){

        $destinationPath = public_path().'/csv/google/';
        if(!File::exists($destinationPath)) {
            //dd('ddd');
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }

        $events = Event::where('view_tpl','!=','event_free')->where('view_tpl','!=','event_free_coupon')->where('view_tpl','!=','elearning_free')->where('published',true)->whereIn('status',[0])->with('category', 'ticket','mediable')->get();

        $columns = array("ID", "Item Title", "Final URL", "Image URL", "Price", "Item Category", "Item Description");


        $file = fopen('csv/google/google.csv', 'w');
        fputcsv($file, $columns);

        foreach($events as $event) {

            //$cat = $event->category->first() ? $event->category->first()->name : '';
            $cat = 'Business & Industrial > Advertising & Marketing';
            $city = null;

            $amount = 0;
            
            foreach($event->ticket as $price){
                if($price->pivot->price > 0 && $price->type != 'Alumni' && $price->type != 'Special') {
                    $amount = $price->pivot->price;
                }

            }

            $img = url('/') . $event['mediable']['path'] . '/' . $event['mediable']['original_name'];

            $eventTitle = $event->xml_title ? $event->xml_title : $event->title;
            $summary = $event->xml_description;
            
            fputcsv($file, array($event->id, $eventTitle,  url('/') . '/' . $event->slugable->slug, $img, $amount . ' EUR',  $cat, trim($summary)));

        }
        fclose($file);

        return back();

    }

    public function fbGoogleCsv(){
        $this->generateCSVForGoogle();
        $this->generateCSVForFB();
    }

    public function sendSubscriptionRemind(){

        $adminemail = 'info@knowcrunch.com';
        $today = strtotime( date('Y/m/d'));
        $subscriptions = Subscription::where('must_be_updated','>',$today)->where('stripe_status','active')->get();

        $today = date_create( date('Y/m/d'));
        foreach($subscriptions as $subscription){

            if($subscription->event->first() && $subscription->event->first()->pivot->expiration && $subscription->user){
                $date = date_create($subscription->event->first()->pivot->expiration);
                $date = date_diff($date, $today);

                //if( $date->y==0 && ( ($date->m == 1 &&  $date->d == 0) || ($date->m ==  0 && $date->d == 7))){
                if( $date->y==0 && $date->m == 0 && $date->d == 7 ) {

                    $muser['name'] = $subscription->user->firstname . ' ' . $subscription->user->lastname;
                    $muser['first'] = $subscription->user->firstname;
                    $muser['eventTitle'] =  $subscription->event->first()->title;
                    $muser['email'] = $subscription->user->email;

                    /*$data['firstName'] = $subscription->user->firstname;
                    $data['eventTitle'] = $subscription->event->first()->title;
                    $data['expirationDate'] = date('d/m/Y',strtotime($subscription->event->first()->pivot->expiration));

                    $sent = Mail::send('emails.student.subscription.subscription_date_reminder', $data, function ($m) use ($adminemail, $muser) {

                        $fullname = $muser['name'];
                        $first = $muser['first'];
                        $sub = $first . ' - A reminder about the Subscription expiration date';
                        $m->from($adminemail, 'Knowcrunch');
                        $m->to($muser['email'], $fullname);
                        //$m->cc($adminemail);
                        $m->subject($sub);

                    });*/

                    $data['subject'] = 'Knowcrunch - '. $subscription->user->firstname . ' your subscription will be renewed soon';
                    $data['firstName'] = $subscription->user->firstname;
                    $data['eventTitle'] = $subscription->event->first()->title;
                    $data['expirationDate'] = date('d/m/Y',strtotime($subscription->event->first()->pivot->expiration));
                    $data['template'] = 'emails.user.subscription_reminder';
                    $subscription->user->notify(new SubscriptionReminder($data));

                }
            }
        }

    }

    public function updateStatusField(){
        $subscriptions = Subscription::whereIn('stripe_status',['canceled','cancel','cancelled'])->get();

        foreach($subscriptions as $subscription){
            $subscription->status = false;
            $subscription->save();
        }

        $subscriptions = Subscription::where('status',true)->get();

        foreach($subscriptions as $subscription){

            if(!$subscription->trial_ends_at && !$subscription->ends_at){
                continue;
            }

            if(strtotime($subscription->trial_ends_at) < strtotime($subscription->ends_at)){

                $subscription->stripe_status = 'active';
                $subscription->save();
                
            }

            
        }

        $date = strtotime(date('Y-m-d'));
        $subscriptions = Subscription::where('status',false)->get();

        foreach($subscriptions as $subscription){

            if(strtotime($subscription->ends_at) < $date){
                $subscription->stripe_status = 'cancelled';
                $subscription->save();
            }

            
        }

    }

    public function dereeIDNotification(){
        $option = Option::where('abbr','deree_codes')->first();
		$dereelist = json_decode($option->settings, true);


        if(count($dereelist) <= 15 ){
            $data = [];
            $data['dereeIDs'] = count($dereelist);
            $sent = Mail::send('emails.admin.deree_notification', $data, function ($m)  {

                $sub = 'DereeIDs';
                $m->from('info@knowcrunch.com', 'Knowcrunch');
                $m->to('info@knowcrunch.com', 'Knowcrunch');
                $m->subject($sub);

            });
        }

    }

    public function remindAbandonedUser()
    {
       
        $abandoneds = CartCache::where('send_email',false)->get();
        
        foreach($abandoneds as $abandoned){


            if($abandoned->created_at >= now()->subMinutes(5)){
               continue;
            }

            if(!$user = $abandoned->user){
                continue;
            }

            if(!$event = $abandoned->eventt){
                continue;
            }

            if(!$event->published || $event->status!=0){
                continue;
            }

            $data['firstName'] = $user->firstname;
            $data['eventTitle'] = $event->title;
            $data['faqs'] = url('/') . '/' . $event->slugable->slug . '/#faq';
            $data['slug'] = url('/') . '/registration?cart=' . $abandoned->slug;

            $user->notify(new AbandonedCart($data));
            $abandoned->send_email = 1;
            $abandoned->save();

        }

    }

    public function sendExpirationEmails(){

        $adminemail = 'info@knowcrunch.com';
        $events = Event::has('transactions')->with('users')->where('view_tpl','elearning_event')->get();

        $today = date_create( date('Y/m/d'));
        $today1 = date('Y-m-d');

        foreach($events as $event){

            foreach($event['users'] as $user){

                if(!($user->pivot->expiration >= $today1) || !$user->pivot->expiration){
                    continue;
                }


                $date = date_create($user->pivot->expiration);
                $date = date_diff($date, $today);

                if( $event->id == 2304 && ($date->y == 0 && $date->m ==  0 && $date->d == 7 )){
                    
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['expirationDate'] = date('d-m-Y',strtotime($user->pivot->expiration));

                    $page = Pages::find(4752);

                    $data['subscriptionSlug'] =  url('/') . '/' . $page->getSlug() ;
                    $data['template'] = 'emails.user.courses.masterclass_expiration';
                    $data['subject'] = 'Knowcrunch | ' . $data['firstName'] . ' your course expires soon';

                    $user->notify(new ExpirationMails($data));

                }elseif( $event->id !== 2304 && ($date->y == 0 && $date->m ==  0 && $date->d == 7 )){
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['expirationDate'] = date('d-m-Y',strtotime($user->pivot->expiration));

                    $data['template'] = 'emails.user.courses.week_expiration';
                    $data['subject'] = 'Knowcrunch | ' . $data['firstName'] . ' your course expires soon';

                    $user->notify(new ExpirationMails($data));
                }


                

            }

        }

    }

    public function sendPaymentReminder(){

        $today = date('Y-m-d');

        $invoiceUsers = Invoice::doesntHave('subscription')->where('date','!=', '-')->where('instalments_remaining','>', 0)->get();
        //dd($invoiceUsers);

        foreach($invoiceUsers as $invoiceUser){

            if(!$invoiceUser->user->first()){
                continue;
            }

            //dd($invoiceUser);
            $date = date_create($invoiceUser->date);
            $today = date_create( date('Y/m/d'));
            $date = date_diff($date, $today);

            if(($date->y == 0 && $date->m ==  0 && $date->d == 7 )){

                $data = [];
                $data['firstName'] = $invoiceUser->user->first()->firstname;
                $data['eventTitle'] = $invoiceUser->event->first()->title;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] .' a payment is coming';
                $data['paymentDate'] = date('d-m-Y',strtotime($invoiceUser->date));
                $data['template'] = 'emails.user.payment_reminder';
                //$data['installments'] =


                $invoiceUser->user->first()->notify(new PaymentReminder($data));
            }

        }

    }

    public function sendHalfPeriod(){


        $adminemail = 'info@knowcrunch.com';

        $events = Event::has('transactions')->with('users')->where('view_tpl','elearning_event')->get();
        //$events = Event::has('transactions')->where('published','true')->with('users')->get();

        $today = date_create( date('Y/m/d'));
        $today1 = date('Y-m-d');


        foreach($events as $event){

            foreach($event['users'] as $user){

                if(!($user->pivot->expiration >= $today1) || !$user->pivot->expiration || !$event->expiration){
                    continue;
                }

                

                $date = date_create($user->pivot->expiration);
                $date = date_diff($date, $today);

                if( $date->y==0 && $date->m == ($event->expiration/2)  && $date->d == 0){

                    // dd('edww');
                    
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['fbGroup'] = $event->fb_group;
                    $data['subject'] = 'Knowcrunch - ' . $data['firstName'] .' you are almost there';
                    $data['template'] = 'emails.user.half_period';

                    $user->notify(new HalfPeriod($data));
                    

                }
            }
        }


        $events = Event::has('transactions')->where('published',true)->with('users')->where('view_tpl','event')->get();
        //$events = Event::has('transactions')->where('published','true')->with('users')->get();

        $today = date_create( date('Y/m/d'));
        $today1 = date('Y-m-d');


        foreach($events as $event){

            foreach($event['users'] as $user){

                if( !( $eventDate = $event->summary1()->where('section','date')->first() ) || !$event->expiration ){
                    continue;
                }

                $eventDate = explode('-',$eventDate->title);

                if(!isset($eventDate[1])){
                    continue;
                }

                $eventDate = date('Y-m-d',strtotime($eventDate[1]));
                $date = date_create($eventDate);
                $date = date_diff($date, $today);

                if( $date->y==0 && $date->m == ($event->expiration/2)  && $date->d == 0){

                    // dd('edww');
                    
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['fbGroup'] = $event->fb_group;
                    $data['subject'] = 'Knowcrunch - ' . $data['firstName'] .' you are almost there';
                    $data['template'] = 'emails.user.half_period';

                    $user->notify(new HalfPeriod($data));
                    

                }
            }
        }


    }

    public function sendElearningFQ(){

        $today = date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))));
        $adminemail = 'info@knowcrunch.com';
        $transactions = Transaction::with('event','user')->whereDay('created_at',date('d',strtotime($today)))
                        ->whereMonth('created_at',date('m',strtotime($today)))
                        ->whereYear('created_at',date('Y',strtotime($today)))
                        ->where(function ($q) use($today) {
                            $q->whereHas('event', function ($query) use($today){
                                $query->whereViewTpl('elearning_event');
                            });
                        })->get();
 
        foreach($transactions as $transaction){
            if( !( $event = $transaction->event->first() ) ){
                continue;
            }

            if(count($event->getExams()) <= 0 || !$event->expiration){
                continue;
            }
            
            foreach($transaction['user'] as $user){
   
                $data['firstName'] = $user->firstname;
                $data['eventTitle'] = $event->title;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] .' enjoying ' . $event->title .'?';
                $data['elearningSlug'] = url('/') . '/myaccount/elearning/' . $event->title;
                $data['expirationDate'] = date('d-m-Y',strtotime($user->pivot->expiration));
                $data['template'] = 'emails.user.elearning_f&qemail';

                $user->notify(new ElearningFQ($data));
                    
                
            }
        }

    }

    public function sendSurveyMail(){

    
        $events = Event::has('transactions')->with('users')->where('view_tpl','elearning_event')->get();
        //$events = Event::has('transactions')->where('published','true')->with('users')->get();
     
        $today = date('Y/m/d');
        $today = date('Y-m-d', strtotime('-1 day', strtotime($today)));
        foreach($events as $event){
        
            $sendEmail = false;
            foreach($event['users'] as $user){

                if( $user->pivot->expiration !== $today || !$user->pivot->expiration){
                    continue;
                }
                if($event->evaluate_instructors){
                    $sendEmail = true;
                }else if($event->evaluate_topics){
                    $sendEmail = true;
                }else if($event->fb_testimonial){
                    $sendEmail = true;
                }

                $data['firstName'] = $user->firstname;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' please take our survey';
                $data['template'] = 'emails.user.survey_email';
                $data['fb_group'] = $event->fb_group;
                $data['evaluateTopics'] = $event->evaluate_topics;
                $data['evaluateInstructors'] = $event->evaluate_instructors;
                $data['fbTestimonial'] = $event->fb_testimonial;
               
                if($sendEmail){
                    $user->notify(new SurveyEmail($data));
                }
                    
                
            }
        }

       
        $events = Event::has('transactions')->with('users')->where('view_tpl','event')->get();
        
        foreach($events as $event){
        
            $sendEmail = false;
            //$lessons = $event->topicsLessonsInstructors();
            $lessons = $event->lessons;
            foreach($event['users'] as $user){

                /*if(!isset($lessons['topics'])){
                    continue;
                }
 
                $lesson = end($lessons['topics']);

                if(!isset($lesson['lessons'])){
                    continue;
                }

                $lesson = end($lesson['lessons']);*/
                $lesson = $lessons->last();

                if(!isset($lesson['pivot']['time_ends'])){
                    continue;
                }
            
                $lastDayLesson = date('Y-m-d',strtotime($lesson['pivot']['time_ends']));

                if( $lastDayLesson !== $today ){
                    continue;
                }
 
                if($event->evaluate_instructors){
                    $sendEmail = true;
                }else if($event->evaluate_topics){
                    $sendEmail = true;
                }else if($event->fb_testimonial){
                    $sendEmail = true;
                }

                $data['firstName'] = $user->firstname;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' please take our survey';
                $data['template'] = 'emails.user.survey_email';
                $data['fb_group'] = $event->fb_group;
                $data['evaluateTopics'] = $event->evaluate_topics;
                $data['evaluateInstructors'] = $event->evaluate_instructors;
                $data['fbTestimonial'] = $event->fb_testimonial;

                if($sendEmail){
                    $user->notify(new SurveyEmail($data));
                }
                    
                
            }
        }

    }
    

    public function absences(){

        $date = date('Y-m-d');
        $events = Event::
        wherePublished(true)
        ->whereHas('lessons',function($lessonQ) use($date){
            return $lessonQ->where('event_topic_lesson_instructor.date',$date);
        })
        ->with([
            'lessons' => function($lessonQ) use($date){
                return $lessonQ->wherePivot('date',$date);
            },
            'users',
            'instructors'
            ]
        )->get();

        

        foreach($events as $event){
            
            $timeStarts = false;
            $timeEnds = false;
            $totalLessonHour = 0;

            $lessons = $event['lessons'];

            foreach($lessons as $key => $lesson){  
            
            
                $lessonHour = date('H', strtotime($lesson->pivot->time_starts));
    
                if(!$timeStarts){
                    $timeStarts = (int) date('H', strtotime($lesson->pivot->time_starts));
                }
                $timeEnds = (int) date('H', strtotime($lesson->pivot->time_ends));
            }

            if($timeStarts && $timeEnds){
           
                $totalLessonHour = ($timeEnds - $timeStarts) * 60;
                $instructorIds = $event->instructors->unique()->pluck('id')->toArray();
                
                foreach($event['users'] as $user){
                    if($user->instructor->first() && in_array($user->instructor->first()->id,$instructorIds)){
                        continue;
                    }

                    if(Absence::where('user_id',$user->id)->where('event_id',$event->id)->where('date',$date)->first()){
                        continue;
                    }
                    
                    $absence = new Absence;
                    $absence->user_id = $user->id;
                    $absence->event_id = $event->id;
                    $absence->date = $date;
                    $absence->minutes = 0;
                    $absence->total_minutes = $totalLessonHour;
        
                    $absence->save();
                }
                
                
    
            }
    

        }

    }

}
