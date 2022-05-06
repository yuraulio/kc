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
use App\Model\CookiesSMS;
use App\Notifications\WelcomeEmail;
use App\Notifications\CourseInvoice;
use App\Notifications\InstructionMail;
use App\Services\FBPixelService;

class InfoController extends Controller
{

    public $fbp;

    public function __construct(FBPixelService $fbp)
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

        $this->fbp = $fbp;
        $this->fbp->sendPageViewEvent();
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
        $data['info']['title'] = __('thank_you_page.title');
        $data['info']['message'] = __('thank_you_page.message');
        $data['info']['transaction'] = $this->transaction;
        $data['info']['statusClass'] = 'success';
        $data['event'] = [];
        $data['event']['title'] = '';
        $data['event']['facebook'] = '';
        $data['event']['twitter'] = '';
        $data['event']['linkedin'] = '';
        $data['event']['slug'] = '';
        if (isset($this->transaction['payment_response'])) {
	        $cart = Cart::content();

	        foreach ($cart as $item) {


                //Update Stock

                $thisevent = Event::where('id', '=', $item->options['event'])->first();

                $data['event']['title'] = $thisevent->title;
                $data['event']['slug'] = $thisevent->slugable->slug;
                $data['event']['facebook'] = url('/') . '/' .$thisevent->slugable->slug .'?utm_source=Facebook&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&quote='.urlencode("Proudly participating in ". $thisevent->title . " by Knowcrunch.");
                $data['event']['twitter'] = urlencode("Proudly participating in ". $thisevent->title . " by Knowcrunch. 💙");
                $data['event']['linkedin'] = urlencode(url('/') . '/' .$thisevent->slugable->slug .'?utm_source=LinkedIn&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&title='."Proudly participating in ". $thisevent->title . " by Knowcrunch. 💙");
                //$data['event']['linkedin'] = urlencode('https://knowcrunch.com/' . '/' .$thisevent->slugable->slug .'?utm_source=LinkedIn&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&title='."Proudly participating in ". $thisevent->title . " by Knowcrunch. 💙");

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
                    $tr_price = number_format($this->transaction['amount'] , 2 , '.', '');
                }else{
                    $tr_price = number_format($this->transaction['amount'] , 0 , '.', '');
                    $tr_price = strval($tr_price);
                    $tr_price .= ".00";
                }
                            
                $categoryScript = $thisevent->delivery->first() && $thisevent->delivery->first()->id == 143 ? 'Video e-learning courses' : 'In-class courses'; // $thisevent->category->first() ? 'Event > ' . $thisevent->category->first()->name : '';
        
                
                $data['tigran'] = ['OrderSuccess_id' => $this->transaction['id'], 'OrderSuccess_total' => $tr_price, 'Price' =>$tr_price,'Product_id' => $thisevent->id, 'Product_SKU' => $thisevent->id,
                        'Product_SKU' => $thisevent->id,'ProductCategory' => $categoryScript, 'ProductName' =>  $thisevent->title, 'Quantity' => $item->qty, 'TicketType'=>$stockHelper->type,'Event_ID' => 'kc_' . time() 
                ];

                /*$data['ecommerce'] = ['ecommerce' => ['transaction_id' => $this->transaction['id'], 'value' => $tr_price, 'currency' => 'EUR', 'coupon' => $transaction->coupon_code], 
                                    'items' => ['item_name' => $thisevent->title, 'item_id' => $thisevent->id, 'price' => $tr_price, 'quantity' => 1, 'item_category' =>  $categoryScript] ];*/

                $data['ecommerce'] = [
                    'actionField' => ['id' => $this->transaction['id'], 'value' => $tr_price, 'currency' => 'EUR', 'coupon' => $transaction->coupon_code], 
                    'products' => ['name' => $thisevent->title, 'id' => $thisevent->id, 'brand'=>'Knowcrunch', 'price' => $tr_price, 
                                    'category' => $categoryScript, 'coupon' => $transaction->coupon_code,'quantity' => Cart::content()->count()]
                                        
                ];


                $data['gt3'] = ['gt3' => ['transactionId' => $this->transaction['id'], 'transactionTotal' => $tr_price], 
                                    'transactionProducts' => ['name' => $thisevent->title, 'sku' => $thisevent->id, 'price' => $tr_price, 'quantity' => 1, '' =>  $categoryScript] ];

                
               
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

        $this->fbp->sendPurchaseEvent($data);

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
        Session::forget('priceOf');
        ///dd($data);
        
        
        if (isset($this->transaction['payment_response'])) {
            Session::put('thankyouData',$data);
            return redirect('/thankyou');
        	//return view('theme.cart.new_cart.thank_you', $data);
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
		KC - Knowcrunch
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
        $billDet = json_decode($transaction['billing_details'],true);
        $billingEmail = isset( $billDet['billemail']) &&  $billDet['billemail'] != '' ?  $billDet['billemail'] : false;
        
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
            $paymentMethodId = 0;

            if ($thisevent) {

                $paymentMethodId = $thisevent->paymentMethod->first() ? $thisevent->paymentMethod->first()->id : 0;
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
            
            $thismember['job_title'] = '';
            $thismember['company'] = '';

            if(isset($pay_seats_data['jobtitles'][$key])){
                $thismember['job_title'] = $pay_seats_data['jobtitles'][$key];
            }

            if(isset($pay_seats_data['companies'][$key])){
                    $thismember['company'] = $pay_seats_data['companies'][$key];
            }
            
            if(isset($pay_seats_data['afms'][$key]))
                $thismember['afm'] = $pay_seats_data['afms'][$key];

            if(isset($pay_seats_data['cities'][$key]))
                $thismember['city'] = $pay_seats_data['cities'][$key];

    		$checkemailuser = User::where('email', '=', $thismember['email'])->first();
            $expiration_date = '';
    		if ($checkemailuser) {
                //if(!$elearning){
                    $transaction->user()->save($checkemailuser);
                //}

                //if($elearning){
                    $invoice = $transaction->invoice()->first();
                    if($invoice){
                        $invoice->user()->save($checkemailuser);

                    }
                //}

                if ($evid && $evid > 0) {

                    $today = date('Y/m/d'); 
                   

                    if($thisevent->expiration){
                        $monthsExp = '+' . $thisevent->expiration .'months';
                        $expiration_date = date('Y-m-d', strtotime($monthsExp, strtotime($today)));
                    }
                    //$thisevent->users()->where('id',$checkemailuser)->detach();
                    $thisevent->users()->wherePivot('user_id',$checkemailuser->id)->detach();
                    if($tickettypedrop == 7){
                        //$tmp = EventStudent::firstOrCreate(['event_id' => $evid, 'student_id' => $checkemailuser->id, 'trans_id' => $transaction->id,'comment'=>'unilever']);
                        $thisevent->users()->save($checkemailuser,['comment'=>'unilever','expiration'=>$expiration_date,'paid'=>true,'payment_method'=>$paymentMethodId]);
                    }else{

                        if($transaction->coupon_code != ''){
                            //$tmp = EventStudent::firstOrCreate(['event_id' => $evid, 'student_id' => $checkemailuser->id, 'trans_id' => $transaction->id,'comment'=>'coupon']);
                            $thisevent->users()->save($checkemailuser,['comment'=>'coupon','expiration'=>$expiration_date,'paid'=>true,'payment_method'=>$paymentMethodId]);
                        }else{
                            $thisevent->users()->save($checkemailuser,['expiration'=>$expiration_date,'paid'=>true,'payment_method'=>$paymentMethodId]);
                        }

                    }
                    $thisevent->tickets()->save($thisticket,['user_id' => $checkemailuser->id]);
                }

                //SHOULD but back used deree id?
                
                $fullname = $checkemailuser->firstname . ' ' . $checkemailuser->lastname;
                $firstname = $checkemailuser->firstname;

              

                //Update user details with the given ones

                $checkemailuser->firstname = $thismember['firstname'];
                $checkemailuser->lastname = $thismember['lastname'];
                $checkemailuser->mobile = $thismember['mobile'];
                $checkemailuser->country_code = $thismember['country_code'];
                $checkemailuser->job_title = isset($thismember['job_title']) ? $thismember['job_title'] : '';
                $checkemailuser->company = isset($thismember['company']) ? $thismember['company'] : '';
                $checkemailuser->city = isset($thismember['city']) ? $thismember['city'] : '';


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
                $creatAccount = false;

                if(!$checkemailuser->statusAccount->completed){
                    
                    $creatAccount = true;


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
                

                $emailsCollector[] = ['email' => $checkemailuser->email, 'name' => $fullname, 'first' => $firstname,'id' => $checkemailuser->id,
                    'mobile' => $checkemailuser->mobile, 'company' => $checkemailuser->company, 'jobTitle' => $checkemailuser->job_title,'createAccount'=>$creatAccount];

	        
    		}
    		else{
               
    			$newmembersdetails[] = $thismember;
    			$fullname = $thismember['firstname'] . ' ' . $thismember['lastname'];
    			$firstname = $thismember['firstname'];
                $emailsCollector[] = ['id' => null, 'email' => $thismember['email'], 'name' => $fullname, 'first' => $firstname, 'company' => $thismember['company'], 'first' => $firstname,
                    'mobile' => $thismember['mobile'], 'jobTitle' => $thismember['job_title'],'createAccount'=>true
                ];
                

    		}
        }

        /*$groupEmailLink = '';
        if ($evid && $evid == 2068) {
            $groupEmailLink = 'https://www.facebook.com/groups/846949352547091';
        }else{
            $groupEmailLink = 'https://www.facebook.com/groups/elearningdigital/';
        }*/

        $groupEmailLink = $thisevent && $thisevent->fb_group ? $thisevent->fb_group : '';

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
            $user->terms = 0;
            $user->consent = '{"ip": "' . $clientip . '", "date": "'.$connow.'" }';


            if(isset($deree_user_data[$value])) {
                $user->partner_id = $deree_user_data[$value];
            }
            else {
                $user->partner_id = '';
            }

    		$user->kc_id = $knowcrunch_id;
            $user->terms = 0;

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
                        $thisevent->users()->save($user,['comment'=>'unilever','expiration'=>$expiration_date,'paid'=>true,'payment_method'=>$paymentMethodId]);
                }else{

                    if($transaction->coupon_code != ''){
                        $thisevent->users()->save($user,['comment'=>'coupon','expiration'=>$expiration_date,'paid'=>true,'payment_method'=>$paymentMethodId]);
                    }else{
                        $thisevent->users()->save($user,['expiration'=>$expiration_date,'paid'=>true,'payment_method'=>$paymentMethodId]);
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
        
        $paymentMethod = PaymentMethod::find($paymentMethodId);

        $this->sendEmails($transaction, $emailsCollector, $extrainfo, $helperdetails, $elearning, $eventslug, $stripe,$billingEmail,$paymentMethod);

    }

    public function sendEmails($transaction, $emailsCollector, $extrainfo, $helperdetails, $elearning, $eventslug,$stripe,$billingEmail,$paymentMethod = null)
    {
       // dd($elearning);
        // 5 email, admin, user, 2 deree, darkpony
    	//$generalInfo = \Config::get('dpoptions.website_details.settings');
        $adminemail = ($paymentMethod && $paymentMethod->payment_email) ? $paymentMethod->payment_email : 'info@knowcrunch.com'; 

        $data = [];
        $data['fbGroup'] = $extrainfo[7];
        $data['duration'] = $extrainfo[3];
        
        $data['eventSlug'] = $transaction->event->first() ? url('/') . '/' . $transaction->event->first()->getSlug() : url('/');

    	foreach ($emailsCollector as $key => $muser) {
            $data['user'] = $muser;
            
    		$data['trans'] = $transaction;
    		$data['extrainfo'] = $extrainfo;
            $data['helperdetails'] = $helperdetails;
            $data['elearning'] = $elearning;
            $data['eventslug'] = $eventslug;
           
            if(($user = User::where('email',$muser['email'])->first())){

                if($user->cart){
                    $user->cart->delete();
                }

                $data['template'] = $transaction->event->first() && $user->waitingList()->where('event_id',$transaction->event->first()->id)->first() 
                                        ? 'waiting_list_welcome' : 'welcome';

                $user->notify(new WelcomeEmail($user,$data));

                /*if($elearning){
                    $user->notify(new InstructionMail($data));
                }*/
            }
    	}

      
        if($stripe){
            
            $data = [];  
            $muser = [];
            $muser['name'] = $transaction->user->first()->firstname;
            $muser['first'] = $transaction->user->first()->firstname;
            $muser['email'] = $transaction->user->first()->email;
            $muser['id'] = $transaction->user->first()->id;
            $muser['event_title'] =$transaction->event->first()->title;

            $data['firstName'] = $transaction->user->first()->firstname;
            $data['eventTitle'] = $transaction->event->first()->title;
            

            if(Session::has('installments') && Session::get('installments') <= 1){
                

                $data['slugInvoice'] = encrypt($muser['id'] . '-' . $transaction->invoice->first()->id);

                $pdf = $transaction->invoice->first()->generateInvoice();

                $fn = date('Y-m-d') . '-Invoice-' . $transaction->invoice->first()->invoice . '.pdf';

                $pdf = $pdf->output();
                $sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser,$pdf,$billingEmail,$fn) {

                    $fullname = $muser['name'];
                    $first = $muser['first'];
                    $sub =  'Knowcrunch -' . $first . ' – download your receipt';
                    $m->from('info@knowcrunch.com', 'Knowcrunch');
                    $m->to($adminemail, $fullname);
                    //$m->to('moulopoulos@lioncode.gr', $fullname);
                    $m->subject($sub);
                
                });

                $data['user'] =  $transaction->user->first();

                $data['user']->notify(new CourseInvoice($data));


            }

    
        }


        $transdata = [];
        $transdata['trans'] = $transaction;
        $transdata['installments'] = Session::has('installments') ? Session::get('installments') : 1;
        $transdata['coupon'] = $transaction->coupon_code != '' ? $transaction->coupon_code : null;
        
        foreach ($emailsCollector as $key => $muser) {

        	$transdata['user'] = $muser;
        	$transdata['trans'] = $transaction;
        	$transdata['extrainfo'] = $extrainfo;
        	$transdata['helperdetails'] = $helperdetails;
           
            
            $sentadmin = Mail::send('emails.admin.admin_info_new_registration', $transdata, function ($m) use ($adminemail) {

                $m->from('info@knowcrunch.com', 'Knowcrunch');
                $m->to('info@knowcrunch.com', 'Knowcrunch');
           
                $m->subject('Knowcrunch - New Registration');
            });
            
           
        }

    }
}
