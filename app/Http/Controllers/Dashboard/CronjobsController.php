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
use App\Model\Pages;

class CronjobsController extends Controller
{
    
    public function unroll(){

        $students = User::has('events')->with('events')->get();
        $today = date('Y-m-d');
        $today = strtotime($today);
        foreach($students as $student){

            $events = $student->events()->wherePivot('comment','enroll')->get();

            foreach($events as $event){
                $expiration = strtotime($event->pivot->expiration);

                if($today > $expiration){
                    $student->events()->detach($event->id);
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

            if(!$invoiceUser->user->first()){
                continue;
            }
            $data = [];
            $data['name'] = $invoiceUser->user->first()->firstname . ' ' . $invoiceUser->user->first()->lastname ;
            $data['firstName'] = $invoiceUser->user->first()->firstname;
            $data['eventTitle'] = $invoiceUser->event->first()->title;
            $data['subject'] = 'Knowcrunch - ' . $data['firstName'] .' your payment failed';
            $data['amount'] = round($invoiceUser->amount,2);
            $data['template'] = 'emails.user.failed_payment';
            //$data['installments'] =


            $invoiceUser->user->first()->notify(new FailedPayment($data));



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
            $data['expirationDate'] = $event->pivot->expiration;

            $sent = Mail::send('emails.student.subscription.subscription_payment_declined', $data, function ($m) use ($adminemail, $muser) {

                $fullname = $muser['name'];
                $first = $muser['first'];
                $sub = 'KnowCrunch |' . $first . ' - Subscription Payment Declined';
                $m->from($adminemail, 'Knowcrunch');
                $m->to($muser['email'], $fullname);
                //$m->cc($adminemail);
                $m->subject($sub);

            });


        }

    }

    public function generateCSVForFB(){

        $destinationPath = public_path().'/csv/fb/';
        if(!File::exists($destinationPath)) {
            //dd('ddd');
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }



        $events = Event::where('view_tpl','!=','event_free')->where('view_tpl','!=','elearning_free')->where('published',true)->whereIn('status',[0])->with('category', 'ticket','mediable')->get();
        $columns = array('id', 'title', 'description', 'availability', 'price', 'link', 'image_link', 'brand', 'google_product_category','condition');

        $file = fopen('csv/fb/fb.csv', 'w');
        fputcsv($file, $columns);

        foreach($events as $event) {

            $cat = $event->category->first() ? $event->category->first()->name : '';

            $amount = 0;

            foreach($event->ticket as $price){
                if($price->pivot->price > 0 && $price->type != 'Alumni' && $price->type != 'Special') {
                    $amount = $price->pivot->price;
                }

            }
        
            $img = url('/') . $event['mediable']['path'] . '/' . $event['mediable']['original_name'];
            fputcsv($file, array($event->id, $event->title, $event->title, 'in stock', $amount . ' EUR', url('/') . '/' . $event->slugable->slug, str_replace('\"', '', $img), 'Knowcrunch',  'Event > ' . $cat, 'new'));

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

        $events = Event::where('view_tpl','!=','event_free')->where('view_tpl','!=','elearning_free')->where('published',true)->whereIn('status',[0])->with('category', 'ticket','mediable')->get();

        $columns = array("ID", "Item Title", "Final URL", "Image URL", "Price", "Item Category", "Item Description");


        $file = fopen('csv/google/google.csv', 'w');
        fputcsv($file, $columns);

        foreach($events as $event) {

            $cat = $event->category->first() ? $event->category->first()->name : '';
            $city = null;

            $amount = 0;
            
            foreach($event->ticket as $price){
                if($price->pivot->price > 0 && $price->type != 'Alumni' && $price->type != 'Special') {
                    $amount = $price->pivot->price;
                }

            }

            $img = url('/') . $event['mediable']['path'] . '/' . $event['mediable']['original_name'];

            $summary = 'empty';
            if($event->summary != ''){
                $summary = strip_tags($event->summary);
            }

            fputcsv($file, array($event->id, $event->title,  url('/') . '/' . $event->slugable->slug, $img, $amount . ' EUR', 'Event > ' . $cat,trim($summary)));

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

            if($subscription->event->first() && $subscription->event->first()->pivot->expiration){
                $date = date_create($subscription->event->first()->pivot->expiration);
                $date = date_diff($date, $today);

                if( $date->y==0 && ( ($date->m == 1 &&  $date->d == 0) || ($date->m ==  0 && $date->d == 7))){
                    $muser['name'] = $subscription->user->firstname . ' ' . $subscription->user->lastname;
                    $muser['first'] = $subscription->user->firstname;
                    $muser['eventTitle'] =  $subscription->event->first()->title;
                    $muser['email'] = $subscription->user->email;

                    $data['firstName'] = $subscription->user->firstname;
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

                    });
                }
            }
        }

    }

    public function updateStatusField(){
        $subscriptions = Subscription::whereIn('stripe_status',['canceled','cancel'])->get();

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
                    $data['subject'] = 'KnowCrunch | ' . $data['firstName'] . ' your course expires soon';

                    $user->notify(new ExpirationMails($data));

                }elseif( $event->id !== 2304 && ($date->y == 0 && $date->m ==  0 && $date->d == 7 )){
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['expirationDate'] = date('d-m-Y',strtotime($user->pivot->expiration));

                    $data['template'] = 'emails.user.courses.week_expiration';
                    $data['subject'] = 'KnowCrunch | ' . $data['firstName'] . ' your course expires soon';

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


    public function sendSurveyMail(){

        
        $adminemail = 'info@knowcrunch.com';

        $events = Event::has('transactions')->with('users','transactions')->where('view_tpl','elearning_event')->get();
        //$events = Event::has('transactions')->where('published','true')->with('users')->get();

        $today = date_create( date('Y/m/d'));
        $count = 0;
        foreach($events as $event){
            
    
            if(!$event['transactions']->first()){
                continue;
            }
            
            
           

            foreach($event['users'] as $user){


                if( !$event->expiration ){
                    continue;
                }


                if(! ($trans = $event->transactionsByUser($user->id)->first()) ){
                    continue;
                }

                $date  = date('Y-m-d',strtotime($trans->created_at));
                $date = date_create($date);
                $date = date_diff($date, $today);

                if( $date->y==0 && $date->m == 0  && $date->d == 15 ){


                    // dd('edww');
                    
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


       


    }

    /*public function sendInvoice(){

        $invoices = InvoiceElearning::where('instalments_remaining','>=',1)->get();

        $generalInfo = \Config::get('dpoptions.website_details.settings');
        $adminemail = $generalInfo['admin_email'];


    	foreach ($invoices as $invoice) {

            $data = [];
            $date1 = strtotime($invoice->date);
            $date2 = strtotime(Carbon::today()->toDateString());

            $subUsers = DB::select('select * from stripe_subscriptions where billable_id = '  . $invoice->user_id);

            $paid = false;

            foreach($subUsers as $subUser){
                $event = explode('_',json_decode($subUser->plan)->id)[3];
                if($event == $invoice->event_id){
                    $insta = json_decode($subUser->metadata);
                    //dd($insta->installments - $insta->installments_paid);
                    if(($insta->installments - $insta->installments_paid) < $invoice->instalments_remaining){
                    //if($insta->installments == $insta->installments_paid){
                        $paid = true;
                    }

                }

            }

            if($date2 >= $date1 && $invoice->instalments_remaining >=1 && $invoice->transaction->status == 1 && $paid){

                $pdf = $invoice->generateCronjobInvoice();
                $pdf = $pdf->output();

                $muser = [];
                $muser['name'] = $invoice->user->first_name;
                $muser['first'] = $invoice->user->first_name;
                $muser['email'] = $invoice->user->email;

                $data['firstName'] = $invoice->user->first_name;
                $data['eventTitle'] = $invoice->event->title;

                $sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

                    $fullname = $muser['name'];
                    $first = $muser['first'];
                    $sub =  $first . '– H απόδειξη για την πληρωμή σου';
                    $m->from($adminemail, 'Knowcrunch');
                    $m->to($muser['email'], $fullname);
                    $m->subject($sub);
                    $m->attachData($pdf, "invoice.pdf");

                });

                $sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

                    $fullname = $muser['name'];
                    $first = $muser['first'];
                    $sub =  $first . '– H απόδειξη για την πληρωμή σου';
                    $m->from($adminemail, 'Knowcrunch');
                    $m->to('info@knowcrunch.com', $fullname);
                    $m->subject($sub);
                    $m->attachData($pdf, "invoice.pdf");

                });

            }

        }

        //$this->sendOldInvoice();

    }*/

    /*public function sendElearningWarning(){


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

                if( ($date->y == 0 && $date->m == 1 && $date->d == 0) || ($date->y == 0 && $date->m ==  0 && $date->d == 7 )){

                    // dd('edww');
                    $muser['name'] = $user->firstname . ' ' . $user->lastname;
                    $muser['first'] = $user->firstname;
                    $muser['eventTitle'] =  $event->title;
                    $muser['email'] = $user->email;

                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['expirationDate'] = $user->pivot->expiration;

                    $sent = Mail::send('emails.admin.remind_elearning', $data, function ($m) use ($adminemail, $muser) {

                        $fullname = $muser['name'];
                        $first = $muser['first'];
                        $sub =  $first . '– A reminder about the ' . $muser['eventTitle'];
                        $m->from($adminemail, 'Knowcrunch');
                        $m->to($muser['email'], $fullname);
                        $m->cc($adminemail);
                        $m->subject($sub);

                    });

                }
            }

        }

    }*/

    /*public function sendElearningHalfPeriod(){


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

                if( $date->y==0 && $date->m == 2  && $date->d == 0){

                    // dd('edww');
                    $muser['name'] = $user->firstname . ' ' . $user->lastname;
                    $muser['first'] = $user->firstname;
                    $muser['eventTitle'] =  $event->title;
                    $muser['email'] = $user->email;

                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['expirationDate'] = $user->pivot->expiration;

                    $sent = Mail::send('emails.student.elearning_half_period', $data, function ($m) use ($adminemail, $muser) {

                        $fullname = $muser['name'];
                        $first = $muser['first'];
                        $sub = 'KnowCrunch |' . $first . ' kind reminder for ' . $muser['eventTitle'];
                        $m->from($adminemail, 'Knowcrunch');
                        $m->to($muser['email'], $fullname);
                        //$m->cc($adminemail);
                        $m->subject($sub);

                    });

                }
            }
        }

    }*/
}
