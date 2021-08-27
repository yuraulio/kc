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

            $data = [];
            $data['name'] = $invoiceUser->user->first()->firstname . ' ' . $invoiceUser->user->first()->lastname ;
            $data['eventTitle'] = $invoiceUser->event->first()->title;
            //$data['installments'] =

            $sent = Mail::send('emails.admin.failed_stripe_payment', $data, function ($m) use ($adminemail) {

                $sub =  'Αποτυχημένη πληρωμή';
                $m->from($adminemail, 'Knowcrunch');
                $m->to('info@knowcrunch.com');
                $m->subject($sub);

            });

            $invoiceUser->email_sent = true;
            $invoiceUser->save();

        }



    }

    public function sendSubscriptionNonPayment(){

        $adminemail = 'info@knowcrunch.com';

        $today = strtotime( date('Y/m/d'));

        $subscriptions = Subscription::where('must_be_updated','<',$today)->where('stripe_status','active')->where('email_send',false)->where('must_be_updated','!=', 0)->get();

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
                if($price->pivot->price > 0) {
                    $amount = $price->pivot->price;
                }

            }

            $img = url('/') . $event['mediable']['path'] . '/' . $event['mediable']['original_name'];
            fputcsv($file, array($event->id, $event->title, $event->title, 'in stock', $amount . ' EUR', url('/') . '/' . $event->slug, str_replace('\"', '', $img), 'Knowcrunch',  'Event > ' . $cat, 'new'));

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
                if($price->pivot->price > 0) {
                    $amount = $price->pivot->price;
                }
    
            }

            $img = url('/') . $event['mediable']['path'] . '/' . $event['mediable']['original_name'];

            $summary = 'empty';
            if($event->summary != ''){
                $summary = strip_tags($event->summary);
            }
           
            fputcsv($file, array($event->id, $event->title,  url('/') . '/' . $event->slug, $img, $amount . ' EUR', 'Event > ' . $cat,trim($summary)));
            
        }
        fclose($file);
        
        return back();

    }

    public function fbGoogleCsv(){
        $this->generateCSVForGoogle();
        $this->generateCSVForFB();
    }


    public function sendElearningWarning(){


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

    }


    public function sendElearningHalfPeriod(){


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
}
