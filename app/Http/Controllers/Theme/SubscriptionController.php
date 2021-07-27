<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Event;
use App\Model\Plan;
use Auth;
use \Stripe\Stripe;
use Mail;
use Session;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        //$this->middleware('event.subscription')->only(['index','store']);
    }


    public function index($event,$plan)
    {     
        $plan = Plan::where('name',$plan)->first();
        $event = Event::where('title',$event)->first();
        session()->put('payment_method',$event->paymentMethod->first()->id);
        $stripe = Stripe::setApiKey($event->paymentMethod->first()->processor_options['secret_key']);
        
        $user = Auth::user();
        $user->asStripeCustomer();
        $data['plan'] = $plan;
        $data['event'] = $event;
        $data['cur_user'] = $user;
        $data['default_card'] = $user->defaultPaymentMethod() ? $user->defaultPaymentMethod()->card : false;
        
        
        $data['stripe_key'] = env('PAYMENT_PRODUCTION') ? $event->paymentMethod->first()->processor_options['key'] : 
                                                                $event->paymentMethod->first()->test_processor_options['key'];

        return view('theme.cart.subscription-form', $data);
       
    }

    public function store(Request $request, $event,$plan)
    {
         
        $user = Auth::user();

        $plan = Plan::where('name',$plan)->first();
        $event = Event::where('title',$event)->first();

        if(env('PAYMENT_PRODUCTION')){
            Stripe::setApiKey($user->events->where('id',$event->id)->first()->paymentMethod->first()->processor_options['secret_key']);
        }else{
            Stripe::setApiKey($user->events->where('id',$event->id)->first()->paymentMethod->first()->test_processor_options['secret_key']);
        }
		session()->put('payment_method',$user->events->where('id',$event->id)->first()->paymentMethod->first()->id);

    
        if (Session::has('pay_bill_data')) {
            $pay_bill_data = Session::get('pay_bill_data');
            $bd = json_encode($pay_bill_data);
        }
        else {
            $bd = '';
            $pay_bill_data = [];
        }

        $temp = [];
        if(isset($pay_bill_data)) {
            $temp = $pay_bill_data;
            if($temp['billing'] == 1) {
                $temp['billing'] = 'Receipt requested';
                $st_name =  $temp['billname'] . ' ' . $temp['billsurname'];
                $st_tax_id = 'EL'.$temp['billafm'];
                $st_line1 = $temp['billaddress'] . ' ' . $temp['billaddressnum'];
                $st_postal_code = $temp['billpostcode'];
                $st_city = $temp['billcity'];
           //     $st_phone = $temp['billmobile'];

            }
            else {
                $temp['billing'] = 'Invoice requested';
    
                $st_name = $temp['companyname'] . ' ' . $temp['companyprofession'];
                $st_tax_id = $temp['companyafm'] . ' ' . $temp['companydoy'];
                $st_line1 = $temp['companyaddress'] . ' ' . $temp['companyaddressnum'];
                $st_postal_code = $temp['companypostcode'];
                $st_city = $temp['companycity'];
                $st_email = $temp['companyemail'];
                $st_phone = '';

            }
        }

        if($plan->interval == 'month')
        {
            $days = $plan->interval_count * 30;
            $sub_end = strtotime("+" . $days . "day");
        }
        elseif($plan->interval == 'year')
        {
            $sub_end = strtotime("+365 day");
        }elseif($plan->interval == 'week'){
            $days = $plan->interval_count * 7;
            $sub_end = strtotime("+" . $days . "day");
        }
        elseif($plan->interval == 'day'){
            $days = $plan->interval_count;
            $sub_end = strtotime("+" . $days . "day");
        }

        try {

            $charge = $user->newSubscription($plan->name, $plan->stripe_plan)->trialDays($plan->trial_days)->create($user->defaultPaymentMethod()->id, 
            ['email' => $user->email]);
            
            $charge->price = $plan->cost;
            $charge->save();

            $charge['status'] = 'succeeded';            
            $date_sub_end = date('Y/m/d H:i:s', $sub_end);

            if($charge){
                
                $subscription = $user->subscriptions()->where('id',$charge['id'])->first();
                //dd($user->events()->where('event_id',$event->id)->first());
                /*if(!$user->events()->where('event_id',$event)->first()){
                    $user->events()->attach($event->id);
                }*/

                $user->subscriptionEvents()->attach($event->id,['subscription_id'=>$charge['id']]);

            
                $data = [];  
                $muser = [];
                $muser['name'] = $user->firstname;
                $muser['first'] = $user->firstname;
                $muser['email'] = $user->email;
                //$muser['event_title'] = $sub->eventable->event->title;

                $data['firstName'] = $user->firstname;
                $data['sub_type'] = $plan->name;
                $data['sub_price'] = $plan->cost;
                $data['sub_period'] = $plan->period();
        
                $adminemail = 'info@knowcrunch.com';
        
                $sent = Mail::send('emails.student.subscription.subscription_first', $data, function ($m) use ($adminemail, $muser) {
        
                    $fullname = $muser['name'];
                    $first = $muser['first'];
                    $sub = 'KnowCrunch |' . $first . ' – Your subscription has been activated!';
                    $m->from($adminemail, 'Knowcrunch');
                    $m->to($muser['email'], $fullname);
                    $m->subject($sub);
                
                });
  
                Session::forget('pay_seats_data');
                Session::forget('transaction_id');
                Session::forget('cardtype');
                Session::forget('installments');
                //Session::forget('pay_invoice_data');
                Session::forget('pay_bill_data');
                Session::forget('deree_user_data');

                return redirect('/myaccount/subscription-success'); 
                //return redirect('/myaccount'); 
            }else{
                return 'have error';
            }
        }catch (Exception $e) {
            //dd('edwww2');
             \Session::put('dperror',$e->getMessage());
             return back();
            // return redirect('/info/order_error');
        }
        catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            //dd('edwww3'); 
            \Session::put('dperror',$e->getMessage());
            return back();
             //return redirect('/info/order_error');
        }
        catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            //dd($e);
            \Session::put('dperror',$e->getMessage());
            //return redirect('/info/order_error');
            return back();
        }
        catch(\Cartalyst\Stripe\Api\Exception\ServerErrorException $e) {
            //dd($e);
            \Session::put('dperror',$e->getMessage());
            //return redirect('/info/order_error');
            return back();
        }
    }

    public function orderSuccess(){

        //$data['filter_type'] = Category::where('id', 12)->first()->getDescendants()->where('status',1)->where('type',0)->toHierarchy();
        $data['info']['success'] = true;
        $data['info']['title'] = '<h1>Subscription successful</h1>';
        $data['info']['message'] = '<h2>Thank you for being loyal and keeping yourself updated.<br/>Keep thriving!</h2>
        <p>An email with more information is on its way to your inbox.</p>';
        //$data['info']['transaction'] = $this->transaction;
        //$data['info']['statusClass'] = 'success';

        return view('theme.myaccount.subscription.subscription-success', $data);

    }


    public function change_status(Request $request){
        
        $user = Auth::user();

        $subscription = $user->subscriptions()->where('id',$request->sub_id)->first();//Subscription::where(['id' => $request->sub_id, 'user_id' => $currentuser->id])->first();

        $sub_id_stripe = $subscription['stripe_id'];

        if($request->status == 'Cancel'){
            $subscription->status = false;
            $subscription->save();
            $subscription = $subscription->cancel();
         

        }else if($request->status == 'Active'){
            $subscription->status = true;
            $subscription->save();
            $subscription = $subscription->resume();
           
        }

        echo json_encode($subscription);



    }


}
