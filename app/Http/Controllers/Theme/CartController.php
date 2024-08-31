<?php

namespace App\Http\Controllers\Theme;

use App\Events\EmailSent;
use App\Http\Controllers\Controller;
use App\Model\Activation;
use App\Model\CartCache;
use App\Model\CookiesSMS;
use App\Model\Delivery;
use App\Model\Event;
use App\Model\Invoice;
use App\Model\Option;
use App\Model\PaymentMethod;
use App\Model\ShoppingCart;
use App\Model\Ticket;
use App\Model\Transaction;
use App\Model\User;
use App\Notifications\WelcomeEmail;
use App\Services\FBPixelService;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Carbon\Carbon;
use Cart as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Cashier\Payment;
use Mail;
use Redirect;
use Stripe\Plan;
use Stripe\Stripe;
use Stripe\StripeClient;

class CartController extends Controller
{
    public function __construct(FBPixelService $fbp)
    {
        $this->fbp = $fbp;

        $this->middleware('cart')->except('cartIndex', 'completeRegistration', 'validation', 'checkCode', 'add');
        //$this->middleware('cart')->except('cartIndex','validation','checkCode');
        $this->middleware('code.event')->only('completeRegistration');
        $this->middleware('registration.check')->except('cartIndex', 'completeRegistration', 'validation', 'checkCode', 'add');
        //$this->middleware('registration.check');
        $this->middleware('billing.check')->only('billingIndex', 'billing', 'checkoutIndex');

        $fbp->sendPageViewEvent();
    }

    public function calculateInstallments($eventInfo)
    {
        $availableInstallments = 1;

        $delivery = Delivery::find($eventInfo['delivery']);

        $exceptInstallmentsDates = [config('services.promotions.BLACKFRIDAY'), config('services.promotions.CYBERMONDAY')];

        if (!in_array(date('d-m-Y'), $exceptInstallmentsDates)) {
            if ($delivery['installments'] != null && $delivery['installments'] != 0) {
                $availableInstallments = (int) $delivery['installments'];
            }

            if (!is_null($eventInfo['payment_installments'])) {
                $availableInstallments = (int) $eventInfo['payment_installments'];
            }
        }

        return $availableInstallments;
    }

    private function initCartDetails($data)
    {
        $event_id = 0;
        $data['price'] = 'free';
        $data['type'] = -1;
        $totalitems = 0;
        $ticketType = '';
        $data['curStock'] = 1;
        $tr_price = 0;
        $data['elearning'] = false;
        $data['eventFree'] = false;

        $c = Cart::content()->count();
        if ($c > 0) {
            $cart_contents = Cart::content();
            foreach ($cart_contents as $item) {
                $totalitems += $item->qty;
                $event_id = $item->options->event;
                $event_type = $item->options->type;

                $data['itemid'] = $item->rowId;

                break;
            }

            $ev = Event::find($event_id);
            if ($ev) {
                $data['elearning'] = $ev->delivery->first() && $ev->delivery->first()->id == 143 ? true : false;
                $data['eventId'] = $event_id;
                $data['productName'] = $ev->title;

                if ($ev->view_tpl == 'event_free_coupon') {
                    $data['couponEvent'] = true;
                }
                //dd($ev->ticket);
                $data['eventtickets'] = $ev->ticket;
                $data['city_event'] = $ev->city->first() ? '' : '';
                $data['duration'] = '';

                $categoryScript = $ev->delivery->first() && $ev->delivery->first()->id == 143 ? 'Video e-learning courses' : 'In-class courses'; //'Event > ' . $ev->category->first()->name;
                //dd($categoryScript);

                $data['stripe_key'] = '';
                $data['paywithstripe'] = 0;

                if ($ev->paymentMethod->first()) {
                    if ($ev->paymentMethod->first()->method_slug == 'stripe') {
                        $data['paywithstripe'] = 1;
                        session()->put('payment_method', $ev->paymentMethod->first()->id);
                        $data['stripe_key'] = config('app.PAYMENT_PRODUCTION') ? $ev->paymentMethod->first()->processor_options['key'] :
                                                                                $ev->paymentMethod->first()->test_processor_options['key'];
                    }
                }
                $data['pay_methods'] = $ev->paymentMethod->first();

                //$data['duration'] = $ev->summary1->where('section','date')->first() ? $ev->summary1->where('section','date')->first()->title:'';
                //$data['hours'] = $ev->summary1->where('section','duration')->first() ? $ev->summary1->where('section','duration')->first()->title:'';
                $eventInfo = $ev->event_info();
                if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
                    $data['duration'] = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
                                        $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                                                    $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
                } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
                    $data['duration'] = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
                                            $eventInfo['inclass']['dates']['visible']['emails'] ? $eventInfo['inclass']['dates']['text'] : '';
                }

                $data['hours'] = isset($eventInfo['hours']['hour']) && isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

                $data['city_event'] = $ev->city->first() ? $ev->city->first()->name : '';
                $data['coupons'] = $ev->coupons->where('price', '>', 0)->toArray();
            }

            $data['eventId'] = $event_id;
            $data['categoryScript'] = $categoryScript;

            $data['installments'] = $this->calculateInstallments($eventInfo);

            $cart_contents = Cart::content();
            foreach ($cart_contents as $item) {
                if ($item->options->has('type')) {
                    $data['type'] = $item->options->type;
                }

                foreach ($data['eventtickets'] as $tkey => $tvalue) {
                    if ($tvalue->pivot->event_id == $item->options->event && $tvalue->ticket_id == $item->id) {
                        $ticketType = $tvalue->type;
                        $data['curStock'] = $tvalue->pivot->quantity;
                        $oldPrice = $tvalue->pivot->price * $totalitems;
                        $data['oldPrice'] = number_format($tvalue->pivot->price * $totalitems, 2, '.', ','); //$tvalue->pivot->price * $totalitems;
                        $data['showPrice'] = number_format($tvalue->pivot->price * $totalitems, 2, '.', ','); //$tvalue->pivot->price * $totalitems;
                        $data['price'] = $tvalue->pivot->price * $totalitems;
                        $tr_price = $tvalue->pivot->price * $totalitems;
                    }
                }
                break;
            }
        }

        if (Session::get('coupon_code')) {
            $data['price'] = Session::get('coupon_price') * $totalitems;
            $data['savedPrice'] = $oldPrice - Session::get('coupon_price') * $totalitems;
            $data['showPrice'] = number_format($data['price'], 2, '.', ','); //$tvalue->pivot->price * $totalitems;
            $tr_price = Session::get('coupon_price') * $totalitems;
        }

        if (Session::get('priceOf')) {
            $data['priceOf'] = Session::get('priceOf');
        }

        if ($data['type'] == 'free_code') {
            $data['price'] = 'Upon Coupon';
            $data['show_coupon'] = false;
            $ticketType = 'Upon Coupon';
            $tr_price = 0;

            $data['eventFree'] = true;
        } elseif ($data['type'] == 'free') {
            $data['price'] = 'Free';
            $data['show_coupon'] = false;
            $ticketType = 'Free';
            $tr_price = 0;
            $data['eventFree'] = true;
        } elseif ($data['type'] == 'waiting') {
            $data['price'] = 'Waiting list.';
            $data['show_coupon'] = false;
            $ticketType = 'waiting';
            $tr_price = 0;
            $data['eventFree'] = true;
        }

        if (is_numeric($data['price']) && ($data['price'] - floor($data['price']) > 0)) {
            $data['showPrice'] = number_format($data['price'], 2, '.', ',');
            $data['oldPrice'] = number_format($oldPrice, 2, '.', ',');
        } elseif (is_numeric($data['price'])) {
            $data['showPrice'] = number_format($data['price'], 0, '.', ',');
            $data['oldPrice'] = number_format($oldPrice, 0, '.', ',');
        }

        $data['totalitems'] = $totalitems;

        if ($tr_price - floor($tr_price) > 0) {
            $tr_price = number_format($tr_price, 2, '.', '');
        } else {
            $tr_price = number_format($tr_price, 0, '.', '');
            $tr_price = strval($tr_price);
            $tr_price .= '.00';
        }

        $data['tigran'] = ['Price' => $tr_price, 'Product_id' => $data['eventId'], 'Product_SKU' => $data['eventId'],
            'ProductCategory' => $data['categoryScript'], 'ProductName' =>  $ev->title, 'Quantity' => $totalitems, 'TicketType'=>$ticketType, 'Event_ID' => 'kc_' . time(),
        ];

        if (Auth::user()) {
            $data['tigran']['User_id'] = Auth::user()->id;
        } else {
            $data['tigran']['Visitor_id'] = session()->getId();
        }

        return $data;
    }

    public function mobileCheck(Request $request)
    {
        $data = [];
        $validatorArray = [];

        $phones = [];

        $requestPhones = isset($request->all()['mobile']) ? $request->all()['mobile'] : [];
        $requestPhonesCodes = isset($request->all()['country_code']) ? $request->all()['country_code'] : [];

        foreach ($requestPhones as $key => $phone) {
            $countryCode = isset($requestPhonesCodes[$key]) ? $requestPhonesCodes[$key] : '30';
            $phones[] = '+' . $countryCode . $phone;
        }

        $request->request->add(['mobileCheck' => $phones]);
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
        $data = [];
        //$data['lang'] = $_ENV['LANG'];
        //$data['website'] = $_ENV['WEBSITE'];

        $data['pay_methods'] = [];

        $data['eventtickets'] = [];
        $categoryScript = '';
        $data['couponEvent'] = false;

        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');
        } else {
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
        } else {
            $data['pay_bill_data'] = [];
        }

        if (Session::has('cardtype')) {
            $data['cardtype'] = Session::get('cardtype');
        } else {
            $data['cardtype'] = [];
        }

        if (Session::has('installments')) {
            $data['installments'] = Session::get('installments');
        } else {
            $data['installments'] = [];
        }

        $data = $this->initCartDetails($data);

        //check for logged in user
        $loggedin_user = Auth::user();

        for ($i = 1; $i <= $data['totalitems']; $i++) {
            //dd($data['pay_seats_data']);
            //$data['cur_user'][$i] =  isset($data['pay_seats_data']['cur_user'][$i-1]) ? $data['pay_seats_data']['cur_user'][$i-1] : '';
            $data['firstname'][$i - 1] = isset($data['pay_seats_data']['names'][$i - 1]) ? $data['pay_seats_data']['names'][$i - 1] : '';
            $data['lastname'][$i - 1] = isset($data['pay_seats_data']['surnames'][$i - 1]) ? $data['pay_seats_data']['surnames'][$i - 1] : '';

            $data['country_code'][$i - 1] = isset($data['pay_seats_data']['countryCodes'][$i - 1]) ? $data['pay_seats_data']['countryCodes'][$i - 1] : '';
            $data['city'][$i - 1] = isset($data['pay_seats_data']['cities'][$i - 1]) ? $data['pay_seats_data']['cities'][$i - 1] : '';
            $data['mobile'][$i - 1] = isset($data['pay_seats_data']['mobiles'][$i - 1]) ? $data['pay_seats_data']['mobiles'][$i - 1] : '';
            $data['job_title'][$i - 1] = isset($data['pay_seats_data']['jobtitles'][$i - 1]) ? $data['pay_seats_data']['jobtitles'][$i - 1] : '';
            $data['company'][$i - 1] = isset($data['pay_seats_data']['companies'][$i - 1]) ? $data['pay_seats_data']['companies'][$i - 1] : '';
            $data['student_type_id'][$i - 1] = isset($data['pay_seats_data']['student_type_id'][$i - 1]) ? $data['pay_seats_data']['student_type_id'][$i - 1] : '';

            if ($i == 1 && $loggedin_user) {
                $data['email'][$i - 1] = $loggedin_user->email;
            } else {
                $data['email'][$i - 1] = isset($data['pay_seats_data']['emails'][$i - 1]) ? $data['pay_seats_data']['emails'][$i - 1] : '';
            }
        }

        $data['cur_user'][0] = $loggedin_user;
        $data['kc_id'] = '';
        if ($loggedin_user && $data['firstname'][0] == '') {
            $data['firstname'][0] = $loggedin_user->firstname;
            $data['lastname'][0] = $loggedin_user->lastname;
            $data['email'][0] = $loggedin_user->email;
            $data['country_code'][0] = $loggedin_user->country_code;
            $data['city'][0] = $loggedin_user->city;
            $data['mobile'][0] = $loggedin_user->mobile;
            $data['job_title'][0] = $loggedin_user->job_title;
            $data['company'][0] = $loggedin_user->company;
            $data['student_type_id'][0] = $loggedin_user->student_type_id;

            if (isset($data['pay_bill_data']) && empty($data['pay_bill_data'])) {
                $inv = [];
                $rec = [];
                if ($loggedin_user->invoice_details != '') {
                    $inv = json_decode($loggedin_user->invoice_details, true);
                    if (isset($inv['billing'])) {
                        unset($inv['billing']);
                    }
                }

                if ($loggedin_user->receipt_details != '') {
                    $rec = json_decode($loggedin_user->receipt_details, true);
                    if (isset($rec['billing'])) {
                        unset($rec['billing']);
                    }
                }

                $data['pay_bill_data'] = array_merge($inv, $rec);
            }
            if ($data['paywithstripe'] == 1) {
                $data['default_card'] = false; //$loggedin_user->defaultPaymentMethod() ? $loggedin_user->defaultPaymentMethod()->card : false;
            }

            $ukcid = $loggedin_user->kc_id;
            $data['kc_id'] = $ukcid;
        }

        //$this->fbp->sendLeaderEvent($data['tigran']);
        $this->fbp->sendAddToCart($data);

        if ($data['type'] == 1 || $data['type'] == 2 || $data['type'] == 5) {
            return view('theme.cart.new_cart.participant_special', $data);
        }

        if ($data['type'] == 3) {
            return view('theme.cart.new_cart.participant_alumni', $data);
        }

        if ($data['type'] == 'free_code') {
            return view('theme.cart.new_cart.participant_code_event', $data);
        }

        if ($data['type'] == 'free') {
            return view('theme.cart.new_cart.participant_free_event', $data);
        }

        if ($data['type'] == 'waiting') {
            return view('theme.cart.new_cart.participant_waiting_event', $data);
        }

        return view('theme.cart.new_cart.participant', $data);
    }

    public function registration(Request $request)
    {
        $userCheck = Auth::user();
        $data = [];
        $data = $this->initCartDetails($data);
        //dd($data['type']!=3);
        $user = false;

        if ((!$userCheck && !($user = User::where('email', $request->email[0])->first())) && $data['type'] != 3) {
            $input = [];
            $formData = $request->all();
            unset($formData['_token']);
            unset($formData['terms_condition']);
            unset($formData['update']);
            unset($formData['type']);

            foreach ($formData as $key => $value) {
                $input[$key] = $value[0];
            }

            $input['password'] = Hash::make(date('Y-m-dTH:i:s'));

            $user = User::create($input);

            $connow = Carbon::now();
            $clientip = '';
            $clientip = \Request::ip();
            $user->terms = 1;
            $consent['ip'] = $clientip;
            $consent['date'] = $connow;
            $consent['firstname'] = $user->firstname;
            $consent['lastname'] = $user->lastname;
            if ($user->afm) {
                $consent['afm'] = $user->afm;
            }

            $billing = json_decode($user->receipt_details, true);

            if (isset($billing['billafm']) && $billing['billafm']) {
                $consent['billafm'] = $billing['billafm'];
            }

            $user->consent = json_encode($consent);
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
            if ($existingcheck) {
                //$user edww
                if ($user->cart) {
                    $user->cart->delete();
                }
                $existingcheck->delete($user->id);
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            } else {
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            }

            $cart = Cart::content();
            $event = $cart->first()->options->event;
            $tid = $cart->first()->id;

            if ($user->cart) {
                $user->cart->delete();
            }

            $cartCache = new CartCache;

            $cartCache->ticket_id = ($tid == 'free') ? 0 : $tid;
            $cartCache->product_title = $cart->first()->name;
            $cartCache->quantity = $cart->first()->qty;
            $cartCache->price = $cart->first()->price;
            $cartCache->type = ($cart->first()->options->type == 'free') ? 0 : $cart->first()->options->type;
            $cartCache->event = $event;
            $cartCache->user_id = $user->id;
            $cartCache->slug = base64_encode($tid . $user->id . $event);
            $cartCache->save();
        } elseif ($user || $user = $userCheck) {
            $existingcheck = ShoppingCart::where('identifier', $user->id)->first();
            //Cart::restore($user->id
            if ($existingcheck) {
                //$user edww
                if ($user->cart) {
                    $user->cart->delete();
                }
                $existingcheck->delete($user->id);
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            } else {
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            }

            $cart = Cart::content();
            $event = $cart->first()->options->event;
            $tid = $cart->first()->id;

            if ($user->cart) {
                $user->cart->delete();
            }

            $cartCache = new CartCache;

            $cartCache->ticket_id = ($tid == 'free') ? 0 : $tid;
            $cartCache->product_title = $cart->first()->name;
            $cartCache->quantity = $cart->first()->qty;
            $cartCache->price = $cart->first()->price;
            $cartCache->type = ($cart->first()->options->type == 'free') ? 0 : $cart->first()->options->type;
            $cartCache->event = $event;
            $cartCache->user_id = $user->id;
            $cartCache->slug = base64_encode($tid . $user->id . $event);
            $cartCache->save();
        }

        $seats_data = [];

        if ($data['type'] == 3 && $userCheck && $userCheck->kc_id) {
            $seats_data['names'][] = $userCheck->firstname;
            $seats_data['surnames'][] = $userCheck->lastname;
            $seats_data['emails'][] = $userCheck->email;
            $seats_data['mobiles'][] = $userCheck->mobile;
            $seats_data['mobileCheck'][] = $userCheck->mobileCheck;
            $seats_data['countryCodes'][] = $userCheck->country_code;
            $seats_data['cities'][] = $userCheck->city;
            $seats_data['jobtitles'][] = $userCheck->jobtitle;
            $seats_data['companies'][] = $userCheck->company;
            $seats_data['student_type_id'][] = $userCheck->student_type_id;
            Session::put('user_id', $userCheck->id);
        } elseif ($data['type'] != 3) {
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
        } else {
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

        if ($userCheck && isset($seats_data['emails'][0])) {
            $seats_data['emails'][0] = $userCheck->email;
        }

        Session::put('pay_seats_data', $seats_data);

        return redirect('/billing');
    }

    public function billingIndex()
    {
        $data = [];
        $data['pay_methods'] = [];

        $data['eventtickets'] = [];
        $categoryScript = '';
        $data['couponEvent'] = false;

        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');
        } else {
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
        } else {
            $data['pay_bill_data'] = [];
        }

        if (Session::has('cardtype')) {
            $data['cardtype'] = Session::get('cardtype');
        } else {
            $data['cardtype'] = [];
        }
        if (Session::has('installments')) {
            $data['installments'] = Session::get('installments');
        } else {
            $data['installments'] = [];
        }

        $data = $this->initCartDetails($data);

        //check for logged in user
        $loggedin_user = Auth::user();

        $data['billname'] = '';
        $data['billsurname'] = '';
        $data['billaddress'] = '';
        $data['billaddressnum'] = '';
        $data['billpostcode'] = '';
        $data['billcity'] = '';
        $data['billafm'] = '';
        $data['billstate'] = '';
        $data['billemail'] = '';
        $data['billcountry'] = '';

        $data['billname'] = isset($data['pay_bill_data']['billname']) ? $data['pay_bill_data']['billname'] : '';
        $data['billsurname'] = isset($data['pay_bill_data']['billsurname']) ? $data['pay_bill_data']['billsurname'] : '';
        $data['billaddress'] = isset($data['pay_bill_data']['billaddress']) ? $data['pay_bill_data']['billaddress'] : '';
        $data['billaddressnum'] = isset($data['pay_bill_data']['billaddressnum']) ? $data['pay_bill_data']['billaddressnum'] : '';
        $data['billpostcode'] = isset($data['pay_bill_data']['billpostcode']) ? $data['pay_bill_data']['billpostcode'] : '';
        $data['billcity'] = isset($data['pay_bill_data']['billcity']) ? $data['pay_bill_data']['billcity'] : '';
        $data['billafm'] = isset($data['pay_bill_data']['billafm']) ? $data['pay_bill_data']['billafm'] : '';
        $data['billstate'] = isset($data['pay_bill_data']['billstate']) ? $data['pay_bill_data']['billstate'] : '';
        $data['billemail'] = isset($data['pay_bill_data']['billemail']) ? $data['pay_bill_data']['billemail'] : '';
        $data['billcountry'] = isset($data['pay_bill_data']['billcountry']) ? $data['pay_bill_data']['billcountry'] : '';

        if ($loggedin_user) {
            if (isset($data['pay_bill_data']) && empty($data['pay_bill_data'])) {
                $inv = [];
                $rec = [];
                if ($loggedin_user->invoice_details != '') {
                    $inv = json_decode($loggedin_user->invoice_details, true);
                    if (isset($inv['billing'])) {
                        unset($inv['billing']);
                    }
                }

                if ($loggedin_user->receipt_details != '') {
                    $rec = json_decode($loggedin_user->receipt_details, true);
                    if (isset($rec['billing'])) {
                        unset($rec['billing']);
                    }
                }

                $data['pay_bill_data'] = array_merge($inv, $rec);
            }

            $data['billname'] = isset($data['pay_bill_data']['billname']) ? $data['pay_bill_data']['billname'] : '';
            $data['billsurname'] = isset($data['pay_bill_data']['billsurname']) ? $data['pay_bill_data']['billsurname'] : '';
            $data['billaddress'] = isset($data['pay_bill_data']['billaddress']) ? $data['pay_bill_data']['billaddress'] : '';
            $data['billaddressnum'] = isset($data['pay_bill_data']['billaddressnum']) ? $data['pay_bill_data']['billaddressnum'] : '';
            $data['billpostcode'] = isset($data['pay_bill_data']['billpostcode']) ? $data['pay_bill_data']['billpostcode'] : '';
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

    public function billing(Request $request)
    {
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

        if (!$user = Auth::user()) {
            $user = User::find(Session::get('user_id'));
        }

        if ($user) {
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
        $data = [];
        $data['pay_methods'] = [];

        $data['eventtickets'] = [];
        $categoryScript = '';
        $data['couponEvent'] = false;

        if (Session::has('pay_seats_data')) {
            $data['pay_seats_data'] = Session::get('pay_seats_data');
        } else {
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
        } else {
            $data['pay_bill_data'] = [];
        }

        if (Session::has('cardtype')) {
            $data['cardtype'] = Session::get('cardtype');
        } else {
            $data['cardtype'] = [];
        }

        if (Session::has('installments')) {
            $data['installments'] = Session::get('installments');
        } else {
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
        if ((!Auth::user() || (Auth::user() && !Auth::user()->kc_id)) && $type == 3) {
            return back();
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

        // Determine if this is an ajax request
        //dd($ticket);
        $isAjax = $request->ajax();
        // Get the product from the database
        $product = Event::find($id);

        // Check if the product exists on the database
        if (!$product) {
            if ($isAjax) {
                return response('Product was not found!', 404);
            }

            return redirect()->to('/');
        }

        if ($ticket == 'free' || $ticket == 'waiting') {
            $this->addFreeToCart($product, $ticket, $ticket);
        } else {
            if (!isset($product->ticket->groupBy('ticket_id')[$ticket])) {
                return redirect($product->slugable->slug);
            }

            $ticketob = $product->ticket->groupBy('ticket_id')[$ticket]->first();
            $item = $this->addToCart($product, $ticketob, $type);
        }

        if ($isAjax) {
            return response($item->toArray());
        }

        if ($ticket == 'free') {
            return Redirect::to('/registration')->with(
                'success',
                'Free ticket was successfully added to your bag.'
            );
        } elseif ($ticket == 'waiting') {
            return Redirect::to('/registration')/*->with('success',
                "Free ticket was successfully added to your bag."
            )*/;
        } else {
            return Redirect::to('/registration')->with(
                'success',
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
        } else {
            $quantity = 1;
        }

        $eventid = $product->id;
        $item = Cart::add($ticket->ticket_id, $product->title, $quantity, $price, ['type' => $type, 'event' => $eventid])->associate(Ticket::class);

        //SAVE CART IF USER LOGGED
        if (Auth::check()) {
            $user = Auth::user();
            $existingcheck = ShoppingCart::where('identifier', $user->id)->first();
            //Cart::restore($user->id
            if ($existingcheck) {
                //$user edww
                if ($user->cart) {
                    $user->cart->delete();
                }
                $existingcheck->delete($user->id);
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            } else {
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            }

            if ($user->cart) {
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

        $price = (float) 0;
        $quantity = 1;
        $eventid = $product->id;

        $item = Cart::add($ticket, $product->title, $quantity, $price, ['type' => $ticket, 'event' => $eventid])->associate(Ticket::class);

        return $item;

        //return redirect('cart')->withSuccessMessage('Item was added to your cart!');
    }

    public function walletGetTotal(Request $request)
    {
        $input = $request->all();
        $payment_method_id = intval($input['payment_method_id']);
        $data = [];

        Session::put('payment_method_id', $payment_method_id);

        if (isset($input['installments'])) {
            Session::put('installments', $input['installments']);
        } else {
            Session::put('installments', 1);
        }

        if (Session::get('coupon_code')) {
            $input['coupon'] = Session::get('coupon_code');
        }

        $data = $this->initCartDetails($data);

        $installments = isset($input['installments']) ? $input['installments'] : 0;

        $instamount = $data['price'];

        if ($installments > 1) {
            $instamount = round($instamount / $installments, 2);
        }

        return $instamount * 100;
    }

    public function walletPay(Request $request)
    {
        $input = $request->all();
        //dd($input);

        $payment_method_id = intval($input['payment_method_id']);
        $data = [];

        if (isset($input['installments'])) {
            Session::put('installments', $input['installments']);
        } else {
            Session::put('installments', 1);
        }

        if (Session::get('coupon_code')) {
            $input['coupon'] = Session::get('coupon_code');
        }

        $data = $this->initCartDetails($data);
        $this->fbp->sendAddPaymentInfoEvent($data);

        if ($payment_method_id != 1) {
            $url = $this->postPaymentWithStripe($input);
        }

        return $url;
    }

    public function userPaySbt(Request $request)
    {
        $input = $request->all();
        $payment_method_id = intval($input['payment_method_id']);
        $data = [];

        if (isset($input['installments'])) {
            Session::put('installments', $input['installments']);
        } else {
            Session::put('installments', 1);
        }

        if (Session::get('coupon_code')) {
            $input['coupon'] = Session::get('coupon_code');
        }

        $data = $this->initCartDetails($data);
        $this->fbp->sendAddPaymentInfoEvent($data);

        if ($payment_method_id != 1) {
            $redurl = $this->postPaymentWithStripe($input);

            return redirect($redurl);
        } else {
            return $this->alphaBankPayment($input, $request);
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
            //$ev_date_help = $ev->summary1->where('section','date')->first() ? $ev->summary1->where('section','date')->first()->title : 'date';
            $eventInfo = $ev->event_info();
            if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
                $ev_date_help = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
                                    $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                                                $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
            } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
                $ev_date_help = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
                                        $eventInfo['inclass']['dates']['visible']['emails'] ? $eventInfo['inclass']['dates']['text'] : '';
            }

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
        } else {
            $pay_seats_data = [];
        }

        if (Session::has('pay_bill_data')) {
            $pay_bill_data = Session::get('pay_bill_data');
            $bd = json_encode($pay_bill_data);
        } else {
            $bd = '';
            $pay_bill_data = [];
        }

        if (Session::has('installments')) {
            $installments = Session::get('installments');
        } else {
            $installments = 0;
        }

        $input = Arr::except($input, ['_token']);

        try {
            $amount = Cart::total();
            $coupon = [];
            $eventC = Event::find($eventId);
            if ($eventC && isset($input['coupon'])) {
                $coupon = $eventC->coupons()->where('status', true)->get();
            }

            if (isset($input['coupon']) && count($coupon) > 0) {
                foreach ($coupon as $key => $c) {
                    if (!($c->code_coupon === $input['coupon'])) {
                        unset($coupon[$key]);
                    }
                }
            }

            $couponCode = '';
            if (count($coupon) > 0) {
                $coupon = $coupon->first();
                if (isset($input['coupon'])) {
                    if ($input['coupon'] && trim($input['coupon']) != '' && trim($coupon->code_coupon) != '' && $coupon->status && trim($input['coupon']) == trim($coupon->code_coupon)) {
                        if ($coupon->percentage) {
                            $couponPrice = ($amount / Cart::count()) * $coupon->price / 100;
                            $couponPrice = ($amount / Cart::count()) - $couponPrice;
                            $amount = $couponPrice * $qty;
                        } else {
                            $amount = $coupon->price * $qty;
                        }

                        $couponCode = $input['coupon'];
                    }
                }
            }
            $namount = (float) $amount;

            $temp = [];

            $st_name = '';
            $st_tax_id = '';
            $st_line1 = '';
            $st_postal_code = '';
            $st_city = '';
            $st_email = '';
            $st_phone = '';
            $address = [];

            if (isset($pay_bill_data)) {
                $temp = $pay_bill_data;
                if (isset($temp['billing']) && $temp['billing'] == 1) {
                    $address = [];
                    $address['country'] = 'GR';

                    $temp['billing'] = 'Receipt requested';

                    $st_name = $temp['billname'];
                    $st_tax_id = 'EL' . $temp['billafm'];

                    if (isset($temp['billaddress'])) {
                        $st_line1 = $temp['billaddress'];

                        if (isset($temp['billaddressnum'])) {
                            $st_line1 .= ' ' . $temp['billaddressnum'];
                        }

                        $address['line1'] = $st_line1;
                    }

                    if (isset($temp['billcity'])) {
                        $st_city = $temp['billcity'];
                        $address['city'] = $st_city;
                    }

                    if (isset($temp['billpostcode'])) {
                        $st_postal_code = $temp['billpostcode'];
                        $address['postal_code'] = $st_postal_code;
                    }

                //$st_phone = $temp['billmobile'];
                } else {
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

            if (config('app.PAYMENT_PRODUCTION')) {
                Stripe::setApiKey($eventC->paymentMethod->first()->processor_options['secret_key']);
            } else {
                Stripe::setApiKey($eventC->paymentMethod->first()->test_processor_options['secret_key']);
            }
            session()->put('payment_method', $eventC->paymentMethod->first()->id);

            $dpuser->asStripeCustomer();

            if (!$dpuser->stripe_id) {
                $options = ['name' => $dpuser['firstname'] . ' ' . $dpuser['lastname'], 'email' => $dpuser['email']];
                $dpuser->createAsStripeCustomer($options);

                $stripe_ids = json_decode($dpuser->stripe_ids, true) ? json_decode($dpuser->stripe_ids, true) : [];
                $stripe_ids[] = $dpuser->stripe_id;

                $dpuser->stripe_ids = json_encode($stripe_ids);
                $dpuser->save();
            }
            $dpuser = updateStripeCustomer($dpuser, $st_name, $temp, $address);

            if ($installments > 1) {
                $instamount = round($namount / $installments, 2);

                /*if($instamount - floor($instamount)>0){
                    $planAmount = str_replace('.','',$instamount);
                }else{
                    $planAmount  = $instamount . '00';
                }*/
                $planAmount = $instamount * 100;
                //$dpuser->subscription()->syncWithStripe();
                // dd("Entity ready to be billed!");
                // Check if the entity has any active subscription

                //./ngrok authtoken 69hUuQ1DgonuoGjunLYJv_3PVuHFueuq5Kiuz7S1t21
                // Create the plan to subscribe
                $desc = $installments . ' installments';
                $planid = 'plan_' . $dpuser->id . '_E_' . $ev->id . '_T_' . $ticket_id . '_x' . $installments . '_' . date('his');
                $name = $ev_title . ' ' . $ev_date_help . ' | ' . $desc;
                //dd(str_replace('.','',$instamount) . '00');

                $plan = Plan::create([
                    'id'                   => $planid,
                    'product' => [
                        'name'                 => $name,
                    ],

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

                $payment_method_id = -1;
                if ($ev->paymentMethod->first()) {
                    $payment_method_id = $ev->paymentMethod->first()->id;
                }

                try {
                    $charge = $dpuser->newSubscription($name, $plan->id)->noProrate()->create(
                        $input['payment_method'],
                        ['email' => $dpuser->email],
                        ['metadata' => ['installments_paid' => 0, 'installments' => $installments]]
                    );

                    $charge->metadata = json_encode(['installments_paid' => 0, 'installments' => $installments]);
                    $charge->price = $instamount;
                    $charge->save();

                    // assign user to the event only if payment is complete.
                    $ev->users()->wherePivot('user_id', $dpuser->id)->detach();
                    $ev->users()->save($dpuser, ['paid'=>false, 'payment_method'=>$payment_method_id]);
                } catch(\Laravel\Cashier\Exceptions\IncompletePayment $exception) {
                    $payment_method_id = -1;
                    if ($ev->paymentMethod->first()) {
                        $payment_method_id = $ev->paymentMethod->first()->id;
                    }

                    $ev->users()->wherePivot('user_id', $dpuser->id)->detach();
                    $ev->users()->save($dpuser, ['paid'=>false, 'payment_method'=>$payment_method_id]);

                    $input['paymentMethod'] = $payment_method_id;
                    $input['amount'] = $instamount;
                    $input['total_amount'] = $namount;
                    $input['couponCode'] = $couponCode;
                    $input['duration'] = $ev_date_help;

                    $input = encrypt($input);
                    session()->put('input', $input);
                    session()->put('noActionEmail', true);

                    //return '/';
                    //return 'stripe/payment/' . $exception->payment->id . '/' . $input;
                    return 'summary/' . $exception->payment->id . '/' . $input;
                }

                //$namount = $instamount;
            }

            if ($dpuser && $installments > 1) {
                $charge['status'] = 'succeeded';
                $charge['type'] = $installments . ' Installments';
            } else {
                $stripeAmount = $namount * 100;

                $dpuser = updateStripeCustomer($dpuser, $st_name, $temp, $address);

                $temp['customer'] = $dpuser->email;
                $nevent = $ev_title . ' ' . $ev_date_help;

                try {
                    //$ev->users()->where('id',$dpuser->id)->detach();
                    //$ev->users()->save($dpuser,['paid'=>false,'payment_method'=>$payment_method_id]);

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
                        ],
                    );
                } catch (\Laravel\Cashier\Exceptions\IncompletePayment $exception) {
                    //dd('gfds3')

                    $payment_method_id = -1;
                    if ($ev->paymentMethod->first()) {
                        $payment_method_id = $ev->paymentMethod->first()->id;
                    }

                    $ev->users()->wherePivot('user_id', $dpuser->id)->detach();
                    $ev->users()->save($dpuser, ['paid'=>false, 'payment_method'=>$payment_method_id]);

                    $input['paymentMethod'] = $payment_method_id;
                    $input['amount'] = $namount;
                    $input['total_amount'] = $namount;
                    $input['couponCode'] = $couponCode;

                    $input = encrypt($input);

                    //return 'stripe/payment/' . $exception->payment->id . '/' . $input;
                    return 'summary/' . $exception->payment->id . '/' . $input;
                    //return '/stripe/payment/'.$exception->payment->id;
                }
            }

            if ((is_array($charge) && $charge['status'] == 'succeeded') || (isset($charge) && $charge->status == 'succeeded')) {
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
                        'email' => $dpuser->email,
                    ],
                    'pay_seats_data' => $pay_seats_data,
                    'pay_bill_data' => $pay_bill_data,
                    'deree_user_data' => [$dpuser->email => ''],
                    //'cardtype' => $payment_cardtype,
                    'installments' => $installments,
                    'cart_data' => $cart,

                ];
                $transaction_arr = [

                    'payment_method_id' => 100, //$input['payment_method_id'],
                    'account_id' => 17,
                    'payment_status' => 2,
                    'billing_details' => $bd,
                    'status_history' => json_encode($status_history),
                    'placement_date' => Carbon::now()->toDateTimeString(),
                    'ip_address' => \Request::ip(),
                    'status' => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
                    'is_bonus' => 0,
                    'order_vat' => 0,
                    'payment_response' => json_encode($charge),
                    'surcharge_amount' => 0,
                    'discount_amount' => 0,
                    'coupon_code' => $couponCode,
                    'amount' => $namount,
                    'total_amount' => $namount,
                    'trial' => false,
                ];

                $transaction = Transaction::create($transaction_arr);

                if ($transaction) {
                    //$transaction->user()->save($dpuser);
                    $transaction->event()->save($ev);

                    if ($installments <= 1) {
                        /*if(!Invoice::latest()->doesntHave('subscription')->first()){
                        //if(!Invoice::has('event')->latest()->first()){
                            $invoiceNumber = sprintf('%04u', 1);
                        }else{
                            //$invoiceNumber = Invoice::has('event')->latest()->first()->invoice;
                            $invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
                            $invoiceNumber = (int) $invoiceNumber + 1;
                            $invoiceNumber = sprintf('%04u', $invoiceNumber);
                        }*/

                        $elearningInvoice = new Invoice;
                        $elearningInvoice->name = json_decode($transaction->billing_details, true)['billname'];
                        $elearningInvoice->amount = round($namount / $installments, 2);
                        $elearningInvoice->invoice = generate_invoice_number($eventC->paymentMethod->first()->id);
                        $elearningInvoice->date = date('Y-m-d'); //Carbon::today()->toDateString();
                        $elearningInvoice->instalments_remaining = $installments;
                        $elearningInvoice->instalments = $installments;

                        $elearningInvoice->save();

                        //$elearningInvoice->user()->save($dpuser);
                        $elearningInvoice->event()->save($ev);
                        $elearningInvoice->transaction()->save($transaction);
                    } else {
                        //$transaction->subscription()->save($dpuser->subscriptions->where('id',$charge['id'])->first());
                    }

                    \Session::put('transaction_id', $transaction->id);
                }

                return '/order-success';
            //return '/info/order_success';
            } else {
                //dd('edwww1');
                \Session::put('dperror', 'Cannot complete the payment!!');

                //return redirect('/info/order_error');
                return '/checkout';
            }
        } catch (Exception $e) {
            //dd('edwww2');
            \Session::put('dperror', $e->getMessage());

            return '/checkout';
            // return redirect('/info/order_error');
        } catch(\Stripe\Exception\CardErrorException $e) {
            //dd('edwww3');
            \Session::put('dperror', $e->getMessage());

            return '/checkout';
            //return redirect('/info/order_error');
        } catch(\Stripe\Exception\InvalidRequestException $e) {
            //dd($e);
            \Session::put('dperror', $e->getMessage());

            //return redirect('/info/order_error');
            return '/checkout';
        } catch(\Stripe\Exception\MissingParameterException $e) {
            //dd($e);
            \Session::put('dperror', $e->getMessage());

            //return redirect('/info/order_error');
            return '/checkout';
        } catch(\Stripe\Api\Exception\ServerErrorException $e) {
            //dd($e);
            \Session::put('dperror', $e->getMessage());

            //return redirect('/info/order_error');
            return '/checkout';
        } catch(\Stripe\Exception\CardException $e) {
            //dd($e);
            \Session::put('dperror', $e->getMessage());

            //return redirect('/info/order_error');
            return '/checkout';
        } catch(\Laravel\Cashier\Exceptions\IncompletePayment $e) {
            //dd($e);
            \Session::put('dperror', $e->getMessage());

            //return redirect('/info/order_error');
            return '/checkout';
        }
    }

    private function createTransaction($dpuser, $pay_seats_data, $installments, $cart, $bd, $ev, $couponCode, $namount, $pay_bill_data, $charge, $eventC, $status, $sepa = false)
    {
        //$dpuser->updateDefaultPaymentMethod($input['payment_method']);
        // if ($dpuser->hasPaymentMethod()) {
        //     $dpuser->addPaymentMethod($input['payment_method']);
        // }

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
                'email' => $dpuser->email,
            ],
            'pay_seats_data' => $pay_seats_data,
            'pay_bill_data' => $pay_bill_data,
            'deree_user_data' => [$dpuser->email => ''],
            //'cardtype' => $payment_cardtype,
            'installments' => $installments,
            'cart_data' => $cart,

        ];
        $transaction_arr = [

            'payment_method_id' => 100, //$input['payment_method_id'],
            'account_id' => 17,
            'payment_status' => 2,
            'billing_details' => $bd,
            'status_history' => json_encode($status_history),
            'placement_date' => Carbon::now()->toDateTimeString(),
            'ip_address' => \Request::ip(),
            'status' => $status, //2 PENDING, 0 FAILED, 1 COMPLETED
            'is_bonus' => 0,
            'order_vat' => 0,
            'payment_response' => json_encode($charge),
            'surcharge_amount' => 0,
            'discount_amount' => 0,
            'coupon_code' => $couponCode,
            'amount' => $namount,
            'total_amount' => $namount,
            'trial' => false,
        ];

        $transaction = Transaction::create($transaction_arr);

        if ($transaction) {
            //$transaction->user()->save($dpuser);
            $transaction->event()->save($ev);

            //if($installments <= 1){
            /*if(!Invoice::latest()->doesntHave('subscription')->first()){
            //if(!Invoice::has('event')->latest()->first()){
                $invoiceNumber = sprintf('%04u', 1);
            }else{
                //$invoiceNumber = Invoice::has('event')->latest()->first()->invoice;
                $invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
                $invoiceNumber = (int) $invoiceNumber + 1;
                $invoiceNumber = sprintf('%04u', $invoiceNumber);
            }*/

            if (!$sepa) {
                $elearningInvoice = new Invoice;
                $elearningInvoice->name = json_decode($transaction->billing_details, true)['billname'];
                $elearningInvoice->amount = round($namount / $installments, 2);
                $elearningInvoice->invoice = generate_invoice_number($eventC->paymentMethod->first()->id);
                $elearningInvoice->date = date('Y-m-d'); //Carbon::today()->toDateString();
                $elearningInvoice->instalments_remaining = $installments;
                $elearningInvoice->instalments = $installments;

                $elearningInvoice->save();

                //$elearningInvoice->user()->save($dpuser);
                $elearningInvoice->event()->save($ev);
                $elearningInvoice->transaction()->save($transaction);
            }

            //}else{
            //$transaction->subscription()->save($dpuser->subscriptions->where('id',$charge['id'])->first());
            //}

            \Session::put('transaction_id', $transaction->id);
        }
    }

    public function alphaBankPayment($input, $request)
    {
        $payment_method_id = intval($input['payment_method_id']);
        $payment_cardtype = intval($input['cardtype']);

        $amount = Cart::total();
        $namount = (float) $amount;

        function greeklish($Name)
        {
            $greek = ['α', 'ά', 'Ά', 'Α', 'β', 'Β', 'γ', 'Γ', 'δ', 'Δ', 'ε', 'έ', 'Ε', 'Έ', 'ζ', 'Ζ', 'η', 'ή', 'Η', 'θ', 'Θ', 'ι', 'ί', 'ϊ', 'ΐ', 'Ι', 'Ί', 'κ', 'Κ', 'λ', 'Λ', 'μ', 'Μ', 'ν', 'Ν', 'ξ', 'Ξ', 'ο', 'ό', 'Ο', 'Ό', 'π', 'Π', 'ρ', 'Ρ', 'σ', 'ς', 'Σ', 'τ', 'Τ', 'υ', 'ύ', 'Υ', 'Ύ', 'φ', 'Φ', 'χ', 'Χ', 'ψ', 'Ψ', 'ω', 'ώ', 'Ω', 'Ώ', ' ', "'", "'", ','];
            $english = ['a', 'a', 'A', 'A', 'b', 'B', 'g', 'G', 'd', 'D', 'e', 'e', 'E', 'E', 'z', 'Z', 'i', 'i', 'I', 'th', 'Th', 'i', 'i', 'i', 'i', 'I', 'I', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'x', 'X', 'o', 'o', 'O', 'O', 'p', 'P', 'r', 'R', 's', 's', 'S', 't', 'T', 'u', 'u', 'Y', 'Y', 'f', 'F', 'ch', 'Ch', 'ps', 'Ps', 'o', 'o', 'O', 'O', '_', '_', '_', '_'];
            $string = str_replace($greek, $english, $Name);

            return $string;
        }
        $bd = [];
        $bd['billaddress'] = greeklish($input['billaddress']) . ' ' . $input['billaddressnum'];
        $bd['billzip'] = $input['billpostcode'];
        $bd['city'] = greeklish($input['billcity']);
        $bd['billcountry'] = 'GR';

        if (Auth::check()) {
            $cuser = Auth::user();
            $uid = $cuser->id;
        } else {
            $uid = 0;
        }

        $transaction_arr = [

            'payment_method_id' => $payment_method_id,
            'account_id' => 17,
            'payment_status' => 2,
            'billing_details' => json_encode($bd), //serialize($billing_details),
            'placement_date' => Carbon::now()->toDateTimeString(),
            'ip_address' => $request->ip(),
            'type' => $payment_cardtype, //((Sentinel::inRole('super_admin') || Sentinel::inRole('know-crunch')) ? 1 : 0),
            'status' => 2, //2 PENDING, 0 FAILED, 1 COMPLETED
            'is_bonus' => 0, //$input['is_bonus'],
            'order_vat' => 0, //$input['credit'] - ($input['credit'] / (1 + Config::get('dpoptions.order_vat.value') / 100)),
            'surcharge_amount' => 0,
            'discount_amount' => 0,
            'amount' => $namount,
            'total_amount' => $namount,
            'trial' => false,
        ]; //$input['credit']

        $transaction = Transaction::create($transaction_arr);
        if ($transaction) {
            $request->session()->put('transaction_id', $transaction->id);

            return redirect('/payment-dispatch/checkout/' . $transaction->id);
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
        if ($user = Auth::user()) {
            // dd($user->cart);
            $existingcheck = ShoppingCart::where('identifier', $user->id)->first();

            if ($existingcheck) {
                $existingcheck->delete($user->id);
            }

            if ($user->cart) {
                $user->cart->delete();
            }
        }

        $isAjax = request()->ajax();

        if ($isAjax) {
            return response(['message' => 'success', 'id' => $id]);
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

        return Redirect::to('/registration');

        /*return redirect()->route('cart')->with('success',
            "{$product->name} was successfully removed from the shopping cart."
        );*/
    }

    public function checkCoupon(Request $request, $event)
    {
        if (Session::get('coupon_code')) {
            return response()->json([
                'success' => 'used',
                'message' => 'Your coupon has been declined. Please try again.',
            ]);
        }

        //$coupon = Coupon::where('code_coupon',$request->coupon)->where('status',true)->get();
        //dd($request->all());
        $event = Event::find($event);
        $coupon = $event->coupons()->where('status', true)->get();
        if (count($coupon) > 1) {
            foreach ($coupon as $key => $c) {
                if ($c->code_coupon === $request->coupon) {
                    //$coupon = $c->get();
                } else {
                    unset($coupon[$key]);
                }
            }
        }

        if (count($coupon) > 0) {
            $coupon = $coupon->first();

            if (trim($request->coupon) === trim($coupon->code_coupon) && $coupon->status && trim($request->coupon) != '') {
                if ($coupon->percentage) {
                    $price = $request->price * $coupon->price / 100;
                    $newPrice = $request->price - $price;
                    $priceOf = $coupon->price . '%';
                } else {
                    $newPrice = $coupon->price;
                    $priceOf = 100 - ($coupon->price / $request->price) * 100;
                    $priceOf = round($priceOf, 2) . '%';
                }

                $savedPrice = $request->price - $newPrice;

                Session::put('coupon_code', $request->coupon);
                Session::put('coupon_price', $newPrice);
                Session::put('priceOf', $priceOf);

                $instOne = $newPrice * $request->totalItems;
                $instTwo = round($newPrice / 2, 2) * $request->totalItems;
                $instThree = round($newPrice / 3, 2) * $request->totalItems;
                $instFour = round($newPrice / 4, 2) * $request->totalItems;

                if ($instOne - floor($instOne) > 0) {
                    $instOne = number_format($instOne, 2, '.', ',');
                } else {
                    $instOne = number_format($instOne, 0, '.', ',');
                }

                if ($instTwo - floor($instTwo) > 0) {
                    $instTwo = number_format($instTwo, 2, '.', ',');
                } else {
                    $instTwo = number_format($instTwo, 0, '.', ',');
                }

                if ($instThree - floor($instThree) > 0) {
                    $instThree = number_format($instThree, 2, '.', ',');
                } else {
                    $instThree = number_format($instThree, 0, '.', ',');
                }

                if ($instFour - floor($instFour) > 0) {
                    $instFour = number_format($instFour, 2, '.', ',');
                } else {
                    $instFour = number_format($instFour, 0, '.', ',');
                }

                //dd($instOne);

                return response()->json([
                    'success' => true,
                    'new_price' => $instOne,
                    'savedPrice' => round($savedPrice, 2) * $request->totalItems,
                    'priceOf' => $priceOf,
                    'newPriceInt2' => $instTwo,
                    'newPriceInt3' => $instThree,
                    'newPriceInt4' => $instFour,
                    'message' => 'Success! Your coupon has been accepted.',
                    'coupon_code' => $request->coupon,

                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Your coupon has been declined. Please try again.',
        ]);
    }

    public function checkCode(Request $request)
    {
        $event = Event::find($request->event);

        $code = $event->coupons()->where('code_coupon', $request->eventCode)->first();

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'The code you have entered is incorrect. Please try again.',
            ]);
        } elseif ($code->used == 1) {
            return response()->json([
                'success' => false,
                'message' => 'The code you have entered is already taken. Please try another code.',
            ]);
        } else {
            Cart::instance('default')->destroy();
            $item = Cart::add('free_code', $event->title, 1, (float) 0, ['type' => 'free_code', 'event' => $event->id, 'code_id' => $code->id])->associate(Ticket::class);
            //$code->used = true;
            $code->save();

            if ($user = Auth::user()) {
                if ($user->cart) {
                    $user->cart->delete();
                }

                $cartCache = new CartCache;

                $cartCache->ticket_id = 0; //'coupon code ' . $event->id;
                $cartCache->product_title = $event->title;
                $cartCache->quantity = 1;
                $cartCache->price = (float) 0;
                $cartCache->type = 9;
                $cartCache->event = $event->id;
                $cartCache->user_id = Auth::user()->id;
                $cartCache->slug = base64_encode('coupon code ' . $event->id . Auth::user()->id . $event->id);

                $cartCache->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'To event προστεθηκε στο καλάθι',
                'redirect' => '/registration',
            ]);
        }
    }

    public function createSepa(Request $request)
    {
        Log::info('createSepa');
        $input = $request->all();
        $data = [];

        if (isset($input['installments'])) {
            Session::put('installments', $input['installments']);
        } else {
            Session::put('installments', 1);
        }

        if (Session::get('coupon_code')) {
            $input['coupon'] = Session::get('coupon_code');
        }

        $data = $this->initCartDetails($data);
        $this->fbp->sendAddPaymentInfoEvent($data);

        Log::info(json_encode($data));
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
            //$ev_date_help = $ev->summary1->where('section','date')->first() ? $ev->summary1->where('section','date')->first()->title : 'date';
            $eventInfo = $ev->event_info();
            if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
                $ev_date_help = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
                                    $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                                                $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
            } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
                $ev_date_help = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
                                        $eventInfo['inclass']['dates']['visible']['emails'] ? $eventInfo['inclass']['dates']['text'] : '';
            }

            $ev_title = $ev->title;
            $ticket_id = $item->id;
            break;
            //$item->id  <-ticket id
        }
        Log::info(json_encode($cart));
        //dd($cart['CartItem']->CartItemOptions);
        //dd($ev_title . $ev_date_help);
        $data = [];
        if (Session::has('pay_seats_data')) {
            $pay_seats_data = Session::get('pay_seats_data');
        } else {
            $pay_seats_data = [];
        }

        if (Session::has('pay_bill_data')) {
            $pay_bill_data = Session::get('pay_bill_data');
            $bd = json_encode($pay_bill_data);
        } else {
            $bd = '';
            $pay_bill_data = [];
        }

        if (Session::has('installments')) {
            $installments = Session::get('installments');
        } else {
            $installments = 0;
        }

        $input = Arr::except($input, ['_token']);

        try {
            $amount = Cart::total();
            $coupon = [];
            $eventC = Event::find($eventId);
            if ($eventC && isset($input['coupon'])) {
                $coupon = $eventC->coupons()->where('status', true)->get();
            }

            if (isset($input['coupon']) && count($coupon) > 0) {
                foreach ($coupon as $key => $c) {
                    if (!($c->code_coupon === $input['coupon'])) {
                        unset($coupon[$key]);
                    }
                }
            }

            $couponCode = '';
            if (count($coupon) > 0) {
                $coupon = $coupon->first();
                if (isset($input['coupon'])) {
                    if ($input['coupon'] && trim($input['coupon']) != '' && trim($coupon->code_coupon) != '' && $coupon->status && trim($input['coupon']) == trim($coupon->code_coupon)) {
                        if ($coupon->percentage) {
                            $couponPrice = ($amount / Cart::count()) * $coupon->price / 100;
                            $couponPrice = ($amount / Cart::count()) - $couponPrice;
                            $amount = $couponPrice * $qty;
                        } else {
                            $amount = $coupon->price * $qty;
                        }

                        $couponCode = $input['coupon'];
                    }
                }
            }
            $namount = (float) $amount;

            $temp = [];
            if (isset($pay_bill_data)) {
                $temp = $pay_bill_data;
                if ($temp['billing'] == 1) {
                    $address = [];
                    $address['country'] = 'GR';

                    $temp['billing'] = 'Receipt requested';

                    $st_name = $temp['billname'];
                    $st_tax_id = 'EL' . $temp['billafm'];

                    if (isset($temp['billaddress'])) {
                        $st_line1 = $temp['billaddress'];

                        if (isset($temp['billaddressnum'])) {
                            $st_line1 .= ' ' . $temp['billaddressnum'];
                        }

                        $address['line1'] = $st_line1;
                    }

                    if (isset($temp['billcity'])) {
                        $st_city = $temp['billcity'];
                        $address['city'] = $st_city;
                    }

                    if (isset($temp['billpostcode'])) {
                        $st_postal_code = $temp['billpostcode'];
                        $address['postal_code'] = $st_postal_code;
                    }

                //$st_phone = $temp['billmobile'];
                } else {
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

            if (config('app.PAYMENT_PRODUCTION')) {
                Stripe::setApiKey($eventC->paymentMethod->first()->processor_options['secret_key']);
            } else {
                Stripe::setApiKey($eventC->paymentMethod->first()->test_processor_options['secret_key']);
            }
            session()->put('payment_method', $eventC->paymentMethod->first()->id);

            $dpuser->asStripeCustomer();
            if (!$dpuser->stripe_id) {
                $options = ['name' => $dpuser['firstname'] . ' ' . $dpuser['lastname'], 'email' => $dpuser['email']];
                $dpuser->createAsStripeCustomer($options);

                $stripe_ids = json_decode($dpuser->stripe_ids, true) ? json_decode($dpuser->stripe_ids, true) : [];
                $stripe_ids[] = $dpuser->stripe_id;

                $dpuser->stripe_ids = json_encode($stripe_ids);
                $dpuser->save();
            }

            $dpuser = updateStripeCustomer($dpuser, $st_name, $temp, $address);

            Log::info(json_encode($dpuser));
            if ($installments > 1) {
                Log::info(json_encode($installments));

                $instamount = round($namount / $installments, 2);

                /*if($instamount - floor($instamount)>0){
                    $planAmount = str_replace('.','',$instamount);
                }else{
                    $planAmount  = $instamount . '00';
                }*/
                $planAmount = $instamount * 100;
                //$dpuser->subscription()->syncWithStripe();
                // dd("Entity ready to be billed!");
                // Check if the entity has any active subscription

                //./ngrok authtoken 69hUuQ1DgonuoGjunLYJv_3PVuHFueuq5Kiuz7S1t21
                // Create the plan to subscribe
                $desc = $installments . ' installments';
                $planid = 'plan_' . $dpuser->id . '_E_' . $ev->id . '_T_' . $ticket_id . '_x' . $installments . '_' . date('his');
                $name = $ev_title . ' ' . $ev_date_help . ' | ' . $desc;
                //dd(str_replace('.','',$instamount) . '00');

                $plan = Plan::create([
                    'id'                   => $planid,
                    'product' => [
                        'name'                 => $name,
                    ],

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

                $payment_method_id = -1;
                if ($ev->paymentMethod->first()) {
                    $payment_method_id = $ev->paymentMethod->first()->id;
                }

                //dd($client_secret);

                try {
                    //header('Content-Type: application/json');

                    //Create a PaymentIntent with amount and currency
                    // $paymentIntent = \Stripe\PaymentIntent::create([
                    //     'description' => 'Subscription creation',
                    //     'amount' => $planAmount,
                    //     'currency' => 'eur',
                    //     'customer' => $dpuser->stripe_id,
                    //     'payment_method_types' => ['sepa_debit'],
                    //     'metadata' => ['integration_check' => 'sepa_debit_accept_a_payment'],
                    //     'confirm' => false
                    // ]);

                    // $paymentIntent = \Stripe\SetupIntent::create([
                    //     'payment_method_types' => ['sepa_debit'],
                    //     'customer' => $dpuser->stripe_id,
                    //   ]);

                    Log::info('try');

                    $ev->users()->wherePivot('user_id', $dpuser->id)->detach();
                    $ev->users()->save($dpuser, ['paid'=>false, 'payment_method'=>$payment_method_id]);

                    $dpuser->newSubscription($name, $plan->id)->noProrate()->create(
                        $input['payment_method'],
                        ['email' => $dpuser->email],
                        [
                            'metadata' => [
                                'installments_paid' => 0,
                                'installments' => $installments,
                                'payment_method' => 'sepa',
                            ],
                        ]
                    );
                    //after new subscription payment is incomplete because pay with SEPA

                    //dd($charge);

                    // $charge->metadata = json_encode(['installments_paid' => 0, 'installments' => $installments]);
                    // $charge->price = $instamount;
                    // $charge->save();
                } catch(\Laravel\Cashier\Exceptions\IncompletePayment $exception) {
                    Bugsnag::notifyException($exception);

                    $sub = $dpuser->subscriptions()->where('user_id', $dpuser->id)->first();

                    // TODO
                    // (1) update subscription price
                    $dpuser->subscriptions()->find($sub->id)->update([
                        'price' => $instamount,
                    ]);

                    Session::put('payment_method_is_sepa', true);

                    $this->createTransaction($dpuser, $pay_seats_data, $installments, $cart, $bd, $ev, $couponCode, $namount, $pay_bill_data, $exception->payment, $eventC, $status = 2, true);

                    //after new subscription payment is incomplete because pay with SEPA
                    $output = [
                        'clientSecret' => $exception->payment->client_secret,
                        'return_url' => '/order-success',
                        'status' => '',
                    ];

                    return json_encode($output);
                }

                //$namount = $instamount;
            }

            Log::info('here');
            if ($dpuser && $installments > 1) {
                $charge['status'] = 'succeeded';
                $charge['type'] = $installments . ' Installments';
                Log::info('succeeded');
            } else {
                $stripeAmount = $namount * 100;

                $dpuser = updateStripeCustomer($dpuser, $st_name, $temp, $address);

                $temp['customer'] = $dpuser->email;
                $nevent = $ev_title . ' ' . $ev_date_help;

                try {
                    Log::info('try2');

                    header('Content-Type: application/json');

                    // Create a PaymentIntent with amount and currency
                    $paymentIntent = \Stripe\PaymentIntent::create([
                        'amount' => $stripeAmount,
                        'currency' => 'eur',
                        'description' => $nevent,
                        'customer' => $dpuser->stripe_id,
                        'payment_method_types' => ['sepa_debit'],
                        'metadata' => ['integration_check' => 'sepa_debit_accept_a_payment'],
                        'statement_descriptor' => 'KNOWCRUNCH INC',
                    ]);

                    Log::info(json_encode($paymentIntent));

                    $payment_method_id = -1;
                    if ($ev->paymentMethod->first()) {
                        $payment_method_id = $ev->paymentMethod->first()->id;
                    }

                    Log::info('json_encode($payment_method_id)');
                    Log::info(json_encode($payment_method_id));
                    $ev->users()->wherePivot('user_id', $dpuser->id)->detach();
                    $ev->users()->save($dpuser, ['paid'=>false, 'payment_method'=>$payment_method_id]);

                    if ((is_array($paymentIntent) && $paymentIntent['status'] == 'requires_payment_method') || (isset($paymentIntent) && $paymentIntent->status == 'requires_payment_method') || (is_array($paymentIntent) && $paymentIntent['status'] == 'requires_source') || (isset($paymentIntent) && $paymentIntent->status == 'requires_source')) {
                        Log::info('paymentIntent');
                        Log::info(json_encode($paymentIntent));
                        $status = 2;
                        $this->createTransaction($dpuser, $pay_seats_data, $installments, $cart, $bd, $ev, $couponCode, $namount, $pay_bill_data, $paymentIntent, $eventC, $status);

                        Session::put('payment_method_is_sepa', true);

                        Log::info('createTransaction');
                        Log::info(json_encode($paymentIntent));
                        $output = [
                            'clientSecret' => $paymentIntent->client_secret,
                            'status' => $paymentIntent->status,
                            'return_url' => '/order-success',
                        ];

                        return json_encode($output);
                    }
                } catch (\Laravel\Cashier\Exceptions\IncompletePayment $exception) {
                    Bugsnag::notifyException($exception);
                }
            }

            /*
            if( (isset($charge) && $charge['status'] == 'succeeded')) {


                $status = 2;
                $this->createTransaction($dpuser, $pay_seats_data, $installments, $cart, $bd, $ev,$couponCode,$namount, $pay_bill_data, $charge,$eventC,$status);

                Session::put('payment_method_is_sepa',true);

                dd($charge);

                $output = [
                    'clientSecret' => $charge->client_secret,
                    'return_url' => '/order-success',
                    'status' => ''
                ];

                echo json_encode($output);

            } else {
                //dd('edwww1');
                 \Session::put('dperror','Cannot complete the payment!!');
                //return redirect('/info/order_error');
                  return '/checkout';
            }
            */

            //endddddddddd

            Log::info('end');
        } catch(Error $e) {
            Bugsnag::notifyException($e);
        }
    }

    public function completeRegistration(Request $request)
    {
        $data = [];
        $option = Option::where('abbr', 'deree-codes')->first();
        //$dereelist = json_decode($option->settings, true);
        $code = 0;

        //dd($dereelist);

        $c = Cart::content()->count();

        if ((!$user = User::where('email', $request->email[0])->first())) {
            $input = [];
            $formData = $request->all();
            unset($formData['_token']);
            unset($formData['terms_condition']);
            unset($formData['update']);
            unset($formData['type']);

            foreach ($formData as $key => $value) {
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
            $consent['ip'] = $clientip;
            $consent['date'] = $connow;
            $consent['firstname'] = $user->firstname;
            $consent['lastname'] = $user->lastname;
            if ($user->afm) {
                $consent['afm'] = $user->afm;
            }

            $billing = json_decode($user->receipt_details, true);

            if (isset($billing['billafm']) && $billing['billafm']) {
                $consent['billafm'] = $billing['billafm'];
            }

            $user->consent = json_encode($consent);
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

        $payment_method_id = 1; //intval($input["payment_method_id"]);
        $payment_cardtype = 9; //free;
        $amount = 0;
        $namount = (float) $amount;

        $code = $content->coupons->where('id', $codeId)->first();

        if ($code) {
            $code = $code->code_coupon;
        }

        $transaction_arr = [

            'payment_method_id' => $payment_method_id,
            'account_id' => 17,
            'payment_status' => 1,
            'billing_details' => json_encode([]),
            'placement_date' => Carbon::now()->toDateTimeString(),
            'ip_address' => '127.0.0.1',
            'type' => $payment_cardtype, //((Sentinel::inRole('super_admin') || Sentinel::inRole('know-crunch')) ? 1 : 0),
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

            $pay_seats_data = ['names' => [$request->firstname[0]], 'surnames' => [$request->lastname[0]], 'emails' => [$request->email[0]],
                'mobiles' => [$request->mobile[0]], 'addresses' => [$user->address], 'addressnums' => [$user->address_num],
                'postcodes' => [$user->postcode], 'cities' => [$user->city], 'jobtitles' => [$user->job_title],
                'companies' => [$user->company], 'students' => [''], 'afms' => [$user->afm]];

            $deree_user_data = [$user->email => $user->partner_id];

            //dd($ticket->event->title);
            $cart_data = ['manualtransaction' => ['rowId' => 'manualtransaction', 'id' => 'coupon code ' . $content->id, 'name' => $content->title, 'qty' => '1', 'price' => $amount, 'options' => ['type' => '9', 'event'=> $content->id], 'tax' => 0, 'subtotal' => $amount]];

            $status_history[] = [
                'datetime' => Carbon::now()->toDateTimeString(),
                'status' => 1,
                'user' => [
                    'id' => $user->id, //0, $this->current_user->id,
                    'email' => $user->email, //$this->current_user->email
                ],
                'pay_seats_data' => $pay_seats_data, //$data['pay_seats_data'],
                'pay_bill_data' => [],
                'cardtype' => 9,
                'installments' => 1,
                'deree_user_data' => $deree_user_data, //$data['deree_user_data'],
                'cart_data' => $cart_data, //$cart
            ];

            //Transaction::where('id', $transaction['id'])
            $transaction->update(['status_history' => json_encode($status_history)/*, 'billing_details' => $tbd*/]);

            $user->events()->attach($content->id, ['comment' => 'upon coupon', 'paid' => true]);

            if ($user->cart) {
                $user->cart->delete();
            }
            Cart::instance('default')->destroy();
            $content->coupons->where('id', $codeId)->first()->update(['used' => true]);

            $data['event']['title'] = $content->title;
            $data['event']['slug'] = $content->slugable->slug;
            $data['event']['facebook'] = url('/') . '/' . $content->slugable->slug . '?utm_source=Facebook&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&quote=' . urlencode('Proudly participating in ' . $content->title . ' by Knowcrunch.');
            $data['event']['twitter'] = urlencode('Proudly participating in ' . $content->title . ' ' . url('/') . '/' . $content->slugable->slug . ' by Knowcrunch. 💙');
            $data['event']['linkedin'] = urlencode(url('/') . '/' . $content->slugable->slug . '?utm_source=LinkedIn&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&title=' . 'Proudly participating in ' . $content->title . ' by Knowcrunch. 💙');

            $categoryScript = $content->delivery->first() && $content->delivery->first()->id == 143 ? 'Video e-learning courses' : 'In-class courses'; //'Event > ' . $content->category->first()->name;

            $KC = 'KC-';
            $time = strtotime($transaction->placement_date);
            $MM = date('m', $time);
            $YY = date('y', $time);

            $option = Option::where('abbr', 'website_details')->first();
            //next number available up to 9999
            $next = $option->value;

            if ($user->kc_id == '') {
                $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);
                $knowcrunch_id = $KC . $YY . $MM . $next_kc_id;
                $user->kc_id = $knowcrunch_id;

                $user->save();

                if ($next == 9999) {
                    $next = 1;
                } else {
                    $next += 1;
                }
                $option->value = $next;
                $option->save();
            }
            $this->sendEmails($transaction, $content, $user);

            $data['info']['success'] = true;
            $data['info']['title'] = __('thank_you_page.title');
            $data['info']['message'] = __('thank_you_page.message');
            $data['info']['transaction'] = $transaction;
            $data['info']['statusClass'] = 'success';

            $data['tigran'] = ['OrderSuccess_id' => $transaction['id'], 'OrderSuccess_total' => 0.00, 'Price' => 0.00, 'Product_id' => $content->id, 'Product_SKU' => $content->id,
                'ProductCategory' => $categoryScript, 'ProductName' =>  $content->title, 'Quantity' => $item->qty, 'TicketType'=>'Upon Coupon', 'Event_ID' => 'kc_' . time(),
                'Encrypted_email' => hash('sha256', $user->email),
            ];

            $customerBillingFirstName = '';
            $customerBillingLastName = '';
            $customerBillingCompany = '';
            $customerBillingAddress1 = '';
            $customerBillingAddress2 = '';
            $customerBillingCity = '';
            $customerBillingPostcode = '';
            $customerBillingEmail = '';
            try {
                $receipt_details = json_decode($user->receipt_details);
                if (isset($receipt_details->billname)) {
                    $customerBillingFirstName = $receipt_details->billname;
                }
                if (isset($receipt_details->billsurname)) {
                    $customerBillingLastName = $receipt_details->billsurname;
                }
                if (isset($receipt_details->billemail)) {
                    $customerBillingEmail = $receipt_details->billemail;
                }
                if (isset($receipt_details->billaddress)) {
                    $customerBillingAddress1 = $receipt_details->billaddress;
                }
                if (isset($receipt_details->billaddressnum)) {
                    $customerBillingAddress2 = $receipt_details->billaddressnum;
                }
                if (isset($receipt_details->billpostcode)) {
                    $customerBillingPostcode = $receipt_details->billpostcode;
                }
                if (isset($receipt_details->billcity)) {
                    $customerBillingCity = $receipt_details->billcity;
                }
                if (isset($receipt_details->billemail)) {
                    $customerBillingEmail = $receipt_details->billemail;
                }
            } catch(\Exception $e) {
            }

            $data['customer'] = [
                'customerTotalOrders' => '',
                'customerTotalOrderValue' => '',
                'customerFirstName' => $user->firstname,
                'customerLastName' => $user->lastname,
                'customerMobile' => $user->mobile,
                'customerStreet' => $user->address,
                'customerBillingFirstName' => $customerBillingFirstName,
                'customerBillingLastName' => $customerBillingLastName,
                'customerBillingCompany' => $user->company,
                'customerBillingAddress1' => $customerBillingAddress1,
                'customerBillingAddress2' => $customerBillingAddress2,
                'customerBillingCity' => $customerBillingCity,
                'customerBillingPostcode' => $customerBillingPostcode,
                'customerBillingCountry' => '',
                'customerBillingEmail' => $customerBillingEmail,
                'customerBillingEmailHash' => hash('sha256', $customerBillingEmail),
            ];
        }
        //$this->fbp->sendPurchaseEvent($data);

        Session::put('thankyouData', $data);
        try {
            session_start();
            $_SESSION['thankyouData'] = $data;
        } catch(\Exception $ex) {
            Bugsnag::notifyException($ex);
        }

        return redirect('/thankyou');

        //return view('theme.cart.new_cart.thank_you',$data);
    }

    public function sendEmails($transaction, $content, $user)
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
        $data['eventSlug'] = url('/') . '/' . $content->getSlug();
        $data['duration'] = ''; //$content->summary1->where('section','date')->first() ? $content->summary1->where('section','date')->first()->title : '';

        $eventInfo = $content ? $content->event_info() : [];

        if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
            $data['duration'] = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
                                $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                                            $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
        } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
            $data['duration'] = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
                                        $eventInfo['inclass']['dates']['visible']['emails'] ? $eventInfo['inclass']['dates']['text'] : '';
        }

        $data['hours'] = isset($eventInfo['hours']['visible']['emails']) && $eventInfo['hours']['visible']['emails'] && isset($eventInfo['hours']['hour']) &&
                        isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

        $data['language'] = isset($eventInfo['language']['visible']['emails']) && $eventInfo['language']['visible']['emails'] && isset($eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

        $data['certificate_type'] = isset($eventInfo['certificate']['visible']['emails']) && $eventInfo['certificate']['visible']['emails'] &&
                    isset($eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

        $eventStudents = get_sum_students_course($content->category->first());
        $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] : $eventStudents + 1;

        $data['students'] = isset($eventInfo['students']['visible']['emails']) && $eventInfo['students']['visible']['emails'] &&
                        isset($eventInfo['students']['text']) && $data['students_number'] >= $eventStudents ? $eventInfo['students']['text'] : '';

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

        if (!$user->statusAccount->completed) {
            $data['user']['createAccount'] = true;
            $cookieValue = base64_encode($user->id . date('H:i'));
            setcookie('auth-' . $user->id, $cookieValue, time() + (1 * 365 * 86400), '/'); // 86400 = 1 day

            $coockie = new CookiesSMS;
            $coockie->coockie_name = 'auth-' . $user->id;
            $coockie->coockie_value = $cookieValue;
            $coockie->user_id = $user->id;
            $coockie->sms_code = -1;
            $coockie->sms_verification = true;

            $coockie->save();

            $user->statusAccount->completed = true;
            $user->statusAccount->save();
        }

        $user->notify(new WelcomeEmail($user, $data));
        event(new EmailSent($user->email, 'WelcomeEmail'));
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
        if ($user = Auth::user()) {
            $existingcheck = ShoppingCart::where('identifier', $user->id)->first();

            if ($user->cart) {
                $user->cart->delete();
            }

            //Cart::restore($user->id
            if ($existingcheck) {
                $existingcheck->delete($user->id);
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            } else {
                Cart::store($user->id);
                $timecheck = ShoppingCart::where('identifier', $user->id)->first();
                $timecheck->created_at = Carbon::now();
                $timecheck->updated_at = Carbon::now();
                $timecheck->save();
            }
        }

        $data = $request->only([
            'firstname', 'lastname', 'email', 'mobile', 'country_code', 'city',
            'jobtitle', 'company', 'student_type_id',
        ]);

        Session::put('pay_seats_data', [
            'names' => $data['firstname'] ?? [],
            'surnames' => $data['lastname'] ?? [],
            'emails' => $data['email'] ?? [],
            'mobiles' => $data['mobile'] ?? [],
            'countryCodes' =>  $data['country_code'] ?? [],
            'cities' => $data['city'] ?? [],
            'jobtitles' => $data['jobtitle'] ?? [],
            'companies' => $data['company'] ?? [],
            'student_type_id' => $data['student_type_id'] ?? [],
        ]);

        //Cart::update();
        return Redirect::to('/registration')
            ->with('success', 'Shopping cart was successfully updated.');
        /*return redirect()->route('cart')->with('success', 'Shopping cart was successfully updated.');*/
    }

    public function securePayment(Request $request)
    {
        //dd($request->all());
        Session::forget('dperror');
        Session::forget('error');

        $input = decrypt($request->input);
        $charge = $request->paymentIntent;
        $namount = $input['total_amount'];

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
            //$ev_date_help = $ev->summary1->where('section','date')->first() ? $ev->summary1->where('section','date')->first()->title : 'date';

            $eventInfo = $ev->event_info();
            if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
                $ev_date_help = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
                                    $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                                                $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
            } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
                $ev_date_help = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
                                        $eventInfo['inclass']['dates']['visible']['emails'] ? $eventInfo['inclass']['dates']['text'] : '';
            }

            $ev_title = $ev->title;
            $ticket_id = $item->id;
            break;
            //$item->id  <-ticket id
        }

        $data = [];
        if (Session::has('pay_seats_data')) {
            $pay_seats_data = Session::get('pay_seats_data');
        } else {
            $pay_seats_data = [];
        }

        if (Session::has('pay_bill_data')) {
            $pay_bill_data = Session::get('pay_bill_data');
            $bd = json_encode($pay_bill_data);
        } else {
            $bd = '';
            $pay_bill_data = [];
        }

        if (Session::has('installments')) {
            $installments = Session::get('installments');
        } else {
            $installments = 0;
        }

        $amount = Cart::total();

        $temp = [];
        if (isset($pay_bill_data)) {
            $temp = $pay_bill_data;
            if ($temp['billing'] == 1) {
                $address = [];
                $address['country'] = 'GR';

                $temp['billing'] = 'Receipt requested';

                $st_name = $temp['billname'];
                $st_tax_id = 'EL' . $temp['billafm'];

                if (isset($temp['billaddress'])) {
                    $st_line1 = $temp['billaddress'];

                    if (isset($temp['billaddressnum'])) {
                        $st_line1 .= ' ' . $temp['billaddressnum'];
                    }

                    $address['line1'] = $st_line1;
                }

                if (isset($temp['billcity'])) {
                    $st_city = $temp['billcity'];
                    $address['city'] = $st_city;
                }

                if (isset($temp['billpostcode'])) {
                    $st_postal_code = $temp['billpostcode'];
                    $address['postal_code'] = $st_postal_code;
                }

           //     $st_phone = $temp['billmobile'];
            } else {
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

        if ((is_array($charge) && $charge['status'] == 'succeeded') || (isset($charge) && $charge->status == 'succeeded')) {
            $status_history = [];
            //$payment_cardtype = intval($input["cardtype"]);
            $status_history[] = [
                'datetime' => Carbon::now()->toDateTimeString(),
                'status' => 1,
                'user' => [
                    'id' => $dpuser->id,
                    'email' => $dpuser->email,
                ],
                'pay_seats_data' => $pay_seats_data,
                'pay_bill_data' => $pay_bill_data,
                'deree_user_data' => [$dpuser->email => ''],
                //'cardtype' => $payment_cardtype,
                'installments' => $installments,
                'cart_data' => $cart,

            ];
            $transaction_arr = [

                'payment_method_id' => 100, //$input['payment_method_id'],
                'account_id' => 17,
                'payment_status' => 2,
                'billing_details' => $bd,
                'status_history' => json_encode($status_history),
                'placement_date' => Carbon::now()->toDateTimeString(),
                'ip_address' => \Request::ip(),
                'status' => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
                'is_bonus' => 0,
                'order_vat' => 0,
                'payment_response' => json_encode($charge),
                'surcharge_amount' => 0,
                'discount_amount' => 0,
                'coupon_code' => $input['couponCode'],
                'amount' => $input['total_amount'],
                'total_amount' => $input['total_amount'],
                'trial' => false,
            ];

            $transaction = Transaction::create($transaction_arr);

            if ($transaction) {
                //$transaction->user()->save($dpuser);
                $transaction->event()->save($ev);

                if ($installments <= 1) {
                    /*if(!Invoice::latest()->doesntHave('subscription')->first()){
                    //if(!Invoice::has('event')->latest()->first()){
                        $invoiceNumber = sprintf('%04u', 1);
                    }else{
                        //$invoiceNumber = Invoice::has('event')->latest()->first()->invoice;
                        $invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
                        $invoiceNumber = (int) $invoiceNumber + 1;
                        $invoiceNumber = sprintf('%04u', $invoiceNumber);
                    }*/

                    $paymentMethodId = $event->paymentMethod->first() ? $event->paymentMethod->first()->id : -1;
                    $elearningInvoice = new Invoice;
                    $elearningInvoice->name = json_decode($transaction->billing_details, true)['billname'];
                    $elearningInvoice->amount = round($namount / $installments, 2);
                    $elearningInvoice->invoice = generate_invoice_number($paymentMethodId);
                    $elearningInvoice->date = date('Y-m-d'); //Carbon::today()->toDateString();
                    $elearningInvoice->instalments_remaining = $installments;
                    $elearningInvoice->instalments = $installments;

                    $elearningInvoice->save();

                    //$elearningInvoice->user()->save($dpuser);
                    $elearningInvoice->event()->save($ev);
                    $elearningInvoice->transaction()->save($transaction);
                } else {
                    //$transaction->subscription()->save($dpuser->subscriptions->where('id',$charge['id'])->first());
                }

                \Session::put('transaction_id', $transaction->id);
            }

            return response()->json([
                'success' => true,
                'redirect' => '/order-success',
            ]);
        }
    }
}
