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

class CartController extends Controller
{

    public function checkoutcheck(Request $request)
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

    }

        /**
     * Display a listing of products on the cart.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

       
        $data = array();
        //$data['lang'] = $_ENV['LANG'];
        //$data['website'] = $_ENV['WEBSITE'];

        $data['pay_methods'] = array();


        $data['eventtickets'] = [];
        $categoryScript = ''; 
        $data['couponEvent'] = false;

        /*$data['pay_methods'] = PaymentMethod::where('status', [1,2])->whereNotIn('method_slug', ['bonus'])->get()->toArray();*/
        /*if(Sentinel::check()) {
        if (Sentinel::inRole('super_admin') || Sentinel::inRole('administrator')) {
            $data['pay_methods'] = PaymentMethod::whereIn('status', [1,2])->get();
        } else {*/
            //$data['pay_methods'] = PaymentMethod::where('status', 1)->get();
             //DEBUG ONLY
            $data['pay_methods'] = PaymentMethod::whereIn('status', [1,2])->get();
        /*}
        }*/


        //$data['social_media'] =  \Config::get('dpoptions.social_media.settings');

        //$data['google_analytics'] =  \Config::get('dpoptions.google_analytics');

       // $data['filter_location'] = Category::where('id', 9)->first()->getDescendants()->where('status',1)->where('type',0)->sortBy('name')->toHierarchy();

       // $data['filter_type'] = Category::where('id', 12)->first()->getDescendants()->where('status',1)->where('type',0)->toHierarchy();

        /*$data['footerLinks'] = Content::filterContent([
                            'status' => 1,
                            'type' => 36,
                            'author_id' => '',
                            'search_term' => '',
                            'abbr' => 'footergroup',
                            'sort' => 'priority',
                            'sort_direction' => 'asc',
                            'lang' => $data['lang'],
                        ])->get();

        $data['block_logos'] = Content::filterContent([
                    'take' => 1,
                    'status' => 1,
                    'type' => 31,
                    'lang' => $data['lang'],
                    'abbr' => 'block_logos',
                    'sort' => 'published_at',
                    'sort_direction' => 'desc',
                    'excluding_ids' => [],
                    'on_categories' => []
                ])->with('categories','tags','author','featured.media')->get();*/



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


        
        //check for logged in user

        $loggedin_user = Auth::user();

        if($loggedin_user) {
            $data['cur_user'] = $loggedin_user;
            $ukcid = $loggedin_user->kc_id;
        }

        $event_id = 0;

        $c = Cart::content()->count();
        if ($c > 0) {
            $cart_contents = Cart::content();
            foreach ($cart_contents as $item) :
                $event_id = $item->options->event;
                $event_type = $item->options->type;

                break;
            endforeach;
           
            
            $ev = Event::find($event_id);
            if($ev) {
                
                if($ev->view_tpl == 'event_free_coupon'){
                    $data['couponEvent'] = true;
                }
                //dd($ev->ticket);
                $data['eventtickets'] = $ev->ticket;
                $data['city'] = $ev->city->first() ? '' : '';
                $data['duration'] = '';
                
                $categoryScript = 'Event > ' . $ev->category->first()->name;
                //dd($categoryScript);
      


                if(isset($ev->custom_style) && $ev->custom_style != '' && $ev->custom_style == 'knowcrunch-event') {
                    $data['paywithstripe'] = 1;
                }
                if($ev->view_tpl == 'elearning_event'){
                    $data['paywithstripe'] = 1;
                }

                //dd($ev->summary1->where('section','date')->first());
              
                $data['duration'] = $ev->summary1->where('section','date')->first() ? $ev->summary1->where('section','date')->first()->title:'';

                /*if($ev->customFields) {
                    foreach ($ev->customFields as $key => $cfield) {
                       if($cfield->name == 'simple_text' && $cfield->priority == 0) {
                        //dd($cfield);
                            $data['ev_date_help'] = $cfield->value;
                            break;
                       }
                    }

                    foreach ($ev->customFields as $key => $cfield) {
                        if($cfield->name == 'simple_text' && $cfield->priority == 12) {
                        
                             $data['duration'] = $cfield->value;
                             break;
                        }
                     }
                }*/
            }
           
            $data['eventId'] = $event_id;
            $data['categoryScript'] = $categoryScript;
            
            if(!$loggedin_user) {
                

                return view('theme.cart.cart', $data);
            }
          
            else {
                // Get user billing details in order to pre populate values
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

                $data['default_card'] = $loggedin_user->defaultPaymentMethod()->card;
               
                return view('theme.cart.cart', $data);
            }
            
            return view('theme.cart.cart', $data);
        }
        else {

            
                if(request()->has('cart')){
                  //  dd(request('cart'));
                    //Cart::instance('default')->destroy();
                    //$cartCache = CartCache::where('slug', request('cart'))->first();
                    
                   // if($cartCache){
                     //  Cart::add($cartCache->ticket_id, $cartCache->product_title, $cartCache->quantity, $cartCache->price, 
                    //    ['type' => $cartCache->type, 'event' => $cartCache->event])->associate('PostRider\Content');
                  //  }

                

                    //return redirect('/cart');
                }

            return Redirect::to('/');
        }



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

        //dd('dfsafdas');
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
       
        $ticketob = $product->ticket->groupBy('ticket_id')[$ticket]->first();
        
        if($ticket == 'free'){
            $this->addFreeToCart($product, $ticket, $ticket);
        }else{
            $item = $this->addToCart($product, $ticketob, $type);
        }

        if ($isAjax) {
            return response($item->toArray());
        }

        if($ticket == 'free'){
            return Redirect::to('/cart')->with('success',
                "Free ticket was successfully added to your bag."
            );
        }else{
            return Redirect::to('/cart')->with('success',
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

    public function userPaySbt(Request $request){
       
       //dd($request->all());
        $this->validate($request, [
            'mobileCheck.*' => 'phone:AUTO',
        ]);

        $input = $request->all();
        $payment_method_id = intval($input["payment_method_id"]);

        if($payment_method_id == 100) {
            
            $redurl = $this->postPaymentWithStripe($input);    
            return redirect($redurl);
        }

    }

    public function postPaymentWithStripe($input)
    {
       // dd($input);
        Session::forget('dperror');
        Session::forget('error');

        //$current_user = Sentinel::getUser();
        $dpuser = Auth::user();
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
            $ev_date_help = $ev->summary1->where('section','date')->first() ? $ev->summary1->where('section','date')->first() : 'date';
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
            /*if (isset($input['coupon'])){
                $coupon = Coupon::where('coupon_code',$input['coupon'])->where('status', true)->get();
            }*/

            /*if(isset($input['coupon']) && count($coupon) > 1){
                foreach($coupon as $key => $c){
                    if(!($c->coupon_code === $input['coupon'])){
                        unset($coupon[$key]);
                    }
                }
            }

            $couponCode = '';
            if(count($coupon) > 0){
                $coupon = $coupon->first();
                if (isset($input['coupon'])){
                    if($input['coupon'] && trim($input['coupon']) != '' && trim($coupon->coupon_code)!= '' && $coupon->status && trim($input['coupon']) == trim($coupon->coupon_code)){
                        $amount = $coupon->price * $qty;
                        $couponCode = $input['coupon'];
                    }
                }
            }*/
            $namount = (float)$amount;

            $temp = [];
            if(isset($pay_bill_data)) {
                $temp = $pay_bill_data;
                if($temp['billing'] == 1) {
                    $temp['billing'] = 'Receipt requested';
                    //generate array for stripe billing
                   // $st_desc = $temp['billname'] . ' ' . $temp['billsurname'];
                    $st_name =  $temp['billname'] . ' ' . $temp['billsurname'];
                    $st_tax_id = 'EL'.$temp['billafm'];
                    $st_line1 = $temp['billaddress'] . ' ' . $temp['billaddressnum'];
                    $st_postal_code = $temp['billpostcode'];
                    $st_city = $temp['billcity'];
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

            

             if($installments > 1) {

                $instamount = round($namount / $installments, 2);

                if (! $dpuser->isBillable()) {
                    //dd("Entity is not ready to be billed!");
                    $dpuser->createStripeCustomer([
                        'name' => $st_name,
                        'email' => $dpuser->email,
                        'metadata' => $temp,
                        //'description' => $st_desc,
                        'tax_info' => ['tax_id' => $st_tax_id, 'type' => 'vat'],
                        'shipping' => ['name' => $st_name, 'address' => ['line1' => $st_line1,'postal_code' => $st_postal_code,'city' => $st_city,'country' => 'GR']],

                    ]); //,'phone' => $st_phone

                    // Check if the entity has any active subscription
                    if ($dpuser->isSubscribed()) {
                       // dd('Entity has at least one active subscription!');
                        \Session::put('dperror','You have at least one active subscription!');
                          return '/cart';
                    }
                    else {
                        // Create the card
                        //dd('here');
                        if (! $dpuser->hasActiveCards()) {
                            $card = $dpuser->card()->create($token['id']);
                        }
                        // Create the plan to subscribe
                        $desc = $installments . ' installments';
                        $planid = 'plan_'.$dpuser->id.'_E_'.$ev->id.'_T_'.$ticket_id.'_x'.$installments;
                        $name = $ev_title . ' ' . $ev_date_help . ' | ' . $desc;
                        $plan = $stripe->plans()->create([
                            'id'                   => $planid,
                            'name'                 => $name,
                            'amount'               => $instamount,
                            'currency'             => 'eur',
                            'interval'             => 'month',
                            'statement_descriptor' => $desc,

                        ]);

                        // Create the subscription
                        $sub = $dpuser
                            ->subscription()
                            ->onPlan($planid)
                            ->create(['metadata' => ['installments_paid' => 0, 'installments' => $installments]])
                        ;

                    }

                }
                else {

                    $dpuser->subscription()->syncWithStripe();
                   // dd("Entity ready to be billed!");
                    // Check if the entity has any active subscription
                    if ($dpuser->isSubscribed()) {
                        \Session::put('dperror','You have at least one active subscription!');
                          return '/cart';
                    }
                    else {
                       
                        //./ngrok authtoken 69hUuQ1DgonuoGjunLYJv_3PVuHFueuq5Kiuz7S1t21
                        // Create the plan to subscribe
                        $desc = $installments . ' installments';
                        $planid = 'plan_'.$dpuser->id.'_E_'.$ev->id.'_T_'.$ticket_id.'_x'.$installments;
                        $name = $ev_title . ' ' . $ev_date_help . ' | ' . $desc;

                            $plan = $stripe->plans()->create([
                                'id'                   => $planid,
                                'name'                 => $name,
                                'amount'               => $instamount,
                                'currency'             => 'eur',
                                'interval'             => 'month',
                                'statement_descriptor' => $desc,

                            ]);

                        $sub = $dpuser
                            ->subscription()
                            ->onPlan($planid)
                            ->create(['metadata' => ['installments_paid' => 0, 'installments' => $installments]])
                        ;

                    }
                }

               


             }


            if($dpuser && $installments > 1) {

                $charge['status'] = 'succeeded';
                $charge['type'] = $installments . ' Installments';
            }
            else {
                
                $dpuser->updateStripeCustomer([
                    'name' => $st_name,
                    'email' => $dpuser->email,
                    'metadata' => $temp,
                    //'description' => $st_desc,
                  
                    //'tax_info' => ['tax_id' => $st_tax_id, 'type' => 'vat'],
                    'shipping' => ['name' => $st_name, 'address' => ['line1' => $st_line1,'postal_code' => $st_postal_code,'city' => $st_city,'country' => 'GR']],
                    'address' => ['line1' => $st_line1,'postal_code' => $st_postal_code,'city' => $st_city,'country' => 'GR'],

                ]);
            
                $temp['customer'] = $dpuser->email;
                $nevent = $ev_title . ' ' . $ev_date_help;
                
                 $charge = $dpuser->charge(
                    $namount,
                    $dpuser->defaultPaymentMethod()->id,
                    [
                    
                        'currency' => 'eur',
                        'amount' => $namount,
                        'description' => $nevent,
                        'customer' => $dpuser->stripe_id,
                        'metadata' => $temp,
       
                    ]
                );

            }

        

            if($charge->status == 'succeeded') {
                 /**
                 * Write Here Your Database insert logic.
                 */
                
                 $status_history = [];
           //      $payment_cardtype = intval($input["cardtype"]);
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
                 //   'cardtype' => $payment_cardtype,
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
                    //"coupon_code" => $couponCode,
                    "amount" => $namount,
                    "total_amount" => $namount
                ];

                $transaction = Transaction::create($transaction_arr);

                if($transaction) {

                    $transaction->user()->save($dpuser);
                    $transaction->event()->save($ev);

                    if(!Invoice::latest()->first()){
                        $invoiceNumber = sprintf('%04u', 1);
                    }else{
                        $invoiceNumber = Invoice::latest()->first()->invoice;
                        $invoiceNumber = (int) $invoiceNumber + 1;
                        $invoiceNumber = sprintf('%04u', $invoiceNumber);
                    }

    
                    $elearningInvoice = new Invoice;
                    $elearningInvoice->name = json_decode($transaction->billing_details,true)['billname'];
                    $elearningInvoice->amount = round($namount / $installments, 2);
                    $elearningInvoice->invoice = $invoiceNumber;
                    $elearningInvoice->date = Carbon::today()->toDateString();
                    $elearningInvoice->instalments_remaining = $installments;
                    $elearningInvoice->instalments = $installments;

                    $elearningInvoice->save();

                    $elearningInvoice->user()->save($dpuser);
                    $elearningInvoice->event()->save($ev);
                    $elearningInvoice->transaction()->save($transaction);

                    \Session::put('transaction_id', $transaction->id);
                }
              
                return '/info/order_success';
               
            } else {
                //dd('edwww1');
                 \Session::put('dperror','Cannot complete the payment!!');
                //return redirect('/info/order_error');
                  return '/cart';
            }
        }
        catch (Exception $e) {
            //dd('edwww2');
             \Session::put('dperror',$e->getMessage());
              return '/cart';
            // return redirect('/info/order_error');
        }
        catch(\Stripe\Exception\CardErrorException $e) {
            //dd('edwww3'); 
            \Session::put('dperror',$e->getMessage());
              return '/cart';
             //return redirect('/info/order_error');
        }
        catch(\Stripe\Exception\MissingParameterException $e) {
            //dd($e);
            \Session::put('dperror',$e->getMessage());
            //return redirect('/info/order_error');
            return '/cart';
        }
        catch(\Stripe\Api\Exception\ServerErrorException $e) {
            //dd($e);
            \Session::put('dperror',$e->getMessage());
            //return redirect('/info/order_error');
            return '/cart';
        }
        

    }

}
