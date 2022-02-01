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
use App\Services\FBPixelService;
use App\Model\PaymentMethod;
use App\Notifications\SubscriptionWelcome;

class SubscriptionController extends Controller
{

    private $paymentMethod;

    public function __construct(FBPixelService $fbp)
    {
        $this->fbp = $fbp;
        $this->paymentMethod = PaymentMethod::find(2);
        $this->middleware('event.subscription')->only(['index','store']);
    }

    public function index($event,$plan)
    {     

        $plan = Plan::where('name',$plan)->first();
        $event = Event::where('title',$event)->first();
        session()->put('payment_method',$this->paymentMethod->id);

        $secretKey = env('PAYMENT_PRODUCTION') ? $this->paymentMethod->processor_options['secret_key'] : $this->paymentMethod->test_processor_options['secret_key'];
        Stripe::setApiKey($secretKey);
        
        $user = Auth::user();
        $user->asStripeCustomer();
        $data['plan'] = $plan;
        $data['event'] = $event;
        $data['eventId'] = $event->id;
        $data['cur_user'] = $user;
        $data['eventFree'] = false;

        $data['stripe_key'] = env('PAYMENT_PRODUCTION') ? $this->paymentMethod->processor_options['key'] : 
                                                                $this->paymentMethod->test_processor_options['key'];


        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');
        
        }
        else {
            $data['pay_seats_data'] = [];
        }
        
       
        
        if (Session::has('pay_bill_data')) {
            $data['pay_bill_data'] = Session::get('pay_bill_data');
        }
        else {
            $data['pay_bill_data'] = [];
        }
        
        if (Session::has('cardtype')) {
            $data['cardtype'] = Session::get('cardtype');
        }
        else {
            $data['cardtype'] = [];
        }
        
        if (Session::has('installments')) {
            $data['installments'] = Session::get('installments');
        }
        else {
            $data['installments'] = [];
        }

        
        
        $data['billname'] = '';
        $data['billsurname'] = '';
        $data['billaddress'] ='';
        $data['billaddressnum'] = '';
        $data['billpostcode'] = '';
        $data['billcity'] = '';
        $data['billafm'] = '';
        $data['billstate'] = '';
        $data['billemail'] = '';
        $data['billcountry'] = '';
        
        if( $user) {
        
            
            
            if(isset($data['pay_bill_data']) && empty($data['pay_bill_data'])) {
               
                $inv = []; $rec = [];
                if($user->invoice_details != '') {
                    $inv = json_decode($user->invoice_details, true);
                    if(isset($inv['billing']))
                        unset($inv['billing']);
                }
        
                if($user->receipt_details != '') {
                    $rec = json_decode($user->receipt_details, true);
                    if(isset($rec['billing']))
                        unset($rec['billing']);
                }
        
                $data['pay_bill_data'] = array_merge($inv, $rec);
            }
            
            $data['billname'] = isset($data['pay_bill_data']['billname']) ? $data['pay_bill_data']['billname'] : '';
            $data['billsurname'] = isset($data['pay_bill_data']['billsurname']) ? $data['pay_bill_data']['billsurname'] : '';
            $data['billaddress'] = isset($data['pay_bill_data']['billaddress']) ? $data['pay_bill_data']['billaddress'] : '';
            $data['billaddressnum'] = isset($data['pay_bill_data']['billaddressnum']) ? $data['pay_bill_data']['billaddressnum'] : '';
            $data['billpostcode'] = isset( $data['pay_bill_data']['billpostcode']) ? $data['pay_bill_data']['billpostcode'] : '';
            $data['billcity'] = isset($data['pay_bill_data']['billcity']) ? $data['pay_bill_data']['billcity'] : '';
            $data['billafm'] = isset($data['pay_bill_data']['billafm']) ? $data['pay_bill_data']['billafm'] : '';
            $data['billcountry'] = isset($data['pay_bill_data']['country']) ? $data['pay_bill_data']['billcountry'] : '';
            $data['billstate'] = isset($data['pay_bill_data']['state']) ? $data['pay_bill_data']['billstate'] : '';
            $data['billemail'] = isset($data['pay_bill_data']['billemail']) ? $data['pay_bill_data']['billemail'] : '';
        
            $ukcid = $user->kc_id;
        }
                                                        

        return view('theme.cart.new_cart.subscription.billing', $data);
       
    }

    public function checkoutIndex($event,$plan)
    {     

        $pay_bill_data = [];

        $pay_bill_data['billing'] = 1;
        $pay_bill_data['billname'] = request()->get('billname');
		$pay_bill_data['billsurname'] = request()->get('billsurname');
		$pay_bill_data['billemail'] = request()->get('billemail');
		$pay_bill_data['billaddress'] = request()->get('billaddress');
		$pay_bill_data['billaddressnum'] = request()->get('billaddressnum');
		$pay_bill_data['billpostcode'] = request()->get('billpostcode');
		$pay_bill_data['billcity'] = request()->get('billcity');
        $pay_bill_data['billcountry'] = request()->get('billcountry');
        $pay_bill_data['billstate'] = request()->get('billstate');
        $pay_bill_data['billafm'] = request()->get('billafm');


        $plan = Plan::where('name',$plan)->first();
        $event = Event::where('title',$event)->first();
        session()->put('payment_method',$this->paymentMethod->id);

        $secretKey = env('PAYMENT_PRODUCTION') ? $this->paymentMethod->processor_options['secret_key'] : $this->paymentMethod->test_processor_options['secret_key'];
        Stripe::setApiKey($secretKey);
        
        $user = Auth::user();
        $user->asStripeCustomer();
        $data['plan'] = $plan;
        $data['event'] = $event;
        $data['eventId'] = $event->id;
        $data['cur_user'] = $user;
        $data['eventFree'] = false;
        
        $data['stripe_key'] = env('PAYMENT_PRODUCTION') ? $this->paymentMethod->processor_options['key'] : 
                                                                $this->paymentMethod->test_processor_options['key'];


        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');
        
        }
        else {
            $data['pay_seats_data'] = [];
        }
        
       
        
        if (Session::has('pay_bill_data')) {
            $data['pay_bill_data'] = Session::get('pay_bill_data');
        }
        else {
            $data['pay_bill_data'] = [];
        }
        
        if (Session::has('cardtype')) {
            $data['cardtype'] = Session::get('cardtype');
        }
        else {
            $data['cardtype'] = [];
        }
        
        if (Session::has('installments')) {
            $data['installments'] = Session::get('installments');
        }
        else {
            $data['installments'] = [];
        }

        
        
        $data['billname'] = '';
        $data['billsurname'] = '';
        $data['billaddress'] ='';
        $data['billaddressnum'] = '';
        $data['billpostcode'] = '';
        $data['billcity'] = '';
        $data['billafm'] = '';
        $data['billstate'] = '';
        $data['billemail'] = '';
        $data['billcountry'] = '';
        
        if( $user) {
        
            
            
            if(isset($data['pay_bill_data']) && empty($data['pay_bill_data'])) {
               
                $inv = []; $rec = [];
                if($user->invoice_details != '') {
                    $inv = json_decode($user->invoice_details, true);
                    if(isset($inv['billing']))
                        unset($inv['billing']);
                }
        
                if($user->receipt_details != '') {
                    $rec = json_decode($user->receipt_details, true);
                    if(isset($rec['billing']))
                        unset($rec['billing']);
                }
        
                $data['pay_bill_data'] = array_merge($inv, $rec);
            }
            
            $data['billname'] = isset($data['pay_bill_data']['billname']) ? $data['pay_bill_data']['billname'] : '';
            $data['billsurname'] = isset($data['pay_bill_data']['billsurname']) ? $data['pay_bill_data']['billsurname'] : '';
            $data['billaddress'] = isset($data['pay_bill_data']['billaddress']) ? $data['pay_bill_data']['billaddress'] : '';
            $data['billaddressnum'] = isset($data['pay_bill_data']['billaddressnum']) ? $data['pay_bill_data']['billaddressnum'] : '';
            $data['billpostcode'] = isset( $data['pay_bill_data']['billpostcode']) ? $data['pay_bill_data']['billpostcode'] : '';
            $data['billcity'] = isset($data['pay_bill_data']['billcity']) ? $data['pay_bill_data']['billcity'] : '';
            $data['billafm'] = isset($data['pay_bill_data']['billafm']) ? $data['pay_bill_data']['billafm'] : '';
            $data['billcountry'] = isset($data['pay_bill_data']['country']) ? $data['pay_bill_data']['billcountry'] : '';
            $data['billstate'] = isset($data['pay_bill_data']['state']) ? $data['pay_bill_data']['billstate'] : '';
            $data['billemail'] = isset($data['pay_bill_data']['billemail']) ? $data['pay_bill_data']['billemail'] : '';
        
            $ukcid = $user->kc_id;
        }
                                                        
        Session::put('pay_bill_data', $pay_bill_data);
        return view('theme.cart.new_cart.subscription.checkout', $data);
       
    }

    public function store(Request $request, $event,$plan)
    {
  
        $user = Auth::user();

        $plan = Plan::where('name',$plan)->first();
        $event = Event::where('title',$event)->first();

        if(env('PAYMENT_PRODUCTION')){
            Stripe::setApiKey($this->paymentMethod->processor_options['secret_key']);
        }else{
            Stripe::setApiKey($this->paymentMethod->test_processor_options['secret_key']);
        }
		session()->put('payment_method',$this->paymentMethod->id);

    
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
        
        $user->asStripeCustomer();
        if(!$user->stripe_id){
                
            $options=['name' => $user['firstname'] . ' ' . $user['lastname'], 'email' => $user['email']];
            $user->createAsStripeCustomer($options);
           
            $stripe_ids = json_decode($user->stripe_ids,true) ? json_decode($user->stripe_ids,true) : [];
            $stripe_ids[] =$user->stripe_id;

            $user->stripe_ids = json_encode($stripe_ids);
            $user->save();
        }


        try {

            $charge = $user->newSubscription($plan->name, $plan->stripe_plan)->trialDays($plan->trial_days)->create($request->payment_method, 
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

                $user->subscriptionEvents()->attach($event->id,['subscription_id'=>$charge['id'],'payment_method'=>$this->paymentMethod->id]);

            
                $data = [];  
                /*$muser = [];
                $muser['name'] = $user->firstname;
                $muser['first'] = $user->firstname;
                $muser['email'] = $user->email;*/
                //$muser['event_title'] = $sub->eventable->event->title;

                $subEnds = $plan->trial_days && $plan->trial_days > 0 ? $plan->trial_days : $plan->getDays();
                $subEnds=date('d-m-Y', strtotime("+$subEnds days"));

                $data['firstName'] = $user->firstname;

                $data['name'] = $user->firstname . ' ' . $user->lastname;
                $data['email'] = $user->email;
                $data['amount'] = $charge->price;
                $data['position'] = $user->job_title;
                $data['company'] = $user->company;
                $data['mobile'] = $user->mobile;
                $data['userLink'] = url('/') . "/admin/user/" . $user['id'] ."/edit";

                $data['eventTitle'] = $event->title;
                $data['eventFaq'] = url('/') . '/' .$event->getSlug().'#faq';
                $data['eventSlug'] = url('/') . '/myaccount/elearning/' . $event->title;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] .' to our annual subscription';
                $data['template'] = 'emails.user.subscription_welcome';
                $data['subscriptionEnds'] = $subEnds;
                /*$data['sub_type'] = $plan->name;
                $data['sub_price'] = $plan->cost;
                $data['sub_period'] = $plan->period();*/
        
                $user->notify(new SubscriptionWelcome($data));

                $adminemail = 'info@knowcrunch.com';
        
                /*$sent = Mail::send('emails.admin.admin_info_subscription_registration', $data, function ($m) use ($adminemail) {
        
                    
                    $sub = 'Knowcrunch - New subscription';
                    $m->from($adminemail, 'Knowcrunch');
                    $m->to($adminemail, 'Knowcrunch');
                    $m->subject($sub);
                
                });*/
  
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
        }catch(\Stripe\Exception\CardException $e) {
            \Session::put('dperror',$e->getMessage());
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

        $this->fbp->sendStartTrialEvent();

        return view('theme.myaccount.subscription.subscription-success', $data);

    }

    public function change_status(Request $request){
        
        $user = Auth::user();

        $subscription = $user->subscriptions()->where('id',$request->sub_id)->first();//Subscription::where(['id' => $request->sub_id, 'user_id' => $currentuser->id])->first();

        $sub_id_stripe = $subscription['stripe_id'];

        $paymentMethod = PaymentMethod::find(2);
        if(env('PAYMENT_PRODUCTION')){
            Stripe::setApiKey($paymentMethod->processor_options['secret_key']);
        }else{
            Stripe::setApiKey($paymentMethod->test_processor_options['secret_key']);
        }
        session()->put('payment_method',$paymentMethod->id);


        try{
            if($request->status == 'Cancel'){
                        
                $subscription->status = false;
                $subscription->stripe_status = 'cancelled';
                $subscription->save();
                $subscription = $subscription->cancel();
             
    
            }else if($request->status == 'Active'){
    
    
                $subscription->status = true;
                $subscription->stripe_status = 'active';
                $subscription->save();
                $subscription = $subscription->resume();
            
    
            }
        }catch(\Stripe\Exception\InvalidArgumentException $e){
            //dd($subscription);
            $subscription->status = false;
            $subscription->save();
        }

        

        echo json_encode($subscription);



    }

}
