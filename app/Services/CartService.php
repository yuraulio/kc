<?php

namespace App\Services;

use App\Events\EmailSent;
use App\Exceptions\ProductAlreadyInCartException;
use App\Exceptions\UserAlreadyEnrolledToTheCourseException;
use App\Model\Activation;
use App\Model\CartCache;
use App\Model\CookiesSMS;
use App\Model\Coupon;
use App\Model\Delivery;
use App\Model\Event;
use App\Model\Invoice;
use App\Model\Option;
use App\Model\ShoppingCart;
use App\Model\Ticket;
use App\Model\Transaction;
use App\Model\User;
use App\Notifications\WelcomeEmail;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Stripe\Plan;
use Stripe\Stripe;

class CartService
{
    public function __construct(
        private readonly FBPixelService $fbp,
    ) {
    }

    public function calculateDiscountAmount(float $totalAmount, $coupon): float
    {
        if (!$coupon || (is_array($coupon) && empty($coupon))) {
            return 0;
        }

        if ($coupon['percentage']) {
            return $totalAmount / 100 * $coupon['price'];
        }

        return $totalAmount - $coupon['price'];
    }

    /**
     * This function prevents student to enroll to the same course multiple times.
     */
    public function checkMultiplePayments(Collection $itemsInCart, array $paySeatsData): void
    {
        $userEmails = $paySeatsData['emails'] ?? [];

        $eventIds = [];

        foreach ($itemsInCart->pluck('options') as $option) {
            $eventIds[] = $option->event;
        }

        $events = Event::whereIn('id', $eventIds)->select(['id', 'title'])->get()->keyBy('id');

        $users = User::whereIn('email', $userEmails)->get();

        // check if user enrolled to course without subscription
        foreach ($users as $user) {
            if ($user->allEvents->whereIn('id', $events->pluck('id'))->isNotEmpty()) {
                throw new UserAlreadyEnrolledToTheCourseException($userEmails, $events->pluck('title')->toArray());
            }
        }
    }

    public function calculateInstallments(array $eventInfo): int
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

    /**
     * Partially refactored 19.10.2024.
     */
    public function registerStudents(?User $authUser, array $formData, string $requestIp): void
    {
        DB::beginTransaction();

        try {
            $data = [];
            $data = $this->initCartDetails($authUser, $data);
            $user = false;

            if ((!$authUser && !($user = User::where('email', $formData['email'][0])->first())) && $data['type'] != 3) {
                $input = [];
                unset($formData['_token']);
                unset($formData['terms_condition']);
                unset($formData['update']);
                unset($formData['type']);

                foreach ($formData as $key => $value) {
                    $input[$key] = $value[0];
                }

                $input['password'] = Hash::make(date('Y-m-dTH:i:s'));

                $user = User::create($input);

                $user->terms = 1;
                $consent['ip'] = $requestIp;
                $consent['date'] = Carbon::now();
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

                Activation::create([
                    'user_id' => $user->id,
                    'code' => Str::random(40),
                    'completed' => false,
                ]);

                $user->role()->attach(7);
                Session::put('user_id', $user->id);

                $this->refreshShoppingCart(
                    ShoppingCart::where('identifier', $user->id)->first(),
                    $user,
                );

                $this->saveCartToCache(Cart::content(), $user);
            } elseif ($user || $user = $authUser) {
                $this->refreshShoppingCart(
                    ShoppingCart::where('identifier', $user->id)->first(),
                    $user,
                );

                $this->saveCartToCache(Cart::content(), $user);
            }

            $seats_data = [];

            if ($data['type'] == 3 && $authUser && $authUser->kc_id) {
                $seats_data['names'][] = $authUser->firstname;
                $seats_data['surnames'][] = $authUser->lastname;
                $seats_data['emails'][] = $authUser->email;
                $seats_data['mobiles'][] = $authUser->mobile;
                $seats_data['mobileCheck'][] = $authUser->mobileCheck;
                $seats_data['countryCodes'][] = $authUser->country_code;
                $seats_data['cities'][] = $authUser->city;
                $seats_data['jobtitles'][] = $authUser->jobtitle;
                $seats_data['companies'][] = $authUser->company;
                $seats_data['student_type_id'][] = $authUser->student_type_id;
                Session::put('user_id', $authUser->id);
            } elseif ($data['type'] != 3) {
                $seats_data['names'] = $formData['firstname'] ?? null;
                $seats_data['surnames'] = $formData['lastname'] ?? null;
                $seats_data['emails'] = $formData['email'] ?? null;
                $seats_data['mobiles'] = $formData['mobile'] ?? null;
                $seats_data['mobileCheck'] = $formData['mobileCheck'] ?? null;
                $seats_data['countryCodes'] = $formData['country_code'] ?? null;
                $seats_data['cities'] = $formData['city'] ?? null;
                $seats_data['jobtitles'] = $formData['jobtitle'] ?? null;
                $seats_data['companies'] = $formData['company'] ?? null;
                $seats_data['student_type_id'] = $formData['student_type_id'] ?? null;
                Session::put('user_id', $authUser ? $authUser->id : $user->id);
            } else {
                Cart::instance('default')->destroy();
                Session::forget('pay_seats_data');
                Session::forget('transaction_id');
                Session::forget('cardtype');
                Session::forget('installments');
                Session::forget('pay_bill_data');
                Session::forget('deree_user_data');
                Session::forget('user_id');
                Session::forget('coupon_code');
                Session::forget('coupon_price');
                Session::forget('priceOf');
            }

            if ($authUser && isset($seats_data['emails'][0])) {
                $seats_data['emails'][0] = $authUser->email;
            }

            Session::put('pay_seats_data', $seats_data);

            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }
    }

    private function saveCartToCache($cart, User $user): void
    {
        $event = $cart->first()->options->event;
        $tid = $cart->first()->id;

        if ($user->cart) {
            $user->cart->delete();
        }

        $cartCache = new CartCache();

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

    private function refreshShoppingCart(?ShoppingCart $existingCartCheck, User $user): void
    {
        if ($existingCartCheck) {
            if ($user->cart) {
                $user->cart->delete();
            }
            $existingCartCheck->delete($user->id);
        }

        Cart::store($user->id);

        $shoppingCart = ShoppingCart::where('identifier', $user->id)->first();
        $shoppingCart->created_at = Carbon::now();
        $shoppingCart->updated_at = Carbon::now();
        $shoppingCart->save();
    }

    public function storeBillingData(array $requestData, ?User $user): void
    {
        $billingData = [
            'billing' => 1,
            'billname' => $requestData['billname'] ?? null,
            'billemail' => $requestData['billemail'] ?? null,
            'billaddress' => $requestData['billaddress'] ?? null,
            'billaddressnum' => $requestData['billaddressnum'] ?? null,
            'billpostcode' => $requestData['billpostcode'] ?? null,
            'billcity' => $requestData['billcity'] ?? null,
            'billcountry' => $requestData['billcountry'] ?? null,
            'billstate' => $requestData['billstate'] ?? null,
            'billafm' => $requestData['billafm'] ?? null,
        ];

        if (!$user) {
            $user = User::find(Session::get('user_id'));
        }

        if ($user) {
            //UPDATE billing in user profile

            $user->receipt_details = json_encode($billingData);
            $user->afm = $billingData['billafm'];
            $user->save();
        }

        Session::put('pay_bill_data', $billingData);
    }

    /** Not refactored, only moved to service class */
    public function initCartDetails(?User $authUser, array $data): array
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

        if ($authUser) {
            $data['tigran']['User_id'] = $authUser->id;
        } else {
            $data['tigran']['Visitor_id'] = session()->getId();
        }

        return $data;
    }

    /**
     * Payment using stored in stripe database user's payment method.
     */
    public function generateStripeWalletPayUrl(?User $user, array $requestData, string $requestIp): string
    {
        $this->checkMultiplePayments(Cart::content(), Session::get('pay_seats_data'));

        $paymentMethodId = intval($requestData['payment_method_id']);
        $data = [];

        if (isset($requestData['installments'])) {
            Session::put('installments', $requestData['installments']);
        } else {
            Session::put('installments', 1);
        }

        if (Session::get('coupon_code')) {
            $requestData['coupon'] = Session::get('coupon_code');
        }

        $data = $this->initCartDetails($user, $data);
        $this->fbp->sendAddPaymentInfoEvent($data);

        if ($paymentMethodId != 1) {
            $url = $this->postPaymentWithStripe($user, $requestData, $requestIp);
        } else {
            throw new \Exception('Unexpected payment_method_id value');
        }

        return $url;
    }

    public function paySbt(?User $user, array $requestData, string $requestIp): string
    {
        $this->checkMultiplePayments(Cart::content(), Session::get('pay_seats_data'));

        $paymentMethodId = intval($requestData['payment_method_id']);
        $data = [];

        if (isset($requestData['installments'])) {
            Session::put('installments', $requestData['installments']);
        } else {
            Session::put('installments', 1);
        }

        if (Session::get('coupon_code')) {
            $requestData['coupon'] = Session::get('coupon_code');
        }

        $data = $this->initCartDetails($user, $data);
        $this->fbp->sendAddPaymentInfoEvent($data);

        if ($paymentMethodId != 1) {
            return $this->postPaymentWithStripe($user, $requestData, $requestIp);
        } else {
            return $this->alphaBankPayment($requestData, $requestIp);
        }
    }

    public function paySEPA(?User $user, array $requestData, string $requestIp)
    {
        $this->checkMultiplePayments(Cart::content(), Session::get('pay_seats_data'));

        Log::info('createSepa');
        $input = $requestData;
        $data = [];

        if (isset($input['installments'])) {
            Session::put('installments', $input['installments']);
        } else {
            Session::put('installments', 1);
        }

        if (Session::get('coupon_code')) {
            $input['coupon'] = Session::get('coupon_code');
        }

        $data = $this->initCartDetails($user, $data);
        $this->fbp->sendAddPaymentInfoEvent($data);

        Log::info(json_encode($data));
        Session::forget('dperror');
        Session::forget('error');

        $dpuser = $user ? $user : User::find(Session::get('user_id'));
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
        }
        Log::info(json_encode($cart));
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
            $initialAmount = $amount;
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

                $planAmount = $instamount * 100;
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
                ]);

                $payment_method_id = -1;
                if ($ev->paymentMethod->first()) {
                    $payment_method_id = $ev->paymentMethod->first()->id;
                }

                try {
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
                } catch(\Laravel\Cashier\Exceptions\IncompletePayment $exception) {
                    Bugsnag::notifyException($exception);

                    $sub = $dpuser->subscriptions()->where('user_id', $dpuser->id)->first();

                    // TODO
                    // (1) update subscription price
                    $dpuser->subscriptions()->find($sub->id)->update([
                        'price' => $instamount,
                    ]);

                    Session::put('payment_method_is_sepa', true);

                    $this->createTransaction(
                        $dpuser,
                        $pay_seats_data,
                        $installments,
                        $cart,
                        $bd,
                        $ev,
                        $couponCode,
                        $namount,
                        $pay_bill_data,
                        $exception->payment,
                        $eventC,
                        $status = 2,
                        true,
                        $initialAmount,
                        $requestIp
                    );

                    //after new subscription payment is incomplete because pay with SEPA
                    return [
                        'clientSecret' => $exception->payment->client_secret,
                        'return_url' => '/order-success',
                        'status' => '',
                    ];
                }
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
                        $this->createTransaction(
                            $dpuser,
                            $pay_seats_data,
                            $installments,
                            $cart,
                            $bd,
                            $ev,
                            $couponCode,
                            $namount,
                            $pay_bill_data,
                            $paymentIntent,
                            $eventC,
                            $status,
                            false,
                            $initialAmount,
                            $requestIp
                        );

                        Session::put('payment_method_is_sepa', true);

                        Log::info('createTransaction');
                        Log::info(json_encode($paymentIntent));

                        return [
                            'clientSecret' => $paymentIntent->client_secret,
                            'status' => $paymentIntent->status,
                            'return_url' => '/order-success',
                        ];
                    }
                } catch (\Laravel\Cashier\Exceptions\IncompletePayment $exception) {
                    Bugsnag::notifyException($exception);
                }
            }
            Log::info('end');
        } catch(\Throwable $e) {
            Bugsnag::notifyException($e);
        }
    }

    /** Not refactored */
    private function createTransaction(
        $dpuser,
        $pay_seats_data,
        $installments,
        $cart,
        $bd,
        $ev,
        $couponCode,
        $namount,
        $pay_bill_data,
        $charge,
        $eventC,
        $status,
        bool $sepa,
        float $initialAmount,
        string $requestIp
    ): void {
        $coupon = Coupon::where('code_coupon', $couponCode)->first();

        $statusHistory = [];
        $statusHistory[] = [
            'datetime' => Carbon::now()->toDateTimeString(),
            'status' => 1,
            'user' => [
                'id' => $dpuser->id,
                'email' => $dpuser->email,
            ],
            'pay_seats_data' => $pay_seats_data,
            'pay_bill_data' => $pay_bill_data,
            'deree_user_data' => [$dpuser->email => ''],
            'installments' => $installments,
            'cart_data' => $cart,
        ];

        $transaction = Transaction::create([
            'payment_method_id' => 100, //$input['payment_method_id'],
            'account_id' => 17,
            'payment_status' => 2,
            'billing_details' => $bd,
            'status_history' => json_encode($statusHistory),
            'placement_date' => Carbon::now()->toDateTimeString(),
            'ip_address' => $requestIp,
            'status' => $status, //2 PENDING, 0 FAILED, 1 COMPLETED
            'is_bonus' => 0,
            'order_vat' => 0,
            'payment_response' => json_encode($charge),
            'surcharge_amount' => 0,
            'discount_amount' => 0,
            'coupon_code' => $couponCode,
            'coupon_details' => isset($coupon) ? json_encode($coupon) : null,
            'amount' => $namount,
            'total_amount' => $namount,
            'initial_amount' => $initialAmount,
            'trial' => false,
        ]);

        if ($transaction) {
            $transaction->event()->save($ev);

            if (!$sepa) {
                $elearningInvoice = new Invoice();
                $elearningInvoice->name = json_decode($transaction->billing_details, true)['billname'];
                $elearningInvoice->amount = round($namount / $installments, 2);
                $elearningInvoice->invoice = generate_invoice_number($eventC->paymentMethod->first()->id);
                $elearningInvoice->date = date('Y-m-d'); //Carbon::today()->toDateString();
                $elearningInvoice->instalments_remaining = $installments;
                $elearningInvoice->instalments = $installments;

                $elearningInvoice->save();

                $elearningInvoice->event()->save($ev);
                $elearningInvoice->transaction()->save($transaction);
            }

            Session::put('transaction_id', $transaction->id);
        }
    }

    /** Not refactored */
    private function postPaymentWithStripe(?User $user, array $input, string $requestIp): string
    {
        Session::forget('dperror');
        Session::forget('error');

        $dpuser = $user ? $user : User::find(Session::get('user_id'));
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

        $input = Arr::except($input, ['_token']);

        try {
            $amount = Cart::total();
            $initialAmount = $amount;
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
                } else {
                    $temp['billing'] = 'Invoice requested';
                    //generate array for stripe billing
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

                $planAmount = $instamount * 100;
                // Check if the entity has any active subscription

                //./ngrok authtoken 69hUuQ1DgonuoGjunLYJv_3PVuHFueuq5Kiuz7S1t21
                // Create the plan to subscribe
                $desc = $installments . ' installments';
                $planid = 'plan_' . $dpuser->id . '_E_' . $ev->id . '_T_' . $ticket_id . '_x' . $installments . '_' . date('his');
                $name = $ev_title . ' ' . $ev_date_help . ' | ' . $desc;

                $plan = Plan::create([
                    'id'                   => $planid,
                    'product' => [
                        'name'                 => $name,
                    ],

                    'amount'               => $planAmount,
                    'currency'             => 'eur',
                    'interval'             => 'month',

                ]);

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
                } catch(\Laravel\Cashier\Exceptions\IncompletePayment $exception) {
                    $payment_method_id = -1;
                    if ($ev->paymentMethod->first()) {
                        $payment_method_id = $ev->paymentMethod->first()->id;
                    }

                    $ev->users()->wherePivot('user_id', $dpuser->id)->detach();

                    $input['paymentMethod'] = $payment_method_id;
                    $input['amount'] = $instamount;
                    $input['total_amount'] = $namount;
                    $input['couponCode'] = $couponCode;
                    $input['duration'] = $ev_date_help;

                    $input = encrypt($input);
                    session()->put('input', $input);
                    session()->put('noActionEmail', true);

                    return 'summary/' . $exception->payment->id . '/' . $input;
                }
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
                    $payment_method_id = -1;
                    if ($ev->paymentMethod->first()) {
                        $payment_method_id = $ev->paymentMethod->first()->id;
                    }

                    $ev->users()->wherePivot('user_id', $dpuser->id)->detach();

                    $input['paymentMethod'] = $payment_method_id;
                    $input['amount'] = $namount;
                    $input['total_amount'] = $namount;
                    $input['couponCode'] = $couponCode;

                    $input = encrypt($input);

                    return 'summary/' . $exception->payment->id . '/' . $input;
                } catch (\Exception $e) {
                    Session::put('dperror', $e->getMessage());

                    return '/checkout';
                }
            }

            if ((isset($charge) && is_array($charge) && $charge['status'] == 'succeeded') || (isset($charge) && $charge->status == 'succeeded')) {
                $ev->users()->save($dpuser, ['paid' => true, 'payment_method' => $ev->paymentMethod->first()?->id]);
                /**
                 * Write Here Your Database insert logic.
                 */
                $status_history = [];
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
                    'ip_address' => $requestIp,
                    'status' => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
                    'is_bonus' => 0,
                    'order_vat' => 0,
                    'payment_response' => json_encode($charge),
                    'surcharge_amount' => 0,
                    'discount_amount' => $this->calculateDiscountAmount($initialAmount, $coupon),
                    'coupon_code' => $couponCode,
                    'coupon_details' => isset($coupon) ? json_encode($coupon) : null,
                    'amount' => $namount,
                    'total_amount' => $namount,
                    'initial_amount' => $initialAmount,
                    'trial' => false,
                ];

                $transaction = Transaction::create($transaction_arr);

                if ($transaction) {
                    $transaction->event()->save($ev);

                    if ($installments <= 1) {
                        $elearningInvoice = new Invoice();
                        $elearningInvoice->name = json_decode($transaction->billing_details, true)['billname'];
                        $elearningInvoice->amount = round($namount / $installments, 2);
                        $elearningInvoice->invoice = generate_invoice_number($eventC->paymentMethod->first()->id);
                        $elearningInvoice->date = date('Y-m-d'); //Carbon::today()->toDateString();
                        $elearningInvoice->instalments_remaining = $installments;
                        $elearningInvoice->instalments = $installments;

                        $elearningInvoice->save();

                        $elearningInvoice->event()->save($ev);
                        $elearningInvoice->transaction()->save($transaction);
                    }

                    Session::put('transaction_id', $transaction->id);
                }

                return '/order-success';
            } else {
                Session::put('dperror', 'Cannot complete the payment!!');

                return '/checkout';
            }
        } catch (\Exception $e) {
            Session::put('dperror', $e->getMessage());

            return '/checkout';
        }
    }

    /** Not refactored */
    public function alphaBankPayment($input, string $requestIp): string
    {
        $paymentMethodId = intval($input['payment_method_id']);
        $paymentCardType = intval($input['cardtype']);

        $amount = (float) Cart::total();
        $initialAmount = $amount;

        $billingDetails = [];
        $billingDetails['billaddress'] = transliterateGreekToEnglish($input['billaddress']) . ' ' . $input['billaddressnum'];
        $billingDetails['billzip'] = $input['billpostcode'];
        $billingDetails['city'] = transliterateGreekToEnglish($input['billcity']);
        $billingDetails['billcountry'] = 'GR';

        $transaction = Transaction::create([
            'payment_method_id' => $paymentMethodId,
            'account_id' => 17,
            'payment_status' => 2,
            'billing_details' => json_encode($billingDetails), //serialize($billing_details),
            'placement_date' => Carbon::now()->toDateTimeString(),
            'ip_address' => $requestIp,
            'type' => $paymentCardType, //((Sentinel::inRole('super_admin') || Sentinel::inRole('know-crunch')) ? 1 : 0),
            'status' => 2, //2 PENDING, 0 FAILED, 1 COMPLETED
            'is_bonus' => 0, //$input['is_bonus'],
            'order_vat' => 0, //$input['credit'] - ($input['credit'] / (1 + Config::get('dpoptions.order_vat.value') / 100)),
            'surcharge_amount' => 0,
            'discount_amount' => 0,
            'amount' => $amount,
            'total_amount' => $amount,
            'initial_amount' => $initialAmount,
            'trial' => false,
        ]);

        if ($transaction) {
            Session::put('transaction_id', $transaction->id);

            return '/payment-dispatch/checkout/' . $transaction->id;
        } else {
            throw new \Exception('CartService@alphaBankPayment: Error: Error during the transaction creation');
        }
    }

    public function removeProductFromCart(?User $user, string $id, bool $isAjax): void
    {
        Cart::remove($id);

        if ($user) {
            $existingCheck = ShoppingCart::where('identifier', $user->id)->first();

            if ($existingCheck) {
                $existingCheck->delete($user->id);
            }

            if ($user->cart) {
                $user->cart->delete();
            }
        }

        if (!$isAjax) {
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
        }
    }

    public function addToCart(?User $user, Event $product, Ticket $ticket, string $type)
    {
        // Let only one event in the cart added on 5/6/2018

        Cart::instance('default')->destroy();

        $price = (float) $ticket->pivot->price;
        $duplicates = Cart::search(function ($cartItem, $rowId) use ($ticket) {
            return $cartItem->id === $ticket->id;
        });

        if (!$duplicates->isEmpty()) {
            throw new ProductAlreadyInCartException('Item is already in your cart!');
        }

        if ($type == 5) {
            $quantity = 2;
        } else {
            $quantity = 1;
        }

        $item = Cart::add(
            $ticket->ticket_id,
            $product->title,
            $quantity,
            $price,
            ['type' => $type, 'event' => $product->id]
        )->associate(Ticket::class);

        //SAVE CART IF USER LOGGED
        if ($user) {
            $existingcheck = ShoppingCart::where('identifier', $user->id)->first();
            if ($existingcheck) {
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
        }

        return $item;
    }

    public function addFreeToCart(Event $product, string $ticket)
    {
        // Let only one event in the cart added on 5/6/2018
        Cart::instance('default')->destroy();

        $price = 0.0;
        $quantity = 1;

        return Cart::add(
            $ticket,
            $product->title,
            $quantity,
            $price,
            ['type' => $ticket, 'event' => $product->id]
        )->associate(Ticket::class);
    }

    public function updateCart(?User $user, array $requestData): void
    {
        $updates = $requestData['update'] ?? [];
        foreach ($updates as $key => $value) {
            Cart::update($key, $value['quantity']);
        }

        //UPDATE SAVED CART IF USER LOGGED
        if ($user) {
            $existingCheck = ShoppingCart::where('identifier', $user->id)->first();

            if ($user->cart) {
                $user->cart->delete();
            }

            if ($existingCheck) {
                $existingCheck->delete($user->id);
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

        Session::put('pay_seats_data', [
            'names' => $requestData['firstname'] ?? [],
            'surnames' => $requestData['lastname'] ?? [],
            'emails' => $requestData['email'] ?? [],
            'mobiles' => $requestData['mobile'] ?? [],
            'countryCodes' =>  $requestData['country_code'] ?? [],
            'cities' => $requestData['city'] ?? [],
            'jobtitles' => $requestData['jobtitle'] ?? [],
            'companies' => $requestData['company'] ?? [],
            'student_type_id' => $requestData['student_type_id'] ?? [],
        ]);
    }

    public function walletGetTotal(?User $user, array $requestData): float | int
    {
        $paymentMethodId = intval($requestData['payment_method_id']);
        $data = [];

        Session::put('payment_method_id', $paymentMethodId);

        if (isset($requestData['installments'])) {
            Session::put('installments', $requestData['installments']);
        } else {
            Session::put('installments', 1);
        }

        if (Session::get('coupon_code')) {
            $requestData['coupon'] = Session::get('coupon_code');
        }

        $data = $this->initCartDetails($user, $data);

        $installments = isset($requestData['installments']) ? $requestData['installments'] : 0;

        $instAmount = $data['price'];

        if ($installments > 1) {
            $instAmount = round($instAmount / $installments, 2);
        }

        return $instAmount * 100;
    }

    public function checkCoupon(array $requestData, string $event): array
    {
        $event = Event::find($event);
        $coupon = $event->coupons()->where('status', true)->get();

        if (count($coupon) > 1) {
            foreach ($coupon as $key => $c) {
                if ($c->code_coupon !== $requestData['coupon']) {
                    unset($coupon[$key]);
                }
            }
        }

        if (count($coupon) > 0) {
            $coupon = $coupon->first();

            if (trim($requestData['coupon']) === trim($coupon->code_coupon) && $coupon->status && trim($requestData['coupon']) != '') {
                if ($coupon->percentage) {
                    $price = $requestData['price'] * $coupon->price / 100;
                    $newPrice = $requestData['price'] - $price;
                    $priceOf = $coupon->price . '%';
                } else {
                    $newPrice = $coupon->price;
                    $priceOf = 100 - ($coupon->price / $requestData['price']) * 100;
                    $priceOf = round($priceOf, 2) . '%';
                }

                $savedPrice = $requestData['price'] - $newPrice;

                Session::put('coupon_code', $requestData['coupon']);
                Session::put('coupon_price', $newPrice);
                Session::put('priceOf', $priceOf);

                $instOne = $newPrice * $requestData['totalItems'];
                $instTwo = round($newPrice / 2, 2) * $requestData['totalItems'];
                $instThree = round($newPrice / 3, 2) * $requestData['totalItems'];
                $instFour = round($newPrice / 4, 2) * $requestData['totalItems'];

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

                return [
                    'success' => true,
                    'new_price' => $instOne,
                    'savedPrice' => round($savedPrice, 2) * $requestData['totalItems'],
                    'priceOf' => $priceOf,
                    'newPriceInt2' => $instTwo,
                    'newPriceInt3' => $instThree,
                    'newPriceInt4' => $instFour,
                    'message' => 'Success! Your coupon has been accepted.',
                    'coupon_code' => $requestData['coupon'],

                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Your coupon has been declined. Please try again.',
        ];
    }

    public function checkCode(?User $user, array $requestData): array
    {
        $event = Event::find($requestData['event'] ?? null);

        $code = $event->coupons()->where('code_coupon', $requestData['eventCode'] ?? null)->first();

        if (!$code) {
            return [
                'success' => false,
                'message' => 'The code you have entered is incorrect. Please try again.',
            ];
        } elseif ($code->used == 1) {
            return [
                'success' => false,
                'message' => 'The code you have entered is already taken. Please try another code.',
            ];
        } else {
            Cart::instance('default')->destroy();

            Cart::add(
                'free_code',
                $event->title,
                1,
                (float) 0,
                ['type' => 'free_code', 'event' => $event->id, 'code_id' => $code->id]
            )->associate(Ticket::class);

            $code->save();

            if ($user) {
                if ($user->cart) {
                    $user->cart->delete();
                }

                $cartCache = new CartCache;

                $cartCache->ticket_id = 0;
                $cartCache->product_title = $event->title;
                $cartCache->quantity = 1;
                $cartCache->price = (float) 0;
                $cartCache->type = 9;
                $cartCache->event = $event->id;
                $cartCache->user_id = $user->id;
                $cartCache->slug = base64_encode('coupon code ' . $event->id . $user->id . $event->id);

                $cartCache->save();
            }

            return [
                'success' => true,
                'message' => 'To event προστεθηκε στο καλάθι',
                'redirect' => '/registration',
            ];
        }
    }

    public function completeRegistration(array $requestData, string $requestIp): array
    {
        DB::beginTransaction();

        try {
            $data = [];
            $option = Option::where('abbr', 'deree-codes')->first();
            $code = 0;

            $c = Cart::content()->count();

            if ((!$user = User::where('email', $requestData['email'][0])->first())) {
                $input = [];
                $formData = $requestData;
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
                $user->terms = 1;
                $consent['ip'] = $requestIp;
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
                'ip_address' => $requestIp,
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

                $pay_seats_data = [
                    'names' => [$requestData['firstname'][0]],
                    'surnames' => [$requestData['lastname'][0]],
                    'emails' => [$requestData['email'][0]],
                    'mobiles' => [$requestData['mobile'][0]],
                    'addresses' => [$user->address],
                    'addressnums' => [$user->address_num],
                    'postcodes' => [$user->postcode],
                    'cities' => [$user->city],
                    'jobtitles' => [$user->job_title],
                    'companies' => [$user->company],
                    'students' => [''],
                    'afms' => [$user->afm],
                ];

                $deree_user_data = [$user->email => $user->partner_id];

                $cart_data = [
                    'manualtransaction' => [
                        'rowId' => 'manualtransaction',
                        'id' => 'coupon code ' . $content->id,
                        'name' => $content->title,
                        'qty' => '1',
                        'price' => $amount,
                        'options' => [
                            'type' => '9',
                            'event'=> $content->id,
                        ],
                        'tax' => 0,
                        'subtotal' => $amount,
                    ],
                ];

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

            DB::commit();

            return $data;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }
    }

    /** Not refactored */
    public function sendEmails($transaction, $content, $user): void
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
            $data['firstName'] = $user->firstname ?? '';
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

    /** Not refactored */
    public function securePayment(?User $user, array $requestData, string $requestIp): bool
    {
        $success = false;

        Session::forget('dperror');
        Session::forget('error');

        $input = decrypt($requestData['input'] ?? null);
        $charge = $requestData['paymentIntent'] ?? null;
        $namount = $input['total_amount'];

        $dpuser = $user ? $user : User::find(Session::get('user_id'));
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
        $initialAmount = $amount;

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
            } else {
                $temp['billing'] = 'Invoice requested';
                //generate array for stripe billing
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
            $success = true;

            $status_history = [];
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
                'installments' => $installments,
                'cart_data' => $cart,
            ];

            $coupon = Coupon::where('code_coupon', $input['couponCode'])->first();

            $transaction = Transaction::create([
                'payment_method_id' => 100, //$input['payment_method_id'],
                'account_id' => 17,
                'payment_status' => 2,
                'billing_details' => $bd,
                'status_history' => json_encode($status_history),
                'placement_date' => Carbon::now()->toDateTimeString(),
                'ip_address' => $requestIp,
                'status' => 1, //2 PENDING, 0 FAILED, 1 COMPLETED
                'is_bonus' => 0,
                'order_vat' => 0,
                'payment_response' => json_encode($charge),
                'surcharge_amount' => 0,
                'discount_amount' => 0,
                'coupon_code' => $input['couponCode'],
                'coupon_details' => isset($coupon) ? json_encode($coupon) : $coupon,
                'amount' => $input['total_amount'],
                'total_amount' => $input['total_amount'],
                'initial_amount' => $initialAmount,
                'trial' => false,
            ]);

            if ($transaction) {
                $transaction->event()->save($ev);

                if ($installments <= 1) {
                    $paymentMethodId = $ev->paymentMethod->first() ? $ev->paymentMethod->first()->id : -1;
                    $elearningInvoice = new Invoice();
                    $elearningInvoice->name = json_decode($transaction->billing_details, true)['billname'];
                    $elearningInvoice->amount = round($namount / $installments, 2);
                    $elearningInvoice->invoice = generate_invoice_number($paymentMethodId);
                    $elearningInvoice->date = date('Y-m-d'); //Carbon::today()->toDateString();
                    $elearningInvoice->instalments_remaining = $installments;
                    $elearningInvoice->instalments = $installments;

                    $elearningInvoice->save();

                    $elearningInvoice->event()->save($ev);
                    $elearningInvoice->transaction()->save($transaction);
                }

                Session::put('transaction_id', $transaction->id);
            }
        }

        return $success;
    }
}
