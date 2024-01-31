<?php

namespace App\Http\Controllers\Theme;

use App\Events\EmailSent;
use App\Http\Controllers\Controller;
use App\Jobs\EventSoldOut;
use App\Model\Activation;
use App\Model\CookiesSMS;
use App\Model\Event;
use App\Model\Option;
use App\Model\PaymentMethod;
use App\Model\Role;
use App\Model\ShoppingCart;
use App\Model\Transaction;
use App\Model\User;
use App\Notifications\CourseInvoice;
use App\Notifications\InstructionMail;
use App\Notifications\SubscriptionWelcome;
use App\Notifications\WelcomeEmail;
use App\Services\FBPixelService;
use Auth;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Carbon\Carbon;
use Cart as Cart;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mail;
use PDF;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Duration;
use Redirect;
use Session;

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
        $data = [];
        //$data['lang'] = $_ENV['LANG'];
        //$data['website'] = $_ENV['WEBSITE'];

        $data['pay_methods'] = [];
        $data['pay_methods'] = PaymentMethod::whereIn('status', [1, 2])->get();

        if (Session::has('transaction_id')) {
            $transaction = Transaction::where('id', Session::get('transaction_id'))->first();

            if ($transaction) {
                $this->transaction = $transaction->toArray();
            } else {
                $this->transaction = [];
            }
        } else {
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
                $data['event']['facebook'] = url('/') . '/' . $thisevent->slugable->slug . '?utm_source=Facebook&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&quote=' . urlencode('Proudly participating in ' . $thisevent->title . ' by Knowcrunch.');
                $data['event']['twitter'] = urlencode('Proudly participating in ' . $thisevent->title . ' ' . url('/') . '/' . $thisevent->slugable->slug . ' by Knowcrunch. 💙');
                $data['event']['linkedin'] = urlencode(url('/') . '/' . $thisevent->slugable->slug . '?utm_source=LinkedIn&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&title=' . 'Proudly participating in ' . $thisevent->title . ' by Knowcrunch. 💙');
                //$data['event']['linkedin'] = urlencode('https://knowcrunch.com/' . '/' .$thisevent->slugable->slug .'?utm_source=LinkedIn&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&title='."Proudly participating in ". $thisevent->title . " by Knowcrunch. 💙");

                $stockHelper = $thisevent->ticket->where('ticket_id', $item->id)->first();
                $newstock = $stockHelper->pivot->quantity - $item->qty;
                $stockHelper->pivot->quantity = $newstock;
                $stockHelper->pivot->save();

                //check for active and stockable tickets

                if ($newstock == 0) {
                    $eventStockHelper = $thisevent->ticket()->wherePivot('active', 1)->get();

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
                        dispatch((new EventSoldOut($thisevent->id))->delay(now()->addSeconds(3)));
                    }
                }

                if ($this->transaction['amount'] - floor($this->transaction['amount']) > 0) {
                    $tr_price = number_format($this->transaction['amount'], 2, '.', '');
                } else {
                    $tr_price = number_format($this->transaction['amount'], 0, '.', '');
                    $tr_price = strval($tr_price);
                    $tr_price .= '.00';
                }

                $categoryScript = $thisevent->delivery->first() && $thisevent->delivery->first()->id == 143 ? 'Video e-learning courses' : 'In-class courses'; // $thisevent->category->first() ? 'Event > ' . $thisevent->category->first()->name : '';
                $userEmail = isset($this->transaction['status_history'][0]['pay_seats_data']['emails'][0]) ? $this->transaction['status_history'][0]['pay_seats_data']['emails'][0] : null;

                //hash('sha256', $userEmail)
                $data['tigran'] = ['OrderSuccess_id' => $this->transaction['id'], 'OrderSuccess_total' => $tr_price, 'Price' =>$tr_price, 'Product_id' => $thisevent->id, 'Product_SKU' => $thisevent->id,
                    'Product_SKU' => $thisevent->id, 'ProductCategory' => $categoryScript, 'ProductName' =>  $thisevent->title, 'Quantity' => $item->qty, 'TicketType'=>$stockHelper->type, 'Event_ID' => 'kc_' . time(),
                    'Encrypted_email' => hash('sha256', $userEmail),
                ];

                $customerBillingCompany = '';
                $customerBillingFirstName = '';
                $customerBillingLastName = '';
                $customerBillingCompany = '';
                $customerBillingAddress1 = '';
                $customerBillingAddress2 = '';
                $customerBillingCity = '';
                $customerBillingCountry = '';
                $customerBillingPostcode = '';
                $customerBillingEmail = '';
                try {
                    $billDet = json_decode($this->transaction['billing_details']);
                    if (isset($this->transaction['status_history'][0]['pay_seats_data']['names'][0])) {
                        $customerBillingFirstName = $this->transaction['status_history'][0]['pay_seats_data']['names'][0];
                    }
                    if (isset($this->transaction['status_history'][0]['pay_seats_data']['surnames'][0])) {
                        $customerBillingLastName = $this->transaction['status_history'][0]['pay_seats_data']['surnames'][0];
                    }
                    if (isset($billDet->billname)) {
                        $customerBillingCompany = $billDet->billname;
                    }
                    if (isset($billDet->billemail)) {
                        $customerBillingEmail = $billDet->billemail;
                    }
                    if (isset($billDet->billaddress)) {
                        $customerBillingAddress1 = $billDet->billaddress;
                    }
                    if (isset($billDet->billaddressnum)) {
                        $customerBillingAddress2 = $billDet->billaddressnum;
                    }
                    if (isset($billDet->billpostcode)) {
                        $customerBillingPostcode = $billDet->billpostcode;
                    }
                    if (isset($billDet->billcity)) {
                        $customerBillingCity = $billDet->billcity;
                    }
                    if (isset($billDet->billcountry)) {
                        $customerBillingCountry = $billDet->billcountry;
                    }
                    if (isset($billDet->billemail)) {
                        $customerBillingEmail = $billDet->billemail;
                    }
                } catch(\Exception $e) {
                }

                $data['customer'] = [
                    'customerTotalOrders' => $item->qty,
                    'customerTotalOrderValue' => $tr_price,
                    'customerFirstName' => $customerBillingFirstName,
                    'customerLastName' => $customerBillingLastName,
                    'customerBillingFirstName' => $customerBillingFirstName,
                    'customerBillingLastName' => $customerBillingLastName,
                    'customerBillingCompany' => $customerBillingCompany,
                    'customerBillingAddress1' => $customerBillingAddress1,
                    'customerBillingAddress2' => $customerBillingAddress2,
                    'customerBillingCity' => $customerBillingCity,
                    'customerBillingPostcode' => $customerBillingPostcode,
                    'customerBillingCountry' => $customerBillingCountry,
                    'customerBillingEmail' => $customerBillingEmail,
                    'customerBillingEmailHash' => hash('sha256', $customerBillingEmail),
                ];

                /*$data['ecommerce'] = ['ecommerce' => ['transaction_id' => $this->transaction['id'], 'value' => $tr_price, 'currency' => 'EUR', 'coupon' => $transaction->coupon_code],
                                    'items' => ['item_name' => $thisevent->title, 'item_id' => $thisevent->id, 'price' => $tr_price, 'quantity' => 1, 'item_category' =>  $categoryScript] ];*/

                $data['ecommerce'] = [
                    'actionField' => ['id' => $this->transaction['id'], 'value' => $tr_price, 'currency' => 'EUR', 'coupon' => $transaction->coupon_code],
                    'products' => ['name' => $thisevent->title, 'id' => $thisevent->id, 'brand'=>'Knowcrunch', 'price' => $tr_price,
                        'category' => $categoryScript, 'coupon' => $transaction->coupon_code, 'quantity' => Cart::content()->count()],

                ];

                $data['new_event'] = [
                    'transaction_id'=> $transaction['id'],
                    'value' => $tr_price,
                    'currency' => 'EUR',
                    'coupon' => $transaction->coupon_code,
                    'items' => [
                        'item_id' => $thisevent->id,
                        'item_name' => $thisevent->title,
                        'item_brand' => 'Knowcrunch',
                        'item_category' => $categoryScript,
                        'price' => $tr_price,
                        'quantity' => 1,
                    ],
                ];

                $data['gt3'] = ['gt3' => ['transactionId' => $this->transaction['id'], 'transactionTotal' => $tr_price],
                    'transactionProducts' => ['name' => $thisevent->title, 'sku' => $thisevent->id, 'price' => $tr_price, 'quantity' => 1, '' =>  $categoryScript]];
            }

            if ($transaction) {
                $sepa = Session::has('payment_method_is_sepa') ? true : false;

                $this->createUsersFromTransaction($transaction, $sepa);
            }
        }

        //$this->sendEmails($trans_id);

        //DELETE SAVED CART IF USER LOGGED
        if ($user = Auth::user()) {
            $existingcheck = ShoppingCart::where('identifier', $user->id)->first();
            if ($existingcheck) {
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
        Session::forget('payment_method_is_sepa');
        ///dd($data);

        if (isset($this->transaction['payment_response'])) {
            Session::put('thankyouData', $data);
            try {
                session_start();
                $_SESSION['thankyouData'] = $data;
            } catch(\Exception $ex) {
                Bugsnag::notifyException($ex);
            }

            return redirect('/thankyou');
        //return view('theme.cart.new_cart.thank_you', $data);
        } else {
            return Redirect::to('/');
        }
    }

    public function orderError()
    {
        $data = [];

        $data['pay_methods'] = [];

        $cart = Cart::content();

        $data['eventId'] = '';
        $data['categoryScript'] = '';

        foreach ($cart as $cart) {
            $ev = Event::find($cart->options['event']);
            if ($ev->category->first()) {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
            $data['pay_bill_data'] = [];
        }

        if (Session::has('deree_user_data')) {
            $data['deree_user_data'] = Session::get('deree_user_data');
        } else {
            $data['deree_user_data'] = [];
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

        Session::forget('deree_user_data');

        $data['city'] = '';
        $data['duration'] = '';

        return view('theme.cart.cart', $data);
    }

    public function createUsersFromTransaction($transaction, $sepa = false)
    {
        /*
        1. Knowcrunch Student ID
        KC - Knowcrunch
        YY - year of registration e.g. 17
        MM - month of registration e.g. 01
        ID - next available ID on the system 4-digit 0001-9999 e.g. 0129
        KC-17010004
        */

        $KC = 'KC-';
        $time = strtotime($transaction->placement_date);
        $MM = date('m', $time);
        $YY = date('y', $time);

        $option = Option::where('abbr', 'website_details')->first();
        // next number available up to 9999
        $next = $option->value;

        $pay_seats_data = $transaction['status_history'][0]['pay_seats_data'];
        if (isset($transaction['status_history'][0]['deree_user_data'])) {
            $deree_user_data = $transaction['status_history'][0]['deree_user_data'];
        } else {
            $deree_user_data = [];
        }

        $pay_bill_data = $transaction['status_history'][0]['pay_bill_data'];

        if (isset($transaction['status_history'][0]['cardtype'])) {
            $cardtype = $transaction['status_history'][0]['cardtype'];
        }

        $installments = $transaction['status_history'][0]['installments'];

        $emailsCollector = [];
        $billDet = json_decode($transaction['billing_details'], true);
        $billingEmail = isset($billDet['billemail']) && $billDet['billemail'] != '' ? $billDet['billemail'] : false;

        if (isset($transaction['billing_details']['billing'])) {
            if ($transaction['billing_details']['billing'] == 2) {
                $invoice = 'YES';
            } else {
                $invoice = 'NO';
            }
        } else {
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
            $eventcity = '';
            $elearning = false;
            $expirationDate = '';
            $eventslug = '';
            $stripe = false;
            $paymentMethodId = 0;

            if ($thisevent) {
                $paymentMethodId = $thisevent->paymentMethod->first() ? $thisevent->paymentMethod->first()->id : 0;
                $stripe = ($thisevent->paymentMethod->first() && $thisevent->paymentMethod->first()->id !== 1);
                if ($thisevent->view_tpl === 'elearning_event') {
                    $elearning = true;
                    $eventslug = $thisevent->slug;
                } else {
                }
                //  dd($eventslug);
                $eventname = $thisevent->title;
                $eventcity = ''; //$thisevent->categories->where('parent_id',9)->first()->name;
                //$eventdate = $thisevent->summary1->where('section','date')->first() ? $thisevent->summary1->where('section','date')->first()->title : '';

                /*$visibleDates = isset($eventInfo['inclass']['dates']['visible']['emails']) ? $eventInfo['inclass']['dates']['visible']['emails'] : null;
                if($visibleDates){
                    $eventdate = isset($eventInfo['inclass']['dates']['text']) ? $eventInfo['inclass']['dates']['text'] : null;
                }*/

                if ($thisevent->city->first() != null) {
                    $eventcity = $thisevent->city->first()->name;
                }
            } else {
                $eventname = 'EventName';
                $eventdate = '';

                $eventcity = 'EventCity';
            }
        }

        //if(!$elearning){
        if ($thisevent->paymentMethod->first() && $thisevent->paymentMethod->first()->id == 1) {
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

            if (isset($deree_user_data[$value])) {
                $thismember['password'] = $deree_user_data[$value];
            } else {
                $thismember['password'] = $thismember['email'] . '-knowcrunch';
            }

            $thismember['mobile'] = $pay_seats_data['mobiles'][$key];
            $thismember['country_code'] = $pay_seats_data['countryCodes'][$key];

            $thismember['job_title'] = '';
            $thismember['company'] = '';

            if (isset($pay_seats_data['jobtitles'][$key])) {
                $thismember['job_title'] = $pay_seats_data['jobtitles'][$key];
            }

            if (isset($pay_seats_data['companies'][$key])) {
                $thismember['company'] = $pay_seats_data['companies'][$key];
            }

            if (isset($pay_seats_data['afms'][$key])) {
                $thismember['afm'] = $pay_seats_data['afms'][$key];
            }

            if (isset($pay_seats_data['cities'][$key])) {
                $thismember['city'] = $pay_seats_data['cities'][$key];
            }

            $checkemailuser = User::where('email', '=', $thismember['email'])->first();
            $expiration_date = '';
            if ($checkemailuser) {
                //if(!$elearning){
                $transaction->user()->save($checkemailuser);
                //}

                //if($elearning){
                $invoice = $transaction->invoice()->first();
                if ($invoice) {
                    $invoice->user()->save($checkemailuser);
                }
                //}

                if ($evid && $evid > 0) {
                    $today = date('Y/m/d');

                    if ($thisevent->getAccessInMonths() > 0) {
                        $monthsExp = '+' . $thisevent->getAccessInMonths() . 'months';
                        $expiration_date = date('Y-m-d', strtotime($monthsExp, strtotime($today)));
                    }
                    //$thisevent->users()->where('id',$checkemailuser)->detach();
                    $thisevent->users()->wherePivot('user_id', $checkemailuser->id)->detach();
                    if ($tickettypedrop == 7) {
                        //$tmp = EventStudent::firstOrCreate(['event_id' => $evid, 'student_id' => $checkemailuser->id, 'trans_id' => $transaction->id,'comment'=>'unilever']);
                        $thisevent->users()->save($checkemailuser, ['comment'=>'unilever', 'expiration'=>$expiration_date, 'paid'=>!$sepa, 'payment_method'=>$paymentMethodId]);
                    } else {
                        if ($transaction->coupon_code != '') {
                            //$tmp = EventStudent::firstOrCreate(['event_id' => $evid, 'student_id' => $checkemailuser->id, 'trans_id' => $transaction->id,'comment'=>'coupon']);
                            $thisevent->users()->save($checkemailuser, ['comment'=>'coupon', 'expiration'=>$expiration_date, 'paid'=>!$sepa, 'payment_method'=>$paymentMethodId]);
                        } else {
                            $thisevent->users()->save($checkemailuser, ['expiration'=>$expiration_date, 'paid'=>!$sepa, 'payment_method'=>$paymentMethodId]);
                        }
                    }
                    $thisevent->tickets()->save($thisticket, ['user_id' => $checkemailuser->id]);
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

                if (isset($thismember['afm'])) {
                    $checkemailuser->afm = $thismember['afm'];
                }

                if ($checkemailuser->partner_id == '' && isset($deree_user_data[$value])) {
                    $checkemailuser->partner_id = $deree_user_data[$value];
                }

                if ($checkemailuser->kc_id == '') {
                    $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);
                    $knowcrunch_id = $KC . $YY . $MM . $next_kc_id;
                    $checkemailuser->kc_id = $knowcrunch_id;
                    $checkemailuser->save();
                    //$thismember['password'] =  $knowcrunch_id;

                    if ($next == 9999) {
                        $next = 1;
                    } else {
                        $next = $next + 1;
                    }
                }

                $checkemailuser->save();
                $creatAccount = false;

                $connow = Carbon::now();
                $clientip = '';
                $clientip = \Request::ip();
                $consent['ip'] = $clientip;
                $consent['date'] = $connow;
                $consent['firstname'] = $checkemailuser->firstname;
                $consent['lastname'] = $checkemailuser->lastname;
                if ($checkemailuser->afm) {
                    $consent['afm'] = $checkemailuser->afm;
                }

                $billing = json_decode($checkemailuser->receipt_details, true);

                if (isset($billing['billafm']) && $billing['billafm']) {
                    $consent['billafm'] = $billing['billafm'];
                }

                $checkemailuser->consent = json_encode($consent);
                $checkemailuser->save();

                if ($checkemailuser->statusAccount && !$checkemailuser->statusAccount->completed) {
                    $creatAccount = true;

                    $cookieValue = base64_encode($checkemailuser->id . date('H:i'));
                    setcookie('auth-' . $checkemailuser->id, $cookieValue, time() + (1 * 365 * 86400), '/'); // 86400 = 1 day

                    $coockie = new CookiesSMS;
                    $coockie->coockie_name = 'auth-' . $checkemailuser->id;
                    $coockie->coockie_value = $cookieValue;
                    $coockie->user_id = $checkemailuser->id;
                    $coockie->sms_code = -1;
                    $coockie->sms_verification = true;

                    $coockie->save();
                }

                $emailsCollector[] = ['email' => $checkemailuser->email, 'name' => $fullname, 'first' => $firstname, 'id' => $checkemailuser->id,
                    'mobile' => $checkemailuser->mobile, 'company' => $checkemailuser->company, 'jobTitle' => $checkemailuser->job_title, 'createAccount'=>$creatAccount];
            } else {
                $newmembersdetails[] = $thismember;
                $fullname = $thismember['firstname'] . ' ' . $thismember['lastname'];
                $firstname = $thismember['firstname'];
                $emailsCollector[] = ['id' => null, 'email' => $thismember['email'], 'name' => $fullname, 'first' => $firstname, 'company' => $thismember['company'],
                    'mobile' => $thismember['mobile'], 'jobTitle' => $thismember['job_title'], 'createAccount'=>true,
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

        $extrainfo = [$tickettypedrop, $tickettypename, $eventname, $eventdate, $specialseats, 'YES', $eventcity, $groupEmailLink, $expiration_date];

        //Create new collected users

        $helperdetails = [];

        foreach ($newmembersdetails as $key => $member) {
            $consent = [];

            $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);
            $knowcrunch_id = $KC . $YY . $MM . $next_kc_id;
            $member['password'] = Hash::make($KC . $YY . $MM . $next_kc_id);
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
            if (isset($pay_seats_data['companies'][$key])) {
                $user->company = $pay_seats_data['companies'][$key];
            }

            $connow = Carbon::now();
            $clientip = '';
            $clientip = \Request::ip();
            $user->terms = 0;
            $consent['ip'] = $clientip;
            $consent['date'] = $connow;
            $consent['firstname'] = $user->firstname;
            $consent['lastname'] = $user->lastname;

            if (isset($deree_user_data[$value])) {
                $user->partner_id = $deree_user_data[$value];
            } else {
                $user->partner_id = '';
            }

            $user->kc_id = $knowcrunch_id;
            $user->terms = 0;

            if (isset($pay_seats_data['studentId'][$key])) {
                $user->student_type_id = $pay_seats_data['studentId'][$key];
            } else {
                $user->student_type_id = '';
            }
            if (isset($pay_seats_data['afms'][$key])) {
                $user->afm = $pay_seats_data['afms'][$key];
                $consent['afm'] = $pay_seats_data['afms'][$key];
            } else {
                $user->afm = '';
            }

            $user->consent = json_encode($consent);
            $user->save();
            $transaction->user()->save($user);
            //if($elearning){
            if ($thisevent->paymentMethod->first() && $thisevent->paymentMethod->first()->id !== 1) {
                $invoice = $transaction->invoice()->first();
                if ($invoice) {
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

                if ($thisevent->getAccessInMonths() > 0) {
                    $monthsExp = '+' . $thisevent->getAccessInMonths() . 'months';
                    $expiration_date = date('Y-m-d', strtotime($monthsExp, strtotime($today)));
                }

                if ($tickettypedrop == 7) {
                    $thisevent->users()->save($user, ['comment'=>'unilever', 'expiration'=>$expiration_date, 'paid'=>!$sepa, 'payment_method'=>$paymentMethodId]);
                } else {
                    if ($transaction->coupon_code != '') {
                        $thisevent->users()->save($user, ['comment'=>'coupon', 'expiration'=>$expiration_date, 'paid'=>!$sepa, 'payment_method'=>$paymentMethodId]);
                    } else {
                        $thisevent->users()->save($user, ['expiration'=>$expiration_date, 'paid'=>!$sepa, 'payment_method'=>$paymentMethodId]);
                    }
                }
                $thisevent->tickets()->save($thisticket, ['user_id' => $user->id]);
            }

            if ($next == 9999) {
                $next = 1;
            } else {
                $next = $next + 1;
            }
        }

        $option->value = $next;
        $option->save();

        $paymentMethod = PaymentMethod::find($paymentMethodId);

        if (!$sepa) {
            $this->sendEmails($transaction, $emailsCollector, $extrainfo, $helperdetails, $elearning, $eventslug, $stripe, $billingEmail, $paymentMethod);
        } else {
            $this->sendWelcomeEmailsSepa($transaction, $emailsCollector, $extrainfo, $helperdetails, $elearning, $eventslug, $stripe, $billingEmail, $paymentMethod);
        }
    }

    public function sendWelcomeEmailsSepa($transaction, $emailsCollector, $extrainfo, $helperdetails, $elearning, $eventslug, $stripe, $billingEmail, $paymentMethod = null)
    {
        // dd($elearning);
        // 5 email, admin, user, 2 deree, darkpony
        //$generalInfo = \Config::get('dpoptions.website_details.settings');
        $adminemail = ($paymentMethod && $paymentMethod->payment_email) ? $paymentMethod->payment_email : 'info@knowcrunch.com';

        $data = [];
        $data['fbGroup'] = $extrainfo[7];
        $data['duration'] = ''; //$extrainfo[3];

        $data['eventSlug'] = $transaction->event->first() ? url('/') . '/' . $transaction->event->first()->getSlug() : url('/');

        $eventInfo = $transaction->event->first() ? $transaction->event->first()->event_info() : [];

        if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
            $data['duration'] = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
                                $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                                            $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
        } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
            $data['duration'] = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
                                    $eventInfo['inclass']['dates']['visible']['emails'] ? $eventInfo['inclass']['dates']['text'] : '';

            $data['duration'] = strip_tags($data['duration']);
        }

        $data['hours'] = isset($eventInfo['hours']['visible']['emails']) && $eventInfo['hours']['visible']['emails'] && isset($eventInfo['hours']['hour']) &&
                        isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

        $data['language'] = isset($eventInfo['language']['visible']['emails']) && $eventInfo['language']['visible']['emails'] && isset($eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

        $data['certificate_type'] = isset($eventInfo['certificate']['visible']['emails']) && $eventInfo['certificate']['visible']['emails'] &&
                    isset($eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

        $eventStudents = get_sum_students_course($transaction->event->first()->category->first());
        $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] : $eventStudents + 1;

        $data['students'] = isset($eventInfo['students']['visible']['emails']) && $eventInfo['students']['visible']['emails'] &&
                        isset($eventInfo['students']['text']) && $data['students_number'] >= $eventStudents ? $eventInfo['students']['text'] : '';

        foreach ($emailsCollector as $key => $muser) {
            $data['user'] = $muser;

            $data['trans'] = $transaction;
            $data['extrainfo'] = $extrainfo;
            $data['helperdetails'] = $helperdetails;
            $data['elearning'] = $elearning;
            $data['eventslug'] = $eventslug;

            if (($user = User::where('email', $muser['email'])->first())) {
                if ($user->cart) {
                    $user->cart->delete();
                }

                $data['template'] = $transaction->event->first() && $user->waitingList()->where('event_id', $transaction->event->first()->id)->first()
                                        ? 'waiting_list_welcome' : 'welcome';

                $data['firstName'] = $user->firstname;

                $user->notify(new WelcomeEmail($user, $data));
                event(new EmailSent($user->email, 'WelcomeEmail'));

                /*if($elearning){
                    $user->notify(new InstructionMail($data));
                }*/
            }
        }
    }

    public function sendEmails($transaction, $emailsCollector, $extrainfo, $helperdetails, $elearning, $eventslug, $stripe, $billingEmail, $paymentMethod = null, $sepa = false)
    {
        // dd($elearning);
        // 5 email, admin, user, 2 deree, darkpony
        //$generalInfo = \Config::get('dpoptions.website_details.settings');
        $adminemail = ($paymentMethod && $paymentMethod->payment_email) ? $paymentMethod->payment_email : 'info@knowcrunch.com';

        $data = [];
        $data['fbGroup'] = $extrainfo[7];
        $data['duration'] = ''; //$extrainfo[3];

        $data['eventSlug'] = $transaction->event->first() ? url('/') . '/' . $transaction->event->first()->getSlug() : url('/');

        $eventInfo = $transaction->event->first() ? $transaction->event->first()->event_info() : [];

        if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
            $data['duration'] = isset($eventInfo['elearning']['visible']['emails']) && isset($eventInfo['elearning']['expiration']) &&
                                $eventInfo['elearning']['visible']['emails'] && isset($eventInfo['elearning']['text']) ?
                                            $eventInfo['elearning']['expiration'] . ' ' . $eventInfo['elearning']['text'] : '';
        } elseif (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139) {
            $data['duration'] = isset($eventInfo['inclass']['dates']['visible']['emails']) && isset($eventInfo['inclass']['dates']['text']) &&
                                    $eventInfo['inclass']['dates']['visible']['emails'] ? $eventInfo['inclass']['dates']['text'] : '';

            $data['duration'] = strip_tags($data['duration']);
        }

        $data['hours'] = isset($eventInfo['hours']['visible']['emails']) && $eventInfo['hours']['visible']['emails'] && isset($eventInfo['hours']['hour']) &&
                        isset($eventInfo['hours']['text']) ? $eventInfo['hours']['hour'] . ' ' . $eventInfo['hours']['text'] : '';

        $data['language'] = isset($eventInfo['language']['visible']['emails']) && $eventInfo['language']['visible']['emails'] && isset($eventInfo['language']['text']) ? $eventInfo['language']['text'] : '';

        $data['certificate_type'] = isset($eventInfo['certificate']['visible']['emails']) && $eventInfo['certificate']['visible']['emails'] &&
                    isset($eventInfo['certificate']['type']) ? $eventInfo['certificate']['type'] : '';

        $eventStudents = get_sum_students_course($transaction->event->first()->category->first());
        $data['students_number'] = isset($eventInfo['students']['number']) ? $eventInfo['students']['number'] : $eventStudents + 1;

        $data['students'] = isset($eventInfo['students']['visible']['emails']) && $eventInfo['students']['visible']['emails'] &&
                        isset($eventInfo['students']['text']) && $data['students_number'] >= $eventStudents ? $eventInfo['students']['text'] : '';

        foreach ($emailsCollector as $key => $muser) {
            $data['user'] = $muser;

            $data['trans'] = $transaction;
            $data['extrainfo'] = $extrainfo;
            $data['helperdetails'] = $helperdetails;
            $data['elearning'] = $elearning;
            $data['eventslug'] = $eventslug;

            if (($user = User::where('email', $muser['email'])->first())) {
                if ($user->cart) {
                    $user->cart->delete();
                }

                $data['template'] = $transaction->event->first() && $user->waitingList()->where('event_id', $transaction->event->first()->id)->first()
                                        ? 'waiting_list_welcome' : 'welcome';

                $data['firstName'] = $user->firstname;

                if (isset($transaction['payment_response']) && $transaction['payment_response'] != null) {
                    $paymentResponse = json_decode($transaction['payment_response'], true);
                    if (isset($paymentResponse['metadata']) && isset($paymentResponse['metadata']['integration_check']) && $paymentResponse['metadata']['integration_check'] == 'sepa_debit_accept_a_payment') {
                    } else {
                        $user->notify(new WelcomeEmail($user, $data));
                        event(new EmailSent($user->email, 'WelcomeEmail'));
                    }
                } else {
                    $user->notify(new WelcomeEmail($user, $data));
                    event(new EmailSent($user->email, 'WelcomeEmail'));
                }

                /*if($elearning){
                    $user->notify(new InstructionMail($data));
                }*/
            }
        }

        if ($stripe) {
            //$data = [];
            $muser = [];
            $muser['name'] = $transaction->user->first()->firstname . ' ' . $transaction->user->first()->lastname;
            $muser['first'] = $transaction->user->first()->firstname;
            $muser['email'] = $transaction->user->first()->email;
            $muser['id'] = $transaction->user->first()->id;
            $muser['event_title'] = $transaction->event->first()->title;

            $data['firstName'] = $muser['name'];
            $data['eventTitle'] = $muser['event_title'];

            //Log::info('info controller transaction');
            //Log::info(var_export($transaction->invoice->first(), true));

            if ((Session::has('installments') && Session::get('installments') <= 1) || $sepa) {
                $data['slugInvoice'] = encrypt($muser['id'] . '-' . $transaction->invoice->first()->id);

                $pdf = $transaction->invoice->first()->generateInvoice();

                $invoiceFileName = date('Y.m.d');
                if ($paymentMethod) {
                    $invoiceFileName .= '_' . $paymentMethod->company_name;
                }
                $invoiceFileName .= '_' . $transaction->invoice->first()->invoice . '.pdf';
                $fn = $invoiceFileName;

                $pdf = $pdf->output();
                $sent = Mail::send('emails.admin.elearning_invoice', $data, function ($m) use ($adminemail, $muser, $pdf, $billingEmail, $fn) {
                    $fullname = $muser['name'];
                    $first = $muser['first'];
                    $sub = 'Knowcrunch | ' . $first . ' – download your receipt';
                    $m->from('info@knowcrunch.com', 'Knowcrunch');
                    $m->to($adminemail, $fullname);
                    //$m->to('moulopoulos@lioncode.gr', $fullname);
                    $m->subject($sub);
                });
                event(new EmailSent($adminemail, 'elearning_invoice billingEmail ' . $billingEmail));

                $data['user'] = $transaction->user->first();

                if ($billingEmail) {
                    $sent = Mail::send('emails.user.invoice', $data, function ($m) use ($adminemail, $muser, $pdf, $billingEmail, $fn) {
                        $fullname = $muser['name'];
                        $first = $muser['first'];
                        $sub = 'Knowcrunch | ' . $first . ' – download your receipt';
                        $m->from('info@knowcrunch.com', 'Knowcrunch');
                        $m->to($billingEmail, $fullname);
                        //$m->to('moulopoulos@lioncode.gr', $fullname);
                        $m->subject($sub);
                    });
                    event(new EmailSent($billingEmail, 'download your receipt'));
                } else {
                    $data['user']->notify(new CourseInvoice($data));
                    event(new EmailSent($data['user']->email, 'CourseInvoice'));
                }
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

    public function sendEmailsSubscription($payload, $user, $transaction)
    {
        $subscription = $transaction->subscription()->first();

        $event = $subscription->event()->first();
        $user = $transaction->user()->first();

        $plan = $event['plans'][0];

        $data = [];
        /*$muser = [];
        $muser['name'] = $user->firstname;
        $muser['first'] = $user->firstname;
        $muser['email'] = $user->email;*/
        //$muser['event_title'] = $sub->eventable->event->title;

        //$subEnds = $plan->trial_days && $plan->trial_days > 0 ? $plan->trial_days : $plan->getDays();
        $subEnds = $plan->getDays();
        $subEnds = date('d-m-Y', strtotime("+$subEnds days"));

        //if($exp = $user->events()->wherePivot('event_id',$event->id)->first()){
        if ($exp = $user->events_for_user_list()->wherePivot('event_id', $event->id)->first()) {
            $exp = $exp->pivot->expiration;
            $exp = strtotime($exp);
            $today = strtotime(date('Y-m-d'));

            if ($exp && $exp > $today) {
                $exp = date_create(date('Y-m-d', $exp));
                $today = date_create(date('Y-m-d', $today));

                $days = date_diff($exp, $today);

                $subEnds = date('Y-m-d', strtotime($subEnds . ' + ' . $days->d . ' days'));
            }
        }

        $data['firstName'] = $user->firstname;

        $data['name'] = $user->firstname . ' ' . $user->lastname;
        $data['email'] = $user->email;
        $data['amount'] = $plan->cost;
        $data['position'] = $user->job_title;
        $data['company'] = $user->company;
        $data['mobile'] = $user->mobile;
        $data['userLink'] = url('/') . '/admin/user/' . $user['id'] . '/edit';

        $data['eventTitle'] = $event->title;
        $data['eventFaq'] = url('/') . '/' . $event->getSlug() . '#faq';
        $data['eventSlug'] = url('/') . '/myaccount/elearning/' . $event->title;
        $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' to our annual subscription';
        $data['template'] = 'emails.user.subscription_welcome';
        $data['subscriptionEnds'] = $subEnds;
        /*$data['sub_type'] = $plan->name;
        $data['sub_price'] = $plan->cost;
        $data['sub_period'] = $plan->period();*/

        $user->notify(new SubscriptionWelcome($data));
        event(new EmailSent($user->email, 'SubscriptionWelcome'));
    }
}
