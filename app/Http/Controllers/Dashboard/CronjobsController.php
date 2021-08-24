<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Invoice;
use Laravel\Cashier\Subscription;
use Mail;

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

        $adminemail = 'info@kno';

        $today = strtotime( date('Y/m/d'));
       
        $subscriptions = Subscription::where('must_be_updated','<',$today)->where('status',true)->where('email_send',false)->where('must_be_updated','!=', 0)->get();
        
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
