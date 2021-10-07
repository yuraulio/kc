<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Cart as Cart;
use Redirect;
use Mail;
use App\Model\Transaction;
use App\Model\PaymentMethod;
use Auth;
use App\Model\Event;
use Flash;
use App\Model\ShoppingCart;
use Session;
use PDF;
use App\Model\Option;
use App\Model\User;
use App\Model\Activation;
use App\Model\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \Carbon\Carbon;
use App\Notifications\CreateYourPassword;
use App\Model\CookiesSMS;

class InfoController extends Controller
{

    public function __construct()
    {
        
        if (Session::has('transaction_id')) {
            $transaction = Transaction::where('id', Session::get('transaction_id'))->first();
            //->with('user')

            //$transaction = Transaction::where('id', 4)->with('user','account.defaultStore')->first();
            if ($transaction) {
                $this->transaction = $transaction->toArray();
            } else {
                $this->transaction = [];
            }
        } else {
            $this->transaction = [];
        }
        //dd($this->transaction);
    }

    public function orderSuccess()
    {

    	$data = array();
        //$data['lang'] = $_ENV['LANG'];
        //$data['website'] = $_ENV['WEBSITE'];

        $data['pay_methods'] = array();
    	$data['pay_methods'] = PaymentMethod::whereIn('status', [1,2])->get();
               
        if (Session::has('transaction_id')) {
         	 $transaction = Transaction::where('id', Session::get('transaction_id'))->first();
                
         	 if ($transaction) {
                $this->transaction = $transaction->toArray();
            } else {
                $this->transaction = [];
            }
         }
         else {
            $this->transaction = [];
        }

        $data['info']['success'] = true;
        $data['info']['title'] = '<h1>Booking successful</h1>';
        $data['info']['message'] = '<h2>Thank you and congratulations!<br/>We are very excited about you joining us. We hope you are too!</h2>
        <p>An email with more information is on its way to your inbox.</p>';
        $data['info']['transaction'] = $this->transaction;
        $data['info']['statusClass'] = 'success';

        if (isset($this->transaction['payment_response'])) {
	        $cart = Cart::content();

	        foreach ($cart as $item) {


                //Update Stock

                $thisevent = Event::where('id', '=', $item->options['event'])->first();

	        	$stockHelper = $thisevent->ticket->where('ticket_id', $item->id)->first();
	        	$newstock = $stockHelper->pivot->quantity - $item->qty;
	        	$stockHelper->pivot->quantity = $newstock;
	        	$stockHelper->pivot->save();
               
                //check for active and stockable tickets

                if ($newstock == 0) {

                    $eventStockHelper = $thisevent->ticket;

                    $globalSoldOut = 1;

                    foreach ($eventStockHelper as $evalue) {
                        $ticketstock = $evalue->pivot->quantity;
                        if ($ticketstock > 0) {
                            $globalSoldOut = 0;
                        }
                    }

                    //Update event status to soldout if no ticket stock
                    //$newstock == 0
                    if ($globalSoldOut == 1) {
                        $thisevent->status = 2;
                        $thisevent->save();
                    }
                }
            
                if($this->transaction['amount'] - floor($this->transaction['amount'])>0){
                    $tr_price = number_format($this->transaction['amount'] , 2 , '.', ',');
                }else{
                    $tr_price = number_format($this->transaction['amount'] , 0 , '.', '');
                }
                
                $data['tigran'] = ['evid' => $item->options['event'], 'price' => $tr_price, 'transid' => $this->transaction['id']];
	        }

	        if ($transaction) {
                
	    		$this->createUsersFromTransaction($transaction);

			}
	    }

	    //$this->sendEmails($trans_id);


        //DELETE SAVED CART IF USER LOGGED
        if($user = Auth::user()) {
           
            $existingcheck = ShoppingCart::where('identifier', $user->id)->first();
            if($existingcheck) {
                $existingcheck->delete($user->id);
            }
            //$user->cart->delete();
        }

         //DESTROY CART HERE AND SESSION vars
        Cart::instance('default')->destroy();
        Session::forget('pay_seats_data');
        Session::forget('transaction_id');
        Session::forget('cardtype');
        Session::forget('installments');
        //Session::forget('pay_invoice_data');
        Session::forget('pay_bill_data');
        Session::forget('deree_user_data');
        Session::forget('user_id');
        Session::forget('coupon_code');
        Session::forget('coupon_price');
        
        
        if (isset($this->transaction['payment_response'])) {
        	return view('theme.cart.cart', $data);
        }
        else {
        	return Redirect::to('/');
        }

    }


    public function orderError()
    {
       
    	$data = array();
        
        $data['pay_methods'] = array();
    	

        $cart = Cart::content();

        $data['eventId'] = '';
        $data['categoryScript'] = '';
        
        foreach($cart as $cart){

            $ev = Event::find($cart->options['event']);
            if ($ev->category->first()){
                
                $data['categoryScript'] = 'Event > ' . $ev->category->first()->name;
                   
                
                    
            }

            $data['eventId'] = $cart->options['event'];
        }
        
        $data['eventtickets'] = $ev->ticket;
        $data['hours'] = $ev->hours;
        $data['pay_methods'] = $ev->paymentMethod->first();

        if (Session::has('transaction_id')) {
            $transaction = Transaction::where('id', Session::get('transaction_id'))->first();
              
            if ($transaction) {
              $this->transaction = $transaction->toArray();
          } else {
              $this->transaction = [];
          }
       }
       else {
          $this->transaction = [];
      }
       

        if (Session::has('transaction_id')) {
         	 $transaction = Transaction::where('id', Session::get('transaction_id'))->first();

           

         	 if ($transaction) {
                  
                $this->transaction = $transaction->toArray();
            } else {
                $this->transaction = [];
            }
         	//dd($transaction);
         }
         else {
            $this->transaction = ['payment_response' => 'Card is not valid'];
        }

        $data['info']['success'] = false;
        $data['info']['title'] = '<h3>It seems something went wrong..</h3>';
        $data['info']['message'] = "Your payment didn't go through. Please check your credit or debit card limit or just contact us.";
        $data['info']['transaction'] = $this->transaction;
        $data['info']['statusClass'] = 'danger';
        //return view('admin.info.order_error', $data);
      
        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');
        }
        else {
            $data['pay_seats_data'] = [];
        }

        /*if (Session::has('pay_invoice_data')) {
            $data['pay_invoice_data'] = Session::get('pay_invoice_data');
        }
        else {
            $data['pay_invoice_data'] = [];
        }*/

        if (Session::has('pay_bill_data')) {
            $data['pay_bill_data'] = Session::get('pay_bill_data');
        }
        else {
            $data['pay_bill_data'] = [];
        }

        if (Session::has('deree_user_data')) {
            $data['deree_user_data'] = Session::get('deree_user_data');
        }
        else {
            $data['deree_user_data'] = [];
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

	    Session::forget('deree_user_data');
       

        $data['city'] = '';
        $data['duration'] = '';
        return view('theme.cart.cart', $data);
    }

    public function createUsersFromTransaction($transaction)
    {
    	/*
    	1. Knowcrunch Student ID
		KC - KnowCrunch
		YY - year of registration e.g. 17
		MM - month of registration e.g. 01
		ID - next available ID on the system 4-digit 0001-9999 e.g. 0129
		KC-17010004
		*/

		$KC = "KC-";
		$time = strtotime($transaction->placement_date);
		$MM = date("m",$time);
		$YY = date("y",$time);

		
		$option = Option::where('abbr','website_details')->first();
		// next number available up to 9999
		$next = $option->value;
        
        $pay_seats_data = $transaction['status_history'][0]['pay_seats_data'];
        if(isset($transaction['status_history'][0]['deree_user_data'])) {
             $deree_user_data = $transaction['status_history'][0]['deree_user_data'];
        }
        else {
             $deree_user_data = [];
        }
       
       	$pay_bill_data = $transaction['status_history'][0]['pay_bill_data'];
           
        if(isset($transaction['status_history'][0]['cardtype'])){
            $cardtype = $transaction['status_history'][0]['cardtype'];
        }
           
        $installments = $transaction['status_history'][0]['installments'];

        $emailsCollector = [];

        if (isset($transaction['billing_details']['billing'])) {
            if ($transaction['billing_details']['billing'] == 2) {
                
                $invoice = 'YES';
            }
            else {
                $invoice = 'NO';
            }
        }
        else {
                $invoice = 'NO';
        }


        if (isset($transaction->status_history[0]['cart_data'])) {

            $cart = $transaction->status_history[0]['cart_data'];

            foreach ($cart as $akey => $avalue) {
                $evid = $avalue['options']['event'];
                $tickettypedrop = $avalue['options']['type'];
                $ticketid = $avalue['id'];
                if ($evid && $evid > 0) {
                    break;
                }
            }

           
        
            //get event name and date from cart
            $thisevent = Event::where('id', '=', $evid)->first();
           
            

            $specialseats = 0;
            $thisticket = $thisevent->ticket->where('ticket_id', $ticketid)->first();
            $tickettypename = $thisticket->type; // e.g. Early Birds

            $eventname = '';
            $eventdate = '';
            $eventcity  = '';
            $elearning = false;
            $expirationDate = '';
            $eventslug = '';
            $stripe = false;

            if ($thisevent) {

                $stripe = ($thisevent->paymentMethod->first() && $thisevent->paymentMethod->first()->id !== 1);
                if($thisevent->view_tpl === 'elearning_event'){

                    $elearning = true;
                    $eventslug = $thisevent->slug;
                }
              //  dd($eventslug);
                $eventname = $thisevent->title;
                $eventcity = '';//$thisevent->categories->where('parent_id',9)->first()->name;
                $eventdate = $thisevent->summary1->where('section','date')->first() ? $thisevent->summary1->where('section','date')->first()->title : '';
                if($thisevent->city->first() != null){
                    $eventcity = $thisevent->city->first()->name;
                }
               
            }
            else {
                $eventname = 'EventName';
                $eventdate = 'EventDate';
               
                $eventcity  = 'EventCity';
            }
        }

        //if(!$elearning){
        if($thisevent->paymentMethod->first() && $thisevent->paymentMethod->first()->id == 1){
            $transaction->event()->save($thisevent);
        }

        //Collect all users from seats
        $newmembersdetails = [];
        //dd($pay_seats_data['emails']);
        foreach ($pay_seats_data['emails'] as $key => $value) {

    		$thismember = [];
    		$thismember['firstname'] = $pay_seats_data['names'][$key];
    		$thismember['lastname'] = $pay_seats_data['surnames'][$key];
    		$thismember['email'] = $pay_seats_data['emails'][$key];
        
            if(isset($deree_user_data[$value])) {
                $thismember['password'] = $deree_user_data[$value];
            }
            else {
                $thismember['password'] = $thismember['email'] . '-knowcrunch';
            }
            
            $thismember['mobile'] = $pay_seats_data['mobiles'][$key];
            $thismember['country_code'] = $pay_seats_data['countryCodes'][$key];
            
            if(isset($pay_seats_data['jobtitles'][$key])){
                $thismember['job_title'] = $pay_seats_data['jobtitles'][$key];
                if(isset($pay_seats_data['companies'][$key]))
                    $thismember['company'] = $pay_seats_data['companies'][$key];
            }
            
            if(isset($pay_seats_data['afms'][$key]))
                $thismember['afm'] = $pay_seats_data['afms'][$key];

    		$checkemailuser = User::where('email', '=', $thismember['email'])->first();
            $expiration_date = '';
    		if ($checkemailuser) {
                //if(!$elearning){
                    $transaction->user()->save($checkemailuser);
                //}

                if($elearning){
                    $invoice = $transaction->invoice()->first();
                    if($invoice){
                        $invoice->user()->save($checkemailuser);

                    }
                }

                if ($evid && $evid > 0) {

                    $today = date('Y/m/d'); 
                   

                    if($thisevent->expiration){
                        $monthsExp = '+' . $thisevent->expiration .'months';
                        $expiration_date = date('Y-m-d', strtotime($monthsExp, strtotime($today)));
                    }

                    if($tickettypedrop == 7){
                        //$tmp = EventStudent::firstOrCreate(['event_id' => $evid, 'student_id' => $checkemailuser->id, 'trans_id' => $transaction->id,'comment'=>'unilever']);
                        $thisevent->users()->save($checkemailuser,['comment'=>'unilever','expiration'=>$expiration_date,'paid'=>true]);
                    }else{

                        if($transaction->coupon_code != ''){
                            //$tmp = EventStudent::firstOrCreate(['event_id' => $evid, 'student_id' => $checkemailuser->id, 'trans_id' => $transaction->id,'comment'=>'coupon']);
                            $thisevent->users()->save($checkemailuser,['comment'=>'coupon','expiration'=>$expiration_date,'paid'=>true]);
                        }else{
                            $thisevent->users()->save($checkemailuser,['expiration'=>$expiration_date,'paid'=>true]);
                        }

                    }
                    $thisevent->tickets()->save($thisticket,['user_id' => $checkemailuser->id]);
                }

                //SHOULD but back used deree id?
                
                $fullname = $checkemailuser->firstname . ' ' . $checkemailuser->lastname;
                $firstname = $checkemailuser->firstname;

                $emailsCollector[] = ['email' => $checkemailuser->email, 'name' => $fullname, 'first' => $firstname,'id' => $checkemailuser->id];

                //Update user details with the given ones

                $checkemailuser->firstname = $thismember['firstname'];
                $checkemailuser->lastname = $thismember['lastname'];
                $checkemailuser->mobile = $thismember['mobile'];
                $checkemailuser->country_code = $thismember['country_code'];
                $checkemailuser->job_title = isset($thismember['job_title']) ? $thismember['job_title'] : '';

                /*$transactionBillingDetails = json_decode($transaction->billing_details,true);
                if(isset($transactionBillingDetails['billing']) && $transactionBillingDetails['billing'] == 1){
                    $checkemailuser->receipt_details = $transactionBillingDetails;
                }else if(isset($transactionBillingDetails['billing']) && $transactionBillingDetails['billing'] == 2){
                    $checkemailuser->invoice_details = $transactionBillingDetails;
                }*/

                if(isset($thismember['company']))
                    $checkemailuser->company = $thismember['company'];
                
                if(isset($thismember['afm']))
                    $checkemailuser->afm = $thismember['afm'];

                if($checkemailuser->partner_id == '' && isset($deree_user_data[$value]))
                    $checkemailuser->partner_id = $deree_user_data[$value];
                
                if($checkemailuser->kc_id == '') {
                    $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);
                    $knowcrunch_id = $KC.$YY.$MM.$next_kc_id;
                    $checkemailuser->kc_id = $knowcrunch_id;
                    $checkemailuser->save();
                    //$thismember['password'] =  $knowcrunch_id;
                    
                    if ($next == 9999) {
                        $next = 1;
                    }
                    else {
                        $next = $next + 1;
                    }
                }

                $checkemailuser->save();

                if(!$checkemailuser->statusAccount->completed){
                    
                    $checkemailuser->notify(new CreateYourPassword($checkemailuser));

                    $cookieValue = base64_encode($checkemailuser->id . date("H:i"));
                    setcookie('auth-'.$checkemailuser->id, $cookieValue, time() + (1 * 365 * 86400), "/"); // 86400 = 1 day
                
                    $coockie = new CookiesSMS;
                    $coockie->coockie_name = 'auth-'.$checkemailuser->id;
                    $coockie->coockie_value = $cookieValue;
                    $coockie->user_id = $checkemailuser->id;
                    $coockie->sms_code = -1;
                    $coockie->sms_verification = true;

                    $coockie->save();

                }
                
	        
    		}
    		else{
               
    			$newmembersdetails[] = $thismember;
    			$fullname = $thismember['firstname'] . ' ' . $thismember['lastname'];
    			$firstname = $thismember['firstname'];
                $emailsCollector[] = ['email' => $thismember['email'], 'name' => $fullname, 'first' => $firstname];
                

    		}
        }

        $groupEmailLink = '';
        if ($evid && $evid == 2068) {
            $groupEmailLink = 'https://www.facebook.com/groups/846949352547091';
        }else{
            $groupEmailLink = 'https://www.facebook.com/groups/elearningdigital/';
        }
		$extrainfo = [$tickettypedrop, $tickettypename, $eventname, $eventdate, $specialseats, 'YES',$eventcity,$groupEmailLink,$expiration_date];

        //Create new collected users

        $helperdetails = [];

        foreach ($newmembersdetails as $key => $member) {
          
            $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);
            $knowcrunch_id = $KC.$YY.$MM.$next_kc_id;
            $member['password'] = Hash::make($KC.$YY.$MM.$next_kc_id);
            $user = User::create($member);
          
        	$code = Activation::create([
                'user_id' => $user->id,
                'code' => Str::random(40),
                'completed' => true,
            ])->code;
        	//$role = Role::findRoleBySlug('know-crunch');
            $user->role()->attach(7);

            //CHECK FOR NON REQUIRED FIELDS
            $user->mobile = $member['mobile'];
            $user->country_code = $member['country_code'];
    		$user->job_title = $pay_seats_data['jobtitles'][$key];
            if(isset($pay_seats_data['companies'][$key]))
                $user->company = $pay_seats_data['companies'][$key];
    		

            $connow = Carbon::now();
            $clientip = '';
            $clientip = \Request::ip();
            $user->terms = 1;
            $user->consent = '{"ip": "' . $clientip . '", "date": "'.$connow.'" }';


            if(isset($deree_user_data[$value])) {
                $user->partner_id = $deree_user_data[$value];
            }
            else {
                $user->partner_id = '';
            }

    		$user->kc_id = $knowcrunch_id;
            $user->terms = 1;

            if(isset($pay_seats_data['studentId'][$key])) {
                $user->student_type_id = $pay_seats_data['studentId'][$key];
            }
            else {
                $user->student_type_id = '';
            }
            if(isset($pay_seats_data['afms'][$key])) {
                $user->afm = $pay_seats_data['afms'][$key];
            }
            else {
                $user->afm = '';
            }

           
            $user->save();
            $transaction->user()->save($user);
            //if($elearning){
            if($thisevent->paymentMethod->first() && $thisevent->paymentMethod->first()->id !== 1){

                $invoice = $transaction->invoice()->first();
                if($invoice){
                    $invoice->user()->save($user);
                }
            }

            // Send the activation email
            /*$sent = Mail::send('activation.emails.activate_groupof2+', compact('user', 'code'), function ($m) use ($user) {
                $m->to($user->email)->subject('Activate Your Account');
            });*/
            $user->notify(new CreateYourPassword($user));

            $helperdetails[$user->email] = ['kcid' => $user->kc_id, 'deid' => $user->partner_id, 'stid' => $user->student_type_id, 'jobtitle' => $user->job_title, 'company' => $user->company, 'mobile' => $user->mobile];

    		//Associate first user with transaction
            /*if ($key == 0) {
                $transaction->user_id = $user->id;
                $transaction->save();
        	}*/

            //Save taxonomy Event_student
            if ($evid && $evid > 0) {

                $today = date('Y/m/d'); 
                $expiration_date = '';

                if($thisevent->expiration){
                    $monthsExp = '+' . $thisevent->expiration .'months';
                    $expiration_date = date('Y-m-d', strtotime($monthsExp, strtotime($today)));
                }

                if($tickettypedrop == 7){
                        $thisevent->users()->save($user,['comment'=>'unilever','expiration'=>$expiration_date,'paid'=>true]);
                }else{

                    if($transaction->coupon_code != ''){
                        $thisevent->users()->save($user,['comment'=>'coupon','expiration'=>$expiration_date,'paid'=>true]);
                    }else{
                        $thisevent->users()->save($user,['expiration'=>$expiration_date,'paid'=>true]);
                    }

                }
                $thisevent->tickets()->save($thisticket,['user_id' => $user->id]);
            }

            if ($next == 9999) {
                $next = 1;
            }
            else {
                $next = $next + 1;
            }
        }

        $option->value=$next;
        $option->save();
        
        $this->sendEmails($transaction, $emailsCollector, $extrainfo, $helperdetails, $elearning, $eventslug, $stripe);

    }

    public function sendEmails($transaction, $emailsCollector, $extrainfo, $helperdetails, $elearning, $eventslug,$stripe)
    {
       // dd($elearning);
        // 5 email, admin, user, 2 deree, darkpony
    	//$generalInfo = \Config::get('dpoptions.website_details.settings');
        $adminemail = 'info@knowcrunch.com';

        $data = [];
     
    	foreach ($emailsCollector as $key => $muser) {
            $data['user'] = $muser;
            
    		$data['trans'] = $transaction;
    		$data['extrainfo'] = $extrainfo;
            $data['helperdetails'] = $helperdetails;
            $data['elearning'] = $elearning;
            $data['eventslug'] = $eventslug;
           
            //dd($helperdetails);

            if($elearning){
  
                $sent = Mail::send('emails.admin.info_new_registration_elearning', $data, function ($m) use ($adminemail, $muser) {

    			    $fullname = $muser['name'];
    			    $first = $muser['first'];
                    $sub = 'Knowcrunch - ' . $first . ' your registration has been completed.';
	                $m->from($adminemail, 'Knowcrunch');
	                $m->to($muser['email'], $fullname);
                    $m->subject($sub);
                   // $m->attachData($pdf->output(), "invoice.pdf");
                });

            }else{
                
                $sent = Mail::send('emails.admin.info_new_registration', $data, function ($m) use ($adminemail, $muser) {

    			    $fullname = $muser['name'];
    			    $first = $muser['first'];
                    $sub = 'Knowcrunch - ' . $first . ' your registration has been completed.';
	                $m->from($adminemail, 'Knowcrunch');
	                $m->to($muser['email'], $fullname);
	                $m->subject($sub);
                });
            }
    	}

      
        if($stripe){

            //dd($transaction);
            //dd($transaction->invoice);
            
            $data = [];  
            $muser = [];
            $muser['name'] = $transaction->user->first()->firstname;
            $muser['first'] = $transaction->user->first()->firstname;
            $muser['email'] = $transaction->user->first()->email;
            $muser['id'] = $transaction->user->first()->id;
            $muser['event_title'] =$transaction->event->first()->title;
            $data['firstName'] = $transaction->user->first()->firstname;
            $data['eventTitle'] =$transaction->event->first()->title;

            if(Session::has('installments') && Session::get('installments') <= 1){
                
                $pdf = $transaction->invoice->first()->generateInvoice();
                $pdf = $pdf->output();
                $sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

                    $fullname = $muser['name'];
                    $first = $muser['first'];
                    $sub =  'KnowCrunch |' . $first . ' – Payment Successful in ' . $muser['event_title'];;
                    $m->from($adminemail, 'Knowcrunch');
                    $m->to('info@knowcrunch.com', $fullname);
                    //$m->to('moulopoulos@lioncode.gr', $fullname);
                    $m->subject($sub);
                    $m->attachData($pdf, "invoice.pdf");
                
                });

                $sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf) {

                    $fullname = $muser['name'];
                    $first = $muser['first'];
                    $sub = 'KnowCrunch |' . $first . ' – Payment Successful in ' . $muser['event_title'];;
                    $m->from($adminemail, 'Knowcrunch');
                    $m->to($muser['email'], $fullname);
                    $m->subject($sub);
                    $m->attachData($pdf, "invoice.pdf");
                
                });

            }

        
            /*if($elearning){
                $pathFile = url('/') . '/pdf/elearning/KnowCrunch - How to access our website & account.pdf';
                $pathFile = str_replace(' ','%20',$pathFile);
                $sent = Mail::send('emails.admin.elearning_after_register', $data, function ($m) use ($adminemail, $muser,$pathFile) {
    
                    $fullname = $muser['name'];
                    $first = $muser['first'];
                    $sub = 'KnowCrunch |' . $first . ', welcome to ' . $muser['event_title'].'!';
                    $m->from($adminemail, 'Knowcrunch');
                    $m->to($muser['email'], $fullname);
                    $m->subject($sub);
                    $m->attach($pathFile);
                        
                });
            }*/
            

            

           
        }


        $transdata = [];
        $transdata['trans'] = $transaction;
        foreach ($emailsCollector as $key => $muser) {

        	$transdata['user'] = $muser;
        	$transdata['trans'] = $transaction;
        	$transdata['extrainfo'] = $extrainfo;
        	$transdata['helperdetails'] = $helperdetails;
           
            if($transaction->payment_method_id == 100) {
                $sentadmin = Mail::send('emails.admin.admin_info_new_registration', $transdata, function ($m) use ($adminemail) {

                    $m->from($adminemail, 'Knowcrunch');
                    $m->to($adminemail, 'Knowcrunch');
           
                    $m->subject('Knowcrunch - New Registration');
                });
            }
            else {
                $sentadmin = Mail::send('emails.admin.admin_info_new_registration', $transdata, function ($m) use ($adminemail) {

                    $m->from($adminemail, 'Knowcrunch');
                    $m->to($adminemail, 'Knowcrunch');
             
                    $m->subject('Knowcrunch - New Registration');
                });
            }


        }

    }
}
