<?php

namespace App\Http\Controllers\Theme;

use App\Exceptions\ProductAlreadyInCartException;
use App\Http\Controllers\Controller;
use App\Model\Event;
use App\Services\CartService;
use App\Services\FBPixelService;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Cart as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mail;
use Redirect;

class CartController extends Controller
{
    public function __construct(
        private readonly FBPixelService $fbp,
        private readonly CartService $cartService,
    ) {
        $this->middleware('cart')->except('cartIndex', 'completeRegistration', 'validation', 'checkCode', 'add');
        $this->middleware('code.event')->only('completeRegistration');
        $this->middleware('registration.check')->except('cartIndex', 'completeRegistration', 'validation', 'checkCode', 'add');
        $this->middleware('billing.check')->only('billingIndex', 'billing', 'checkoutIndex');
        $fbp->sendPageViewEvent();
    }

    /**
     * This function validates the phone numbers on the https://knowcrunch.com/registration page before form submit
     * I don't know why it wasn't implemented as formRequest validation rule by previous developer (<antonismoul>).
     */
    public function mobileCheck(Request $request): array
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
     * This function renders the https://knowcrunch.com/registration page.
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

        $data = $this->cartService->initCartDetails(Auth::user(), $data);

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

    /**
     * This function store the data from https://knowcrunch.com/registration page.
     */
    public function registration(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->cartService->registerStudents(
            auth()->user(),
            $request->all(),
            $request->ip() ?? ''
        );

        return redirect('/billing');
    }

    /**
     * This function renders the https://knowcrunch.com/billing page.
     */
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

        $data = $this->cartService->initCartDetails(Auth::user(), $data);

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
    }

    /**
     * This function stores from the https://knowcrunch.com/billing page.
     */
    public function billing(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->cartService->storeBillingData($request->all(), auth()->user());

        return redirect('/checkout');
    }

    /**
     * This function renders the https://knowcrunch.com/checkout page.
     */
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

        $data = $this->cartService->initCartDetails(Auth::user(), $data);
        $this->fbp->sendAddBillingInfoEvent($data);

        return view('theme.cart.new_cart.checkout', $data);
    }

    /**
     * This function adds event to the cart.
     */
    public function add(
        string $id,
        string $ticket,
        string $type,
        Request $request
    ): \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse {
        $user = auth()->user();

        if ((!$user || ($user && !$user->kc_id)) && $type == 3) {
            return back();
        }

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

        $isAjax = $request->ajax();
        $product = Event::find($id);

        // Check if the product exists on the database
        if (!$product) {
            if ($isAjax) {
                return response()->json('Product was not found!', 404);
            }

            return redirect()->to('/');
        }

        if ($ticket == 'free' || $ticket == 'waiting') {
            $this->cartService->addFreeToCart($product, $ticket);
        } else {
            if (!isset($product->ticket->groupBy('ticket_id')[$ticket])) {
                return redirect($product->slugable->slug);
            }

            $ticketModel = $product->ticket->groupBy('ticket_id')[$ticket]->first();

            try {
                $item = $this->cartService->addToCart($user, $product, $ticketModel, $type);
            } catch (ProductAlreadyInCartException $exception) {
                return redirect('cart')->withSuccessMessage($exception->getMessage());
            }
        }

        if ($isAjax) {
            return response()->json($item->toArray());
        }

        if ($ticket == 'free') {
            return response()
                ->redirectTo('/registration')
                ->with(
                    'success',
                    'Free ticket was successfully added to your bag.'
                );
        } elseif ($ticket == 'waiting') {
            return response()->redirectTo('/registration');
        } else {
            return response()
                ->redirectTo('/registration')
                ->with(
                    'success',
                    "{$ticketModel->title} was successfully added to your bag."
                );
        }
    }

    /**
     * This function calculates total amount in cart.
     */
    public function walletGetTotal(Request $request): float | int
    {
        return $this->cartService->walletGetTotal(
            auth()->user(),
            $request->all(),
        );
    }

    /**
     * This function makes payment using stored in stripe database user's payment method.
     */
    public function walletPay(Request $request): string
    {
        return $this->cartService->generateStripeWalletPayUrl(
            auth()->user(),
            $request->all(),
            $request->ip() ?? '',
        );
    }

    /**
     * This function makes payment by card.
     */
    public function userPaySbt(Request $request): \Illuminate\Http\RedirectResponse
    {
        return redirect(
            $this->cartService->paySbt(auth()->user(), $request->all(), $request->ip())
        );
    }

    /**
     * This function makes payment by SEPA.
     */
    public function createSepa(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $this->cartService->paySEPA(
                auth()->user(),
                $request->all(),
                $request->ip() ?? '',
            )
        );
    }

    /**
     * This function removes product (event) from cart.
     */
    public function dpremove(string $item): \Illuminate\Http\RedirectResponse | \Illuminate\Http\JsonResponse
    {
        $isAjax = request()->ajax();

        $this->cartService->removeProductFromCart(auth()->user(), $item, $isAjax);

        if ($isAjax) {
            return response()->json(['message' => 'success', 'id' => $item]);
        }

        return response()->redirectTo('/registration');
    }

    /**
     * This function checks the coupon for specific event
     * Reminder: only one item can be added to the cart.
     */
    public function checkCoupon(Request $request, string $event): \Illuminate\Http\JsonResponse
    {
        if (Session::get('coupon_code')) {
            return response()->json([
                'success' => 'used',
                'message' => 'Your coupon has been declined. Please try again.',
            ]);
        }

        return response()
            ->json(
                $this->cartService->checkCoupon(
                    $request->all(),
                    $event,
                )
            );
    }

    /**
     * This function checks the coupon for specific event
     * Used only if Event -> Content -> Overview -> View tpl is Event Free coupon
     * Tested 20.10.24.
     */
    public function checkCode(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $this->cartService->checkCode(
                auth()->user(),
                $request->all(),
            )
        );
    }

    /**
     * This function enrols user to the event and creates transaction (probably part of checkCode functionality)
     * Tested 20.10.24.
     */
    public function completeRegistration(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $this->cartService->completeRegistration(
            $request->all(),
            $request->ip() ?? '',
        );

        Session::put('thankyouData', $data);
        try {
            session_start();
            $_SESSION['thankyouData'] = $data;
        } catch(\Exception $ex) {
            Bugsnag::notifyException($ex);
        }

        return redirect('/thankyou');
    }

    /**
     * This function updates cart.
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->cartService->updateCart(auth()->user(), $request->all());

        return response()
            ->redirectTo('/registration')
            ->with('success', 'Shopping cart was successfully updated.');
    }

    /**
     * This function used to proceed 3D secure payments.
     *
     * Stripe test card 4000 0027 6000 3184
     * This card requires authentication on all transactions, regardless of how the card is set up.
     */
    public function securePayment(Request $request)
    {
        $result = $this->cartService->securePayment(
            auth()->user(),
            $request->all(),
            $request->ip(),
        );

        if ($result) {
            return response()->json([
                'success' => true,
                'redirect' => '/order-success',
            ]);
        }
    }
}
