<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Event;
use App\Model\Ticket;
use Auth;
use \Cart as Cart;
use App\Model\ShoppingCart;
use \Carbon\Carbon;
use Redirect;
use Session;
use App\Model\PaymentMethod;
use Validator;
use Illuminate\Support\Arr;
use App\Model\Transaction;
use App\Model\Invoice;
use \Stripe\Plan;
use \Stripe\Stripe;
use \Stripe\StripeClient;
use Laravel\Cashier\Payment;
use App\Model\CartCache;
use Mail;
use App\Model\Option;
use App\Model\Activation;
use App\Model\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Model\CookiesSMS;
use App\Notifications\WelcomeEmail;
use App\Services\FBPixelService;

class CartController extends Controller
{
    private $fbp;

    public function __construct(FBPixelService $fbp)
    {
        $this->fbp = $fbp;

        $this->middleware('cart')->except('cartIndex','completeRegistration','validation','checkCode');
        $this->middleware('code.event')->only('completeRegistration');
        $this->middleware('registration.check')->except('cartIndex','completeRegistration','validation','checkCode','add');
        //$this->middleware('registration.check');
        $this->middleware('billing.check')->only('billingIndex','billing','checkoutIndex');
        
        $fbp->sendPageViewEvent();

    }

    /*public function checkoutcheck(Request $request)
    {
    	$data = array();
        //$data['lang'] = $_ENV['LANG'];
        //$data['website'] = $_ENV['WEBSITE'];



        $pay_invoice_data = array();
        $pay_bill_data = array();
        $pay_bill_data['billing'] = $request->get('needbilling');
        $paymentCardType = $request->get('cardtype');
        $paymentInstallments = $request->get('installments');
        $studentidfield = $request->get('student');
        $afmfield = $request->get('afm');

        $payment_method_id = $request->get('payment_method_id');

        $validatorArray = [];

        $validatorArray['email.*'] = 'required|email';
        $validatorArray['name.*'] = 'required';
        $validatorArray['surname.*'] = 'required';
        $validatorArray['mobile.*'] = 'required';
        $validatorArray['city.*'] = 'required';
        $validatorArray['address.*'] = 'required';
        $validatorArray['addressnum.*'] = 'required';
        $validatorArray['postcode.*'] = 'required';
        //$validatorArray['mobileCheck.*'] = 'phone:AUTO';

        //    $validatorArray['jobtitle.*'] = 'required';
            //$validatorArray['afm.*'] = 'required';

        $freecheck = Cart::total();
        if ($studentidfield) {
            if($freecheck > 0) {
                $validatorArray['student.*'] = 'required';
            }
            else {
                $validatorArray['student.*'] = 'required|min:10|max:11|exists:users,kc_id';
            }


        }


        if ($pay_bill_data['billing'] == 2) {

    		$validatorArray['companyname'] = 'required';
            $validatorArray['companyprofession'] = 'required';
            $validatorArray['companyafm'] = 'required';
            $validatorArray['companydoy'] = 'required';
            $validatorArray['companyaddress'] = 'required';
            $validatorArray['companyaddressnum'] = 'required';
            $validatorArray['companypostcode'] = 'required';
            $validatorArray['companycity'] = 'required';
        }

        if ($pay_bill_data['billing'] == 1) {

        	//$validatorArray['billemail'] = 'required|email';
            $validatorArray['billname'] = 'required';
            $validatorArray['billsurname'] = 'required';
            //$validatorArray['billmobile'] = 'required';
            $validatorArray['billcity'] = 'required';
            $validatorArray['billaddress'] = 'required';
            $validatorArray['billaddressnum'] = 'required';
            $validatorArray['billpostcode'] = 'required';
            $validatorArray['billafm'] = 'required';

        }

        if ($payment_method_id == 100) {
            //stripe
            //$validatorArray['card_no'] = 'required';
            //$validatorArray['ccExpiryMonth'] = 'required';
            //$validatorArray['ccExpiryYear'] = 'required';
            //$validatorArray['cvvNumber'] = 'required';
        }

        $validator = Validator::make($request->all(), $validatorArray);

        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => '',
            ];

        } else {

            $loggedin_user = Auth::user();

            if ($pay_bill_data['billing'] == 2) {
                $pay_invoice_data['billing'] = 2;
		        $pay_invoice_data['companyname'] = $request->get('companyname');
		        $pay_invoice_data['companyprofession'] = $request->get('companyprofession');
		        $pay_invoice_data['companyafm'] = $request->get('companyafm');
		        $pay_invoice_data['companydoy'] = $request->get('companydoy');
		        $pay_invoice_data['companyaddress'] = $request->get('companyaddress');
		        $pay_invoice_data['companyaddressnum'] = $request->get('companyaddressnum');
		        $pay_invoice_data['companypostcode'] = $request->get('companypostcode');
		        $pay_invoice_data['companycity'] = $request->get('companycity');
                $pay_invoice_data['companyemail'] = $request->get('companyemail');

                if($loggedin_user) {
                    //UPDATE billing in user profile
                    $loggedin_user->invoice_details = json_encode($pay_invoice_data);
                    $loggedin_user->save();
                }

                Session::put('pay_bill_data', $pay_invoice_data);


    		}

    		if ($pay_bill_data['billing'] == 1) {
		        $pay_bill_data['billname'] = $request->get('billname');
		        $pay_bill_data['billsurname'] = $request->get('billsurname');
		        //$pay_bill_data['billemail'] = $request->get('billemail');
		        //$pay_bill_data['billmobile'] = $request->get('billmobile');
		        $pay_bill_data['billaddress'] = $request->get('billaddress');
		        $pay_bill_data['billaddressnum'] = $request->get('billaddressnum');
		        $pay_bill_data['billpostcode'] = $request->get('billpostcode');
		        $pay_bill_data['billcity'] = $request->get('billcity');
                if($request->get('billafm'))
                $pay_bill_data['billafm'] = $request->get('billafm');

                if($loggedin_user) {
                    //UPDATE billing in user profile
                    $loggedin_user->receipt_details = json_encode($pay_bill_data);
                    $loggedin_user->save();
                }

		        Session::put('pay_bill_data', $pay_bill_data);

    		}

    		if(isset($paymentCardType)) :
    		     Session::put('cardtype', $paymentCardType);
    		else :
    			 Session::put('cardtype', 1);
    		endif;
    		if(isset($paymentInstallments)) :
		   	     Session::put('installments', $paymentInstallments);
		    else :
		   		 Session::put('installments', 1);
		   	endif;

            $seats_data = array();
            $seats_data['names'] = $request->get('name');
        	$seats_data['surnames'] = $request->get('surname');
        	$seats_data['emails'] = $request->get('email');
            $seats_data['mobiles'] = $request->get('mobile');
            $seats_data['mobileCheck'] = $request->get('mobileCheck');
            $seats_data['countryCodes'] = $request->get('countryCodes');
        	$seats_data['addresses'] = $request->get('address');
        	$seats_data['addressnums'] = $request->get('addressnum');
        	$seats_data['postcodes'] = $request->get('postcode');
        	$seats_data['cities'] = $request->get('city');
        	$seats_data['jobtitles'] = $request->get('jobtitle');
        	$seats_data['companies'] = $request->get('company');
            $seats_data['students'] = $request->get('student');
            $seats_data['afms'] = $request->get('afm');
            $seats_data['studentId'] = $request->get('studentId');
        	Session::put('pay_seats_data', $seats_data);

        	return [
                    'status' => 1,
                    'message' => 'Done go checkout',
                ];

    	}

    }*/

    private function initCartDetails($data){
        
        $event_id = 0;
        $data['price'] = 'free';
        $data['type'] = -1;
        $totalitems = 0;
        $ticketType = '';
        $data['curStock'] = 1;
        $tr_price = 0;
        $data['elearning'] = false;

        $c = Cart::content()->count();
        if ($c > 0) {
            $cart_contents = Cart::content();
            foreach ($cart_contents as $item){

                $totalitems += $item->qty;
                $event_id = $item->options->event;
                $event_type = $item->options->type;
                
                $data['itemid'] = $item->rowId;
            
                break;
            }

            $ev = Event::find($event_id);
            if($ev) {

                $data['elearning'] = $ev->delivery->first() && $ev->delivery->first()->id == 143 ? true : false;
                $data['eventId'] = $event_id;
               
                if($ev->view_tpl == 'event_free_coupon'){
                    $data['couponEvent'] = true;
                }
                //dd($ev->ticket);
                $data['eventtickets'] = $ev->ticket;
                $data['city_event'] = $ev->city->first() ? '' : '';
                $data['duration'] = '';

                $categoryScript = 'Event > ' . $ev->category->first()->name;
                //dd($categoryScript);

                $data['stripe_key'] = '';
                $data['paywithstripe'] = 0;

                if($ev->paymentMethod->first()){
                    if($ev->paymentMethod->first()->method_slug == 'stripe'){
                        $data['paywithstripe'] = 1;
                        session()->put('payment_method',$ev->paymentMethod->first()->id);
                        $data['stripe_key'] = env('PAYMENT_PRODUCTION') ? $ev->paymentMethod->first()->processor_options['key'] :
                                                                                $ev->paymentMethod->first()->test_processor_options['key'];
                    }

                }
                $data['pay_methods'] = $ev->paymentMethod->first();


                $data['duration'] = $ev->summary1->where('section','date')->first() ? $ev->summary1->where('section','date')->first()->title:'';
                $data['hours'] = $ev->summary1->where('section','duration')->first() ? $ev->summary1->where('section','duration')->first()->title:'';
                $data['city_event'] = $ev->city->first() ? $ev->city->first()->name : '';
                $data['coupons'] = $ev->coupons->where('price','>',0)->toArray();
                
            }

            $data['eventId'] = $event_id;
            $data['categoryScript'] = $categoryScript;

            $cart_contents = Cart::content();
            foreach ($cart_contents as $item){
               
                if($item->options->has('type')){
                    $data['type'] = $item->options->type;
                }

                foreach ($data['eventtickets'] as $tkey => $tvalue) {
                    if ($tvalue->pivot->event_id == $item->options->event && $tvalue->ticket_id == $item->id) {
                        $ticketType = $tvalue->type;
                        $data['curStock'] = $tvalue->pivot->quantity;
                        $oldPrice = $tvalue->pivot->price * $totalitems;
                        $data['oldPrice'] = number_format($tvalue->pivot->price * $totalitems, 2, ".", ",");//$tvalue->pivot->price * $totalitems;
                        $data['showPrice'] = number_format($tvalue->pivot->price * $totalitems, 2, ".", ",");//$tvalue->pivot->price * $totalitems;
                        $data['price'] = $tvalue->pivot->price * $totalitems;
                        $tr_price = $tvalue->pivot->price * $totalitems;
                    }
                }
                break;
            }
        }
        
        if(Session::get('coupon_code')){
           
            $data['price'] = Session::get('coupon_price') * $totalitems;
            $data['savedPrice'] = $oldPrice - Session::get('coupon_price') * $totalitems;
            $data['showPrice'] = number_format($data['price'], 2, ".", ",");//$tvalue->pivot->price * $totalitems;
            $tr_price = Session::get('coupon_price') * $totalitems;
            
        }

        if(Session::get('priceOf')){
            $data['priceOf'] = Session::get('priceOf');
        }

        if($data['type'] == 'free_code' ){
            $data['price'] = 'Upon Coupon';
            $data['show_coupon'] = false;
            $ticketType = 'Upon Coupon';
            $tr_price = 0;

        }else if($data['type'] == 'free' ){
            $data['price'] = 'Free';
            $data['show_coupon'] = false;
            $ticketType = 'Free';
            $tr_price = 0;
        }

 
        if(is_numeric($data['price']) && ($data['price'] - floor($data['price'])>0)){
            $data['showPrice'] = number_format($data['price'] , 2 , '.', ',');
            $data['oldPrice'] = number_format($data['price'] , 2 , '.', ',');
        }else if(is_numeric($data['price'])){
            $data['showPrice'] = number_format($data['price'] , 0 , '.', '');
            $data['oldPrice'] = number_format($data['price'] , 0 , '.', '');

        }


        $data['totalitems'] = $totalitems;

        if($tr_price - floor($tr_price)>0){
            $tr_price = number_format($tr_price , 2 , '.', ',');
        }else{
            $tr_price = number_format($tr_price , 0 , '.', '');
            $tr_price = strval($tr_price);
            $tr_price .= ".00";
        }


        $data['tigran'] = ['Price' => $tr_price,'Product_id' => $data['eventId'], 'Product_SKU' => $data['eventId'],
                    'ProductCategory' => $data['categoryScript'], 'ProductName' =>  $ev->title, 'Quantity' => $totalitems,'TicketType'=>$ticketType,'Event_ID' => 'kc_' . time() 
        ];

       
        if(Auth::user()){
            $data['tigran']['User_id'] = Auth::user()->id;
        }else{
            $data['tigran']['Visitor_id'] = session()->getId();
        }
        
        return $data;

    }

    public function mobileCheck(Request $request){
        
        //dd($request->all());
        
        $data = array();
        
        $validatorArray = [];

        //$validatorArray['email.*'] = 'required|email';
        //$validatorArray['firstname.*'] = 'required';
        //$validatorArray['lastname.*'] = 'required';
       // $validatorArray['mobile.*'] = 'required';
       // //$validatorArray['city.*'] = 'required';
        //$validatorArray['address.*'] = 'required';
       // $validatorArray['addressnum.*'] = 'required';
        //$validatorArray['postcode.*'] = 'required';
        $validatorArray['mobileCheck.*'] = 'phone:AUTO';

        $validator = Validator::make($request->all(), $validatorArray);
        //dd($validator);
        if ($validator->fails()) {
            return [
                'status' => 0,
                'errors' => $validator->errors(),
                'message' => '',
            ];

        } else {
           
        	return [
                'status' => 1,
                'message' => 'Done go checkout',
    
            ];



        }

    }

    /**
     * Display a listing of products on the cart.
     *
     * @return \Illuminate\View\View
    */
    public function registrationIndex()
    {

        $data = array();
        //$data['lang'] = $_ENV['LANG'];
        //$data['website'] = $_ENV['WEBSITE'];

        $data['pay_methods'] = array();

        $data['eventtickets'] = [];
        $categoryScript = '';
        $data['couponEvent'] = false;


        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');

        }
        else {
            $data['pay_seats_data'] = [];
        }

       /* if (Session::has('pay_invoice_data')) {
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


       $data = $this->initCartDetails($data);

        //check for logged in user
        $loggedin_user = Auth::user();

        for($i = 1;  $i <= $data['totalitems']; $i++ ){

            //dd($data['pay_seats_data']);
            //$data['cur_user'][$i] =  isset($data['pay_seats_data']['cur_user'][$i-1]) ? $data['pay_seats_data']['cur_user'][$i-1] : '';
            $data['firstname'][$i-1] = isset($data['pay_seats_data']['names'][$i-1]) ? $data['pay_seats_data']['names'][$i-1] : '';
            $data['lastname'][$i-1] = isset($data['pay_seats_data']['surnames'][$i-1]) ? $data['pay_seats_data']['surnames'][$i-1] : '';
            $data['email'][$i-1] = isset($data['pay_seats_data']['emails'][$i-1]) ? $data['pay_seats_data']['emails'][$i-1] : '';
            $data['country_code'][$i-1] = isset($data['pay_seats_data']['countryCodes'][$i-1]) ? $data['pay_seats_data']['countryCodes'][$i-1] : '';
            $data['city'][$i-1] = isset($data['pay_seats_data']['cities'][$i-1]) ? $data['pay_seats_data']['cities'][$i-1] : '';
            $data['mobile'][$i-1] = isset($data['pay_seats_data']['mobiles'][$i-1]) ? $data['pay_seats_data']['mobiles'][$i-1] : '';
            $data['job_title'][$i-1] = isset($data['pay_seats_data']['jobtitles'][$i-1]) ?  $data['pay_seats_data']['jobtitles'][$i-1]: '';
            $data['company'][$i-1] = isset($data['pay_seats_data']['companies'][$i-1]) ?  $data['pay_seats_data']['companies'][$i-1]: '';
            $data['student_type_id'][$i-1] = isset($data['pay_seats_data']['student_type_id'][$i-1]) ?  $data['pay_seats_data']['student_type_id'][$i-1]: '';
            
        }
        
        $data['cur_user'][0] = $loggedin_user;
        $data['kc_id'] = '';
        if($loggedin_user && $data['firstname'][0] =="") {
            
            $data['firstname'][0] = $loggedin_user->firstname;
            $data['lastname'][0] = $loggedin_user->lastname;
            $data['email'][0] = $loggedin_user->email;
            $data['country_code'][0] = $loggedin_user->country_code;
            $data['city'][0] = $loggedin_user->city;
            $data['mobile'][0] = $loggedin_user->mobile;
            $data['job_title'][0] = $loggedin_user->job_title;
            $data['company'][0] = $loggedin_user->company;
            $data['student_type_id'][0] = $loggedin_user->student_type_id;
            

            if(isset($data['pay_bill_data']) && empty($data['pay_bill_data'])) {
                $inv = []; $rec = [];
                if($loggedin_user->invoice_details != '') {
                    $inv = json_decode($loggedin_user->invoice_details, true);
                    if(isset($inv['billing']))
                        unset($inv['billing']);
                }

                if($loggedin_user->receipt_details != '') {
                    $rec = json_decode($loggedin_user->receipt_details, true);
                    if(isset($rec['billing']))
                        unset($rec['billing']);
                }

                $data['pay_bill_data'] = array_merge($inv, $rec);
            }
            if($data['paywithstripe'] == 1){
                $data['default_card'] = false;//$loggedin_user->defaultPaymentMethod() ? $loggedin_user->defaultPaymentMethod()->card : false;
            }


            $ukcid = $loggedin_user->kc_id;
            $data['kc_id'] = $ukcid;
        }

        //$this->fbp->sendLeaderEvent($data['tigran']);
        $this->fbp->sendAddToCart($data);

        if($data['type'] == 1 || $data['type'] == 2 || $data['type'] == 5){
            return view('theme.cart.new_cart.participant_special', $data);
        }

        if($data['type'] == 3){
            
            return view('theme.cart.new_cart.participant_alumni', $data);
        }
        
        if($data['type'] == 'free_code'){
            return view('theme.cart.new_cart.participant_code_event', $data);
        }

        if($data['type'] == 'free'){
            return view('theme.cart.new_cart.participant_free_event', $data);
        }
      
        return view('theme.cart.new_cart.participant', $data);
            
    }

    public function registration(Request $request)
    {

        $userCheck = Auth::user();
        $data = [];
        $data = $this->initCartDetails($data);
        //dd($data['type']!=3);
    
        if((!$userCheck && !($user = User::where('email',$request->email[0])->first())) &&  $data['type'] != 3){
            $input = [];
            $formData = $request->all();
            unset($formData['_token']);
            unset($formData['terms_condition']);
            unset($formData['update']);
            unset($formData['type']);
            
            foreach($formData as $key => $value){
                $input[$key] = $value[0];
            }

            $input['password'] = Hash::make(date('Y-m-dTH:i:s'));
            
            $user = User::create($input);

            $connow = Carbon::now();
            $clientip = '';
            $clientip = \Request::ip();
            $user->terms = 1;
            $user->consent = '{"ip": "' . $clientip . '", "date": "'.$connow.'" }';
            $user->save();
            $code = Activation::create([
                'user_id' => $user->id,
                'code' => Str::random(40),
                'completed' => false,
            ]);
            $user->role()->attach(7);
            Session::put('user_id', $user->id);

            $existingcheck = ShoppingCart::where('identifier', $user->id)->first();
            //Cart::restore($user->id
            if($existingcheck) {
                //$user edww
                if($user->cart){
                    $user->cart->delete();
                }
                $existingcheck->delete($user->id);
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            }
            else {
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            }
 
        }

        $seats_data = array();
        
        if($data['type'] == 3 && $userCheck  && $userCheck ->kc_id ){

            $seats_data['names'][] = $userCheck ->firstname;
            $seats_data['surnames'][] = $userCheck ->lastname;
            $seats_data['emails'][] =$userCheck ->email;
            $seats_data['mobiles'][] = $userCheck ->mobile;
            $seats_data['mobileCheck'][] = $userCheck ->mobileCheck;
            $seats_data['countryCodes'][] = $userCheck ->country_code;
            $seats_data['cities'][] = $userCheck ->city;
            $seats_data['jobtitles'][] = $userCheck ->jobtitle;
            $seats_data['companies'][] = $userCheck ->company;
            $seats_data['student_type_id'][] = $userCheck ->student_type_id;
            Session::put('user_id', $userCheck->id);

        }else if($data['type'] != 3) {
            $seats_data['names'] = $request->get('firstname');
            $seats_data['surnames'] = $request->get('lastname');
            $seats_data['emails'] = $request->get('email');
            $seats_data['mobiles'] = $request->get('mobile');
            $seats_data['mobileCheck'] = $request->get('mobileCheck');
            $seats_data['countryCodes'] = $request->get('country_code');
            $seats_data['cities'] = $request->get('city');
            $seats_data['jobtitles'] = $request->get('jobtitle');
            $seats_data['companies'] = $request->get('company');
            $seats_data['student_type_id'] = $request->get('student_type_id');
            Session::put('user_id', $userCheck ? $userCheck->id : $user->id);

        }else{
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
        }
         
        Session::put('pay_seats_data', $seats_data);
        
        
        return redirect('/billing');
            

    }

    public function billingIndex()
    {

        $data = array();
        $data['pay_methods'] = array();

        $data['eventtickets'] = [];
        $categoryScript = '';
        $data['couponEvent'] = false;


        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');

        }
        else {
            $data['pay_seats_data'] = [];
        }

       /* if (Session::has('pay_invoice_data')) {
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


        $data = $this->initCartDetails($data);

        //check for logged in user
        $loggedin_user = Auth::user();
       
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

        //dd()

        $data['billname'] = isset($data['pay_bill_data']['billname']) ? $data['pay_bill_data']['billname'] : '';
        $data['billsurname'] = isset($data['pay_bill_data']['billsurname']) ? $data['pay_bill_data']['billsurname'] : '';
        $data['billaddress'] = isset($data['pay_bill_data']['billaddress']) ? $data['pay_bill_data']['billaddress'] : '';
        $data['billaddressnum'] = isset($data['pay_bill_data']['billaddressnum']) ? $data['pay_bill_data']['billaddressnum'] : '';
        $data['billpostcode'] = isset($data['pay_bill_data']['billpostcode']) ? $data['pay_bill_data']['billpostcode'] : '';
        $data['billcity'] = isset($data['pay_bill_data']['billcity']) ? $data['pay_bill_data']['billcity'] : '';
        $data['billafm'] = isset($data['pay_bill_data']['billafm']) ?  $data['pay_bill_data']['billafm'] : '';
        $data['billstate'] = isset($data['pay_bill_data']['billstate']) ?  $data['pay_bill_data']['billstate'] : '';
        $data['billemail'] = isset($data['pay_bill_data']['billemail']) ?  $data['pay_bill_data']['billemail'] : '';
        $data['billcountry'] = isset($data['pay_bill_data']['billcountry']) ?  $data['pay_bill_data']['billcountry'] : '';
            
        


        if($loggedin_user) {

            
            
            if(isset($data['pay_bill_data']) && empty($data['pay_bill_data'])) {
               
                $inv = []; $rec = [];
                if($loggedin_user->invoice_details != '') {
                    $inv = json_decode($loggedin_user->invoice_details, true);
                    if(isset($inv['billing']))
                        unset($inv['billing']);
                }

                if($loggedin_user->receipt_details != '') {
                    $rec = json_decode($loggedin_user->receipt_details, true);
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
            $data['billcountry'] = isset($data['pay_bill_data']['billcountry']) ? $data['pay_bill_data']['billcountry'] : '';
            $data['billstate'] = isset($data['pay_bill_data']['billstate']) ? $data['pay_bill_data']['billstate'] : '';
            $data['billemail'] = isset($data['pay_bill_data']['billemail']) ? $data['pay_bill_data']['billemail'] : '';

            $ukcid = $loggedin_user->kc_id;
        }

        $this->fbp->sendCompleteRegistrationEvent($data);

        return view('theme.cart.new_cart.billing', $data);
            


        //return view('theme.cart.cart', $data);
    }

    public function billing(Request $request){

        $pay_bill_data = [];
       
        $pay_bill_data['billing'] = 1;
        $pay_bill_data['billname'] = $request->get('billname');
		$pay_bill_data['billemail'] = $request->get('billemail');
		$pay_bill_data['billaddress'] = $request->get('billaddress');
		$pay_bill_data['billaddressnum'] = $request->get('billaddressnum');
		$pay_bill_data['billpostcode'] = $request->get('billpostcode');
		$pay_bill_data['billcity'] = $request->get('billcity');
        $pay_bill_data['billcountry'] = $request->get('billcountry');
        $pay_bill_data['billstate'] = $request->get('billstate');
        $pay_bill_data['billafm'] = $request->get('billafm');
       
        if(!$user = Auth::user()){
            
            $user = User::find(Session::get('user_id'));
        }

        if($user) {
            //UPDATE billing in user profile
            
            $user->receipt_details = json_encode($pay_bill_data);
            $user->afm = $pay_bill_data['billafm'];
            $user->save();
        }

		Session::put('pay_bill_data', $pay_bill_data);
        return redirect('/checkout');
    }

    public function checkoutIndex()
    {

        $data = array();
        $data['pay_methods'] = array();

        $data['eventtickets'] = [];
        $categoryScript = '';
        $data['couponEvent'] = false;


        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');

        }
        else {
            $data['pay_seats_data'] = [];
        }

       /* if (Session::has('pay_invoice_data')) {
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

        $data = $this->initCartDetails($data);
        //$this->fbp->sendAddPaymentInfoEvent($data);
        $this->fbp->sendAddBillingInfoEvent($data);
        return view('theme.cart.new_cart.checkout', $data);
            
        //return view('theme.cart.cart', $data);
    }

    /**
    * Adds a new product to the shopping cart.
    *
    * @param  string  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function add($id, $ticket, $type, Request $request)
    {
      
        if((!Auth::user() || (Auth::user() && !Auth::user()->kc_id)) && $type == 3){
            return back();
        }
        
        // Determine if this is an ajax request
        //dd($ticket);
        $isAjax = $request->ajax();
        // Get the product from the database
        $product = Event::find($id);
        
        // Check if the product exists on the database
        if (! $product ) {
            if ($isAjax) {
                return response('Product was not found!', 404);
            }
            return redirect()->to('/');
        }

        if($ticket == 'free'){
            $this->addFreeToCart($product, $ticket, $ticket);
        }else{
            $ticketob = $product->ticket->groupBy('ticket_id')[$ticket]->first();
            $item = $this->addToCart($product, $ticketob, $type);
        }

        if ($isAjax) {
            return response($item->toArray());
        }

        if($ticket == 'free'){
            return Redirect::to('/registration')->with('success',
                "Free ticket was successfully added to your bag."
            );
        }else{
            return Redirect::to('/registration')->with('success',
                "{$ticketob->title} was successfully added to your bag."
            );
        }

    }

    /* Add product to cart.
    *
    * @param  \App\Models\Product  $product
    * @return \Cartalyst\Cart\Collections\ItemCollection
    */

    protected function addToCart($product, $ticket, $type)
    {
    
       // Let only one event in the cart added on 5/6/2018

       Cart::instance('default')->destroy();

       $price = (float) $ticket->pivot->price;
       $duplicates = Cart::search(function ($cartItem, $rowId) use ($ticket) {
           return $cartItem->id === $ticket->id;
       });

       if (!$duplicates->isEmpty()) {
           return redirect('cart')->withSuccessMessage('Item is already in your cart!');
       }

       if ($type == 5) {
           //group ticket
           $quantity = 2;
       }
       else {
           $quantity = 1;
       }


       $eventid = $product->id;
       $item = Cart::add($ticket->ticket_id, $product->title, $quantity, $price, ['type' => $type, 'event' => $eventid])->associate(Ticket::class);

       //SAVE CART IF USER LOGGED
       if(Auth::check()) {
           $user = Auth::user();
           $existingcheck = ShoppingCart::where('identifier', $user->id)->first();
           //Cart::restore($user->id
           if($existingcheck) {
               //$user edww
               if($user->cart){
                   $user->cart->delete();
               }
               $existingcheck->delete($user->id);
               Cart::store($user->id);
               $timecheck = ShoppingCart::where('identifier', $user->id)->first();
               $timecheck->created_at = Carbon::now();
               $timecheck->updated_at = Carbon::now();
               $timecheck->save();
           }
           else {
               Cart::store($user->id);
               $timecheck = ShoppingCart::where('identifier', $user->id)->first();
               $timecheck->created_at = Carbon::now();
               $timecheck->updated_at = Carbon::now();
               $timecheck->save();
           }

           if($user->cart){
               $user->cart->delete();
           }

           //$cartCache = new CartCache;

           //$cartCache->ticket_id = $ticket->id;
           //$cartCache->product_title = $product->title;
           //$cartCache->quantity = $quantity;
           //$cartCache->price = $price;
           //$cartCache->type = $type;
           //$cartCache->event = $eventid;
           //$cartCache->user_id = $user->id;
           //$cartCache->slug =  base64_encode($ticket->id. $user->id . $eventid);

           //$cartCache->save();

       }

       return $item;

    }

    protected function addFreeToCart(Event $product, $ticket, $type)
    {
        // Let only one event in the cart added on 5/6/2018

        Cart::instance('default')->destroy();


        $price = (float)0;
        $quantity = 1;
        $eventid = $product->id;

        $item = Cart::add('free', $product->title, $quantity, $price, ['type' => 'free', 'event' => $eventid])->associate(Ticket::class);


        return $item;

        //return redirect('cart')->withSuccessMessage('Item was added to your cart!');


    }

    public function userPaySbt(Request $request){

        $input = $request->all();
        $payment_method_id = intval($input["payment_method_id"]);
        $data = [];
            
        if(isset($input['installments'])){
            Session::put('installments', $input['installments']);
        }else{
            Session::put('installments', 1);
        }

        if(Session::get('coupon_code')){
            $input['coupon'] = Session::get('coupon_code');
        }
        
        $data = $this->initCartDetails($data);
        $this->fbp->sendAddPaymentInfoEvent($data);

        if($payment_method_id != 1) {

            $redurl = $this->postPaymentWithStripe($input);
            return redirect($redurl);
        }else{
            return $this->alphaBankPayment($input,$request);
        }

    }

    public function postPaymentWithStripe($input)
    {
       
        Session::forget('dperror');
        Session::forget('error');

        //$current_user = Auth::user();

        $dpuser = Auth::user() ? Auth::user() : User::find(Session::get('user_id'));
        $cart = Cart::content();
        $ev_title = '';
        $ev_date_help = '';
        $eventId = 0;
        $qty = 1;
        $ticket_id = 0;
        foreach ($cart as $item) {
            $qty = $item->qty;
            $ev = Event::where('id', $item->options['event'])->first();
            $eventId = $item->options['event'];
            $ev_date_help = $ev->summary1->where('section','date')->first() ? $ev->summary1->where('section','date')->first()->title : 'date';
            $ev_title = $ev->title;
            $ticket_id = $item->id;
            break;
            //$item->id  <-ticket id
        }
        //dd($cart['CartItem']->CartItemOptions);
        //dd($ev_title . $ev_date_help);
        $data = [];
        if (Session::has('pay_seats_data')) {
            $pay_seats_data = Session::get('pay_seats_data');
        }
        else {
            $pay_seats_data  = [];
        }

        if (Session::has('pay_bill_data')) {
            $pay_bill_data = Session::get('pay_bill_data');
            $bd = json_encode($pay_bill_data);
        }
        else {
            $bd = '';
            $pay_bill_data = [];
        }

        if (Session::has('installments')) {
            $installments = Session::get('installments');
        }
        else {
            $installments = 0;
        }

        $input = Arr::except($input,array('_token'));

        try {

            $amount = Cart::total();
            $coupon = [];
            $eventC = Event::find($eventId);
            if ($eventC && isset($input['coupon'])){
                $coupon = $eventC->coupons()->where('status', true)->get();
            }

            if(isset($input['coupon']) && count($coupon) > 0){
                foreach($coupon as $key => $c){
                    if(!($c->code_coupon === $input['coupon'])){
                        unset($coupon[$key]);
                    }
                }
            }

            $couponCode = '';
            if(count($coupon) > 0){
                $coupon = $coupon->first();
                if (isset($input['coupon'])){
                    if($input['coupon'] && trim($input['coupon']) != '' && trim($coupon->code_coupon)!= '' && $coupon->status && trim($input['coupon']) == trim($coupon->code_coupon)){
                        
                        if($coupon->percentage){
                            $couponPrice = ($amount/Cart::count()) * $coupon->price / 100;
                            $couponPrice = ($amount/Cart::count()) - $couponPrice;
                            $amount =  $couponPrice * $qty;

                        }else{
                            $amount = $coupon->price * $qty;
                        }
                        
                        $couponCode = $input['coupon'];
                    }
                }
            }

            $namount = (float)$amount;

            $temp = [];
            if(isset($pay_bill_data)) {
                $temp = $pay_bill_data;
                if($temp['billing'] == 1) {

                    $address = [];
                    $address['country'] = 'GR';

                    $temp['billing'] = 'Receipt requested';
                    
                    $st_name =  $temp['billname'];
                    $st_tax_id = 'EL'.$temp['billafm'];

                    if(isset($temp['billaddress'])){
                        $st_line1 = $temp['billaddress'] ;
                        
                        if(isset($temp['billaddressnum'])){
                            $st_line1 .= ' ' . $temp['billaddressnum'];
                        }

                        $address['line1'] = $st_line1;
                    }

                    if(isset($temp['billcity'])){
                        $st_city = $temp['billcity'];
                        $address['city'] = $st_city;
                    }
                    
                    if(isset($temp['billpostcode'])){
                        $st_postal_code = $temp['billpostcode'];
                        $address['postal_code'] = $st_postal_code;
                    }
                    
                    
               //     $st_phone = $temp['billmobile'];

                }
                else {
                    $temp['billing'] = 'Invoice requested';
                    //generate array for stripe billing
                 //   $st_desc = $temp['companyname'] . ' ' . $temp['companyprofession'];
                    $st_name = $temp['companyname'] . ' ' . $temp['companyprofession'];
                    $st_tax_id = $temp['companyafm'] . ' ' . $temp['companydoy'];
                    $st_line1 = $temp['companyaddress'] . ' ' . $temp['companyaddressnum'];
                    $st_postal_code = $temp['companypostcode'];
                    $st_city = $temp['companycity'];
                    $st_email = $temp['companyemail'];
                    $st_phone = '';

                }
            }

            if(env('PAYMENT_PRODUCTION')){
                Stripe::setApiKey($eventC->paymentMethod->first()->processor_options['secret_key']);
            }else{
                Stripe::setApiKey($eventC->paymentMethod->first()->test_processor_options['secret_key']);
            }
            session()->put('payment_method',$eventC->paymentMethod->first()->id);
            
            $dpuser->asStripeCustomer();

            if(!$dpuser->stripe_id){
                
                $options=['name' => $dpuser['firstname'] . ' ' . $dpuser['lastname'], 'email' => $dpuser['email']];
                $dpuser->createAsStripeCustomer($options);
               
                $stripe_ids = json_decode($dpuser->stripe_ids,true) ? json_decode($dpuser->stripe_ids,true) : [];
                $stripe_ids[] =$dpuser->stripe_id;

                $dpuser->stripe_ids = json_encode($stripe_ids);
                $dpuser->save();
            }

             if($installments > 1) {

                $instamount =  round($namount / $installments, 2);

                if($instamount - floor($instamount)>0){
                    $planAmount = str_replace('.','',$instamount);
                }else{
                    $planAmount  = $instamount . '00';
                }
                    //$dpuser->subscription()->syncWithStripe();
                   // dd("Entity ready to be billed!");
                    // Check if the entity has any active subscription


                        //./ngrok authtoken 69hUuQ1DgonuoGjunLYJv_3PVuHFueuq5Kiuz7S1t21
                        // Create the plan to subscribe
                        $desc = $installments . ' installments';
                        $planid = 'plan_'.$dpuser->id.'_E_'.$ev->id.'_T_'.$ticket_id.'_x'.$installments;
                        $name = $ev_title . ' ' . $ev_date_help . ' | ' . $desc;
                        //dd(str_replace('.','',$instamount) . '00');

                        $plan = Plan::create([
                            'id'                   => $planid,
                            "product" => array(
                                'name'                 => $name,
                              ),

                            'amount'               => $planAmount,
                            'currency'             => 'eur',
                            'interval'             => 'month',
                            //'statement_descriptor' => $desc,

                        ]);


                        /*$sub = $dpuser
                            ->subscription()
                            ->onPlan($planid)
                            ->create(['metadata' => ['installments_paid' => 0, 'installments' => $installments]])
                        ;*/

                        $charge = $dpuser->newSubscription($name, $plan->id)->create($input['payment_method'],
                        ['email' => $dpuser->email],
                                    ['metadata' => ['installments_paid' => 0, 'installments' => $installments]]);

                        $charge->metadata = json_encode(['installments_paid' => 0, 'installments' => $installments]);
                        $charge->price = $instamount;
                        $charge->save();

                        //$namount = $instamount;
             }

             
            if($dpuser && $installments > 1) {

                $charge['status'] = 'succeeded';
                $charge['type'] = $installments . ' Installments';
            }
            else {

                if($namount - floor($namount)>0){
                    $stripeAmount = str_replace('.','',$namount);
                }else{
                    $stripeAmount  = $namount . '00';
                }
            
                $dpuser->updateStripeCustomer([
                    'name' => $st_name,
                    'email' => $dpuser->email,
                    'metadata' => $temp,
                    //'description' => $st_desc,

                    //'tax_info' => ['tax_id' => $st_tax_id, 'type' => 'vat'],
                    'shipping' => ['name' => $st_name, 'address' => $address],
                    'address' => $address,
                    
                ]);
               
                $temp['customer'] = $dpuser->email;
                $nevent = $ev_title . ' ' . $ev_date_help;

                
                $charge = $dpuser->charge(
                    $stripeAmount,
                    $input['payment_method'],
                    [
                        'currency' => 'eur',
                        'amount' => $stripeAmount,
                        'description' => $nevent,
                        //'shipping' => ['name' => $st_name, 'address' => ['line1' => $st_line1,'postal_code' => 59100,'city' => 'gsdf','country' => 'GR']],
                        'customer' => $dpuser->stripe_id,
                        //'metadata' => $temp,

                    ]
                );

            }


            if( (is_array($charge)  &&  $charge['status'] == 'succeeded' ) || $charge->status == 'succeeded') {
                 /**
                 * Write Here Your Database insert logic.
                 */

                 $status_history = [];
                //$payment_cardtype = intval($input["cardtype"]);
                 $status_history[] = [
                    'datetime' => Carbon::now()->toDateTimeString(),
                    'status' => 1,
                    'user' => [
                        'id' => $dpuser->id,
                        'email' => $dpuser->email
                    ],
                    'pay_seats_data' => $pay_seats_data,
                    'pay_bill_data' => $pay_bill_data,
                    'deree_user_data' => [$dpuser->email => ''],
                    //'cardtype' => $payment_cardtype,
                    'installments' => $installments,
                    'cart_data' => $cart

                ];
                $transaction_arr = [

                    "payment_method_id" => 100,//$input['payment_method_id'],
                    "account_id" => 17,
                    "payment_status" => 2,
                    "billing_details" => $bd,
                    "status_history" => json_encode($status_history),
                    "placement_date" => Carbon::now()->toDateTimeString(),
                    "ip_address" => \Request::ip(),
                    "status" => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
                    "is_bonus" => 0,
                    "order_vat" => 0,
                    "payment_response" => json_encode($charge),
                    "surcharge_amount" => 0,
                    "discount_amount" => 0,
                    "coupon_code" => $couponCode,
                    "amount" => $namount,
                    "total_amount" => $namount,
                    'trial' => false,
                ];

                $transaction = Transaction::create($transaction_arr);

                if($transaction) {

                    //$transaction->user()->save($dpuser);
                    $transaction->event()->save($ev);

                    if($installments <= 1){
                        if(!Invoice::latest()->doesntHave('subscription')->first()){
                        //if(!Invoice::has('event')->latest()->first()){
                            $invoiceNumber = sprintf('%04u', 1);
                        }else{
                            //$invoiceNumber = Invoice::has('event')->latest()->first()->invoice;
                            $invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
                            $invoiceNumber = (int) $invoiceNumber + 1;
                            $invoiceNumber = sprintf('%04u', $invoiceNumber);
                        }


                        $elearningInvoice = new Invoice;
                        $elearningInvoice->name = json_decode($transaction->billing_details,true)['billname'];
                        $elearningInvoice->amount = round($namount / $installments, 2);
                        $elearningInvoice->invoice = $invoiceNumber;
                        $elearningInvoice->date = date('Y-m-d');//Carbon::today()->toDateString();
                        $elearningInvoice->instalments_remaining = $installments;
                        $elearningInvoice->instalments = $installments;

                        $elearningInvoice->save();


                        //$elearningInvoice->user()->save($dpuser);
                        $elearningInvoice->event()->save($ev);
                        $elearningInvoice->transaction()->save($transaction);
                    }else{
                        //$transaction->subscription()->save($dpuser->subscriptions->where('id',$charge['id'])->first());
                    }

                    \Session::put('transaction_id', $transaction->id);
                }

                return '/thankyou';
                //return '/info/order_success';

            } else {
                //dd('edwww1');
                 \Session::put('dperror','Cannot complete the payment!!');
                //return redirect('/info/order_error');
                  return '/checkout';
            }
        }
        catch (Exception $e) {
            //dd('edwww2');
             \Session::put('dperror',$e->getMessage());
              return '/checkout';
            // return redirect('/info/order_error');
        }
        catch(\Stripe\Exception\CardErrorException $e) {
            //dd('edwww3');
            \Session::put('dperror',$e->getMessage());
              return '/checkout';
             //return redirect('/info/order_error');
        }
        catch(\Stripe\Exception\InvalidRequestException $e) {
            //dd($e);
            \Session::put('dperror',$e->getMessage());
            //return redirect('/info/order_error');
            return '/checkout';
        }
        catch(\Stripe\Exception\MissingParameterException $e) {
            //dd($e);
            \Session::put('dperror',$e->getMessage());
            //return redirect('/info/order_error');
            return '/checkout';
        }
        catch(\Stripe\Api\Exception\ServerErrorException $e) {
            //dd($e);
            \Session::put('dperror',$e->getMessage());
            //return redirect('/info/order_error');
            return '/checkout';
        }catch(\Stripe\Exception\CardException $e) {
            //dd($e);
            \Session::put('dperror',$e->getMessage());
            //return redirect('/info/order_error');
            return '/checkout';
        }


    }

    public function alphaBankPayment($input,$request){

        $payment_method_id = intval($input["payment_method_id"]);
        $payment_cardtype = intval($input["cardtype"]);

        $amount = Cart::total();
        $namount = (float)$amount;

        function greeklish($Name){
            $greek   = array('α','ά','Ά','Α','β','Β','γ', 'Γ', 'δ','Δ','ε','έ','Ε','Έ','ζ','Ζ','η','ή','Η','θ','Θ','ι','ί','ϊ','ΐ','Ι','Ί', 'κ','Κ','λ','Λ','μ','Μ','ν','Ν','ξ','Ξ','ο','ό','Ο','Ό','π','Π','ρ','Ρ','σ','ς', 'Σ','τ','Τ','υ','ύ','Υ','Ύ','φ','Φ','χ','Χ','ψ','Ψ','ω','ώ','Ω','Ώ',' ',"'","'",',');
            $english = array('a', 'a','A','A','b','B','g','G','d','D','e','e','E','E','z','Z','i','i','I','th','Th', 'i','i','i','i','I','I','k','K','l','L','m','M','n','N','x','X','o','o','O','O','p','P','r','R','s','s','S','t','T','u','u','Y','Y','f','F','ch','Ch','ps','Ps','o','o','O','O','_','_','_','_');
            $string  = str_replace($greek, $english, $Name);
            return $string;
        }
        $bd = [];
        $bd['billaddress'] = greeklish($input['billaddress']) . ' ' . $input['billaddressnum'];
        $bd['billzip'] = $input['billpostcode'];
        $bd['city'] = greeklish($input['billcity']);
        $bd['billcountry'] = 'GR';
      
        if(Auth::check()) {
            $cuser = Auth::user();
            $uid = $cuser->id;
        }
        else {
            $uid = 0;
        }


        $transaction_arr = [

            "payment_method_id" => $payment_method_id,
            "account_id" => 17,
            "payment_status" => 2,
            "billing_details" => json_encode($bd),//serialize($billing_details),
            "placement_date" => Carbon::now()->toDateTimeString(),
            "ip_address" => $request->ip(),
            "type" => $payment_cardtype,//((Sentinel::inRole('super_admin') || Sentinel::inRole('know-crunch')) ? 1 : 0),
            "status" => 2, //2 PENDING, 0 FAILED, 1 COMPLETED
            "is_bonus" => 0, //$input['is_bonus'],
            "order_vat" => 0, //$input['credit'] - ($input['credit'] / (1 + Config::get('dpoptions.order_vat.value') / 100)),
            "surcharge_amount" => 0,
            "discount_amount" => 0,
            "amount" => $namount,
            "total_amount" => $namount,
            "trial" => false
        ];//$input['credit']

        $transaction = Transaction::create($transaction_arr);
        if ($transaction) {
            $request->session()->put('transaction_id', $transaction->id);
            return redirect('/payment-dispatch/checkout/'.$transaction->id);

        } else {
            // there was an error
            dd('Error');
        }
    }

    public function dpremove($item)
    {
        
        //dd('sex');
        /*$t = Cart::get($id);
        $t->remove($id);*/
        $id = $item;
        Cart::remove($id);

        //UPDATE SAVED CART IF USER LOGGED
        if($user = Auth::user()) {

           // dd($user->cart);
            $existingcheck = ShoppingCart::where('identifier', $user->id)->first();

            if($existingcheck) {
                $existingcheck->delete($user->id);

            }

            if($user->cart){
               $user->cart->delete();
            }
        }


        $isAjax = request()->ajax();

        if ($isAjax) {
            return response([ 'message' => 'success', 'id' => $id ]);
        }

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

        return Redirect::to('/registration');

        /*return redirect()->route('cart')->with('success',
            "{$product->name} was successfully removed from the shopping cart."
        );*/
    }

    public function checkCoupon(Request $request, $event){

        if(Session::get('coupon_code')){
            return response()->json([
                'success' => 'used',
                'message' => 'Your coupon has been declined. Please try again.'
            ]);
        }

        //$coupon = Coupon::where('code_coupon',$request->coupon)->where('status',true)->get();
        //dd($request->all());
        $event = Event::find($event);
        $coupon = $event->coupons()->where('status',true)->get();
        if(count($coupon) > 1){
            foreach($coupon as $key => $c){

                if($c->code_coupon === $request->coupon){
                    //$coupon = $c->get();
                }else{
                    unset($coupon[$key]);
                }
            }
        }

        if(count($coupon) > 0){
            $coupon = $coupon->first();

            if(trim($request->coupon) === trim($coupon->code_coupon) && $coupon->status && trim($request->coupon) != ''){

                if($coupon->percentage){
                    $price = $request->price * $coupon->price / 100;
                    $newPrice = $request->price - $price;
                    $priceOf = $coupon->price . '%';
                }else{
                    $newPrice = $coupon->price;
                    $priceOf = ($coupon->price / $request->price) * 100;
                    $priceOf = round($priceOf,2) . '%';
                }

                $savedPrice = $request->price - $newPrice;
                Session::put('coupon_code',$request->coupon);
                Session::put('coupon_price',$newPrice);
                Session::put('priceOf',$priceOf);
                
                $instOne = $newPrice;
                $instTwo = round($newPrice / 2, 2);
                $instThree = round($newPrice / 3, 2);

                if($instOne - floor($instOne)>0){
                    $instOne = number_format($instOne , 2 , '.', ',');
                }else{
                    $instOne = number_format($instOne , 0 , '.', '');
                }

                if($instTwo - floor($instTwo)>0){
                    $instTwo = number_format($instTwo , 2 , '.', ',');
                }else{
                    $instTwo = number_format($instTwo , 0 , '.', '');
                }

                if($instThree - floor($instThree)>0){
                    $instThree = number_format($instThree , 2 , '.', ',');
                }else{
                    $instThree = number_format($instThree , 0 , '.', '');
                }

                return response()->json([
                    'success' => true,
                    'new_price' => $instOne,
                    'savedPrice' => round($savedPrice, 2) ,
                    'priceOf' => $priceOf,
                    'newPriceInt2' => $instTwo,
                    'newPriceInt3' => $instThree,
                    'message' => 'Success! Your coupon has been accepted.',
                    'coupon_code' => $request->coupon
                ]);
            }


        }

        return response()->json([
            'success' => false,
            'message' => 'Your coupon has been declined. Please try again.'
        ]);

    }

    public function checkCode(Request $request){

        $event = Event::find($request->event);

        $code = $event->coupons()->where('code_coupon',$request->eventCode)->first();

        if(!$code){
            return response()->json([
                'success' => false,
                'message' => 'The code you have entered is incorrect. Please try again.'
            ]);
        }else if($code->used == 1){
            return response()->json([
                'success' => false,
                'message' => 'The code you have entered is already taken. Please try another code.'
            ]);
        }else{

            Cart::instance('default')->destroy();
            $item = Cart::add('free_code', $event->title, 1, (float)0, ['type' => 'free_code', 'event' => $event->id, 'code_id' => $code->id])->associate(Ticket::class);
            //$code->used = true;
            $code->save();

            if($user = Auth::user()) {


                if($user->cart){
                    $user->cart->delete();
                }

                $cartCache = new CartCache;

                $cartCache->ticket_id = 0;//'coupon code ' . $event->id;
                $cartCache->product_title = $event->title;
                $cartCache->quantity = 1;
                $cartCache->price = (float) 0;
                $cartCache->type = 9;
                $cartCache->event = $event->id;
                $cartCache->user_id =Auth::user()->id;
                $cartCache->slug =  base64_encode('coupon code ' . $event->id . Auth::user()->id . $event->id);

                $cartCache->save();

            }

            return response()->json([
                'success' => true,
                'message' => 'To event προστεθηκε στο καλάθι',
                'redirect' => '/registration'
            ]);
        }

    }

    public function completeRegistration(Request $request){

        
        $data = [];
        $option = Option::where('abbr','deree-codes')->first();
        //$dereelist = json_decode($option->settings, true);
        $code = 0;

        //dd($dereelist);

        $c = Cart::content()->count();
       
        if((!$user = User::where('email',$request->email[0])->first())){
            $input = [];
            $formData = $request->all();
            unset($formData['_token']);
            unset($formData['terms_condition']);
            unset($formData['update']);
            unset($formData['type']);
            
            foreach($formData as $key => $value){
                $input[$key] = $value[0];
            }

            $input['password'] = Hash::make(date('Y-m-dTH:i:s'));
            
            $user = User::create($input);

            $code = Activation::create([
                'user_id' => $user->id,
                'code' => Str::random(40),
                'completed' => false,
            ]);
            $user->role()->attach(7);
            
            $connow = Carbon::now();
            $clientip = '';
            $clientip = \Request::ip();
            $user->terms = 1;
            $user->consent = '{"ip": "' . $clientip . '", "date": "'.$connow.'" }';
        
        }


        if ($c > 0) {
            $cart_contents = Cart::content();
            foreach ($cart_contents as $item) {

                $event_id = $item->options->event;
                $event_type = $item->options->type;
                $codeId = $item->options->code_id;

                break;
            }
            $content = Event::find($event_id);
        }

        $payment_method_id = 1;//intval($input["payment_method_id"]);
        $payment_cardtype = 9; //free;
        $amount = 0;
        $namount = (float)$amount;

        $code = $content->coupons->where('id', $codeId)->first();

        if($code){
            $code = $code->code_coupon;
        }

        $transaction_arr = [

            'payment_method_id' => $payment_method_id,
            'account_id' => 17,
            'payment_status' => 1,
            'billing_details' => json_encode([]),
            'placement_date' => Carbon::now()->toDateTimeString(),
            'ip_address' => '127.0.0.1',
            'type' => $payment_cardtype,//((Sentinel::inRole('super_admin') || Sentinel::inRole('know-crunch')) ? 1 : 0),
            'status' => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
            'coupon_code' =>  $code,
            'is_bonus' => 0, //$input['is_bonus'],
            'order_vat' => 0, //$input['credit'] - ($input['credit'] / (1 + Config::get('dpoptions.order_vat.value') / 100)),
            'surcharge_amount' => 0,
            'discount_amount' => 0,
            'amount' => $namount, //$input['credit'],
            'total_amount' => $namount,
            'trial' => false,
        ];

        $transaction = Transaction::create($transaction_arr);

        if ($transaction) {
            // set transaction id in session
            
            $pay_seats_data = ["names" => [$request->firstname[0]],"surnames" => [$request->lastname[0]],"emails" => [$request->email[0]],
            "mobiles" => [$request->mobile[0]],"addresses" => [$user->address],"addressnums" => [$user->address_num],
            "postcodes" => [$user->postcode],"cities" => [$user->city],"jobtitles" => [$user->job_title],
            "companies" => [$user->company],"students" => [""], "afms" => [$user->afm]];


            $deree_user_data = [$user->email => $user->partner_id];

            //dd($ticket->event->title);
            $cart_data = ["manualtransaction" => ["rowId" => "manualtransaction","id" => 'coupon code ' . $content->id,"name" => $content->title,"qty" => "1","price" => $amount,"options" => ["type" => "9","event"=> $content->id],"tax" => 0,"subtotal" => $amount]];

            $status_history[] = [
            'datetime' => Carbon::now()->toDateTimeString(),
            'status' => 1,
            'user' => [
                'id' => $user->id, //0, $this->current_user->id,
                'email' => $user->email,//$this->current_user->email
                ],
            'pay_seats_data' => $pay_seats_data,//$data['pay_seats_data'],
            'pay_bill_data' => [],
            'cardtype' => 9,
            'installments' => 1,
            'deree_user_data' => $deree_user_data, //$data['deree_user_data'],
            'cart_data' => $cart_data //$cart
            ];

            //Transaction::where('id', $transaction['id'])
            $transaction->update(['status_history' => json_encode($status_history)/*, 'billing_details' => $tbd*/]);

            $user->events()->attach($content->id,['comment' => 'upon coupon']);

            if($user->cart){
                $user->cart->delete();
            }
            Cart::instance('default')->destroy();
            $content->coupons->where('id', $codeId)->first()->update(['used' => true]);

            $data['event']['title'] = $content->title;
            $data['event']['slug'] = $content->slugable->slug;
            $data['event']['facebook'] = url('/') . '/' .$content->slugable->slug .'?utm_source=Facebook&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&quote='.urlencode("Proudly participating in ". $content->title . " by KnowCrunch.");
            $data['event']['twitter'] = urlencode("Proudly participating in ". $content->title . " by KnowCrunch. 💙");
            $data['event']['linkedin'] = urlencode(url('/') . '/' .$content->slugable->slug .'?utm_source=LinkedIn&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&title='."Proudly participating in ". $content->title . " by KnowCrunch. 💙");

            $categoryScript = 'Event > ' . $content->category->first()->name;

            $KC = "KC-";
            $time = strtotime($transaction->placement_date);
            $MM = date("m",$time);
            $YY = date("y",$time);


            $option = Option::where('abbr','website_details')->first();
		    //next number available up to 9999
		    $next = $option->value;

            if($user->kc_id == '') {

                $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);
                $knowcrunch_id = $KC.$YY.$MM.$next_kc_id;
                $user->kc_id = $knowcrunch_id;

                $user->save();

                if($next == 9999){
                    $next = 1;
                }else{
                    $next += 1;
                }
                $option->value = $next;
                $option->save();
            }
            $this->sendEmails($transaction,$content,$user);

            $data['info']['success'] = true;
            $data['info']['title'] = '<h1>Booking successful</h1>';
            $data['info']['message'] = '<h2>Thank you and congratulations!<br/>We are very excited about you joining us. We hope you are too!</h2>
            <p>An email with more information is on its way to your inbox.</p>';
            $data['info']['transaction'] = $transaction;
            $data['info']['statusClass'] = 'success';

            $data['tigran'] = ['OrderSuccess_id' => $transaction['id'], 'OrderSuccess_total' => 0.00, 'Price' => 0.00,'Product_id' => $content->id, 'Product_SKU' => $content->id,
                        'ProductCategory' => $categoryScript, 'ProductName' =>  $content->title, 'Quantity' => $item->qty, 'TicketType'=>'Upon Coupon','Event_ID' => 'kc_' . time() 
                ];

            //$this->fbp->sendPurchaseEvent($data);


        }


        return view('theme.cart.new_cart.thank_you',$data);
       
    }

    public function sendEmails($transaction,$content,$user)
    {

        $muser = [];
        $muser['name'] = $user->first_name . ' ' . $user->last_name;
        $muser['id'] = $user->id;
        $muser['first'] = $user->first_name;
        $muser['email'] = $user->email;
        $muser['createAccount'] = false;

        $tickettypedrop = 'Upon Coupon';
        $tickettypename = 'Upon Coupon';
        $eventname = $content->title;
        $date = '';
        $eventcity = '';


        $extrainfo = [$tickettypedrop, $tickettypename, $eventname, $date, '-', '-', $eventcity];
        $helperdetails[$user->email] = ['kcid' => $user->kc_id, 'deid' => $user->partner_id, 'stid' => $user->student_type_id, 'jobtitle' => $user->job_title, 'company' => $user->company, 'mobile' => $user->mobile];

        $adminemail = 'info@knowcrunch.com';

        $data = [];
        $data['user'] = $muser;
        $data['trans'] = $transaction;
        $data['extrainfo'] = $extrainfo;
        $data['helperdetails'] = $helperdetails;
        $data['eventslug'] = $content->slug;

        $sent = Mail::send('emails.admin.info_new_registration', $data, function ($m) use ($adminemail,$muser) {

            $fullname = $muser['name'];
            $first = $muser['first'];
            $sub = 'Knowcrunch - ' . $first . ' your registration has been completed.';
            $m->from($adminemail, 'Knowcrunch');
            $m->to($muser['email'], $fullname);
            $m->subject($sub);
        });


        //send elearning Invoice
        $transdata = [];
        $transdata['trans'] = $transaction;

        $transdata['user'] = $muser;
        $transdata['trans'] = $transaction;
        $transdata['extrainfo'] = $extrainfo;
        $transdata['helperdetails'] = $helperdetails;
        $transdata['coupon'] = $transaction->coupon_code;

        $sentadmin = Mail::send('emails.admin.admin_info_new_registration', $transdata, function ($m) use ($adminemail) {
            $m->from($adminemail, 'Knowcrunch');
            $m->to($adminemail, 'Knowcrunch');
            $m->subject('Knowcrunch - New Registration');
        });

        if(!$user->statusAccount->completed){
                    
            $data['user']['createAccount'] = true;
            $cookieValue = base64_encode($user->id . date("H:i"));
            setcookie('auth-'.$user->id, $cookieValue, time() + (1 * 365 * 86400), "/"); // 86400 = 1 day
        
            $coockie = new CookiesSMS;
            $coockie->coockie_name = 'auth-'.$user->id;
            $coockie->coockie_value = $cookieValue;
            $coockie->user_id = $user->id;
            $coockie->sms_code = -1;
            $coockie->sms_verification = true;

            $coockie->save();

            $user->statusAccount->completed = true;
            $user->statusAccount->save();

        }

        $user->notify(new WelcomeEmail($user,$data));

    }

    public function update(Request $request)
    {
        //return $request->all();
    	$updates = $request->get('update');
    	foreach ($updates as $key => $value) {
            //dd($value['quantity']);
    		Cart::update($key, $value['quantity']);
    	}

        //UPDATE SAVED CART IF USER LOGGED
        if($user = Auth::user()) {

            $existingcheck = ShoppingCart::where('identifier', $user->id)->first();

            if($user->cart){
                $user->cart->delete();
            }

            //Cart::restore($user->id
            if($existingcheck) {
                $existingcheck->delete($user->id);
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            }
            else {
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            }
        }

        //Cart::update();
        return Redirect::to('/registration')->with('success', 'Shopping cart was successfully updated.');
        /*return redirect()->route('cart')->with('success', 'Shopping cart was successfully updated.');*/
    }

}
