<?php

namespace Laravel\Cashier\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Http\Middleware\VerifyRedirectUrl;
use Laravel\Cashier\Payment;
use App\Model\PaymentMethod;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Session;
use App\Model\Event;

class PaymentController extends Controller
{
    /**
     * Create a new PaymentController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware(VerifyRedirectUrl::class);
    }

    /**
     * Display the form to gather additional payment verification for the given payment.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id,$input)
    {
        //dd('dfa');
        $input = decrypt($input);
        $paymentMethod = $input['paymentMethod'];
        session()->put('payment_method',$paymentMethod);
        Session::put('noActionEmail',true);

        $duration = isset($input['duration']) ? $input['duration'] : '';

        $paymentMethod = PaymentMethod::find($paymentMethod);

        //dd($request->all());

        $payment = new Payment(Cashier::stripe()->paymentIntents->retrieve(
            $id, ['expand' => ['payment_method']])
        );

        $paymentIntent = Arr::only($payment->asStripePaymentIntent()->toArray(), [
            'id', 'status', 'payment_method_types', 'client_secret', 'payment_method',
        ]);

        $paymentIntent['payment_method'] = Arr::only($paymentIntent['payment_method'] ?? [], 'id');
        $input = encrypt($input);
        
        $price = $payment->amount();

        return view('cashier.payment', [
            'stripeKey' => env('PAYMENT_PRODUCTION') ? $paymentMethod->processor_options['key'] : $paymentMethod->test_processor_options['key'],
            'amount' => $payment->amount(),
            'payment' => $payment,
            'price' => $price,
            'duration' => $duration,
            'paymentIntent' => array_filter($paymentIntent),
            'paymentMethod' => (string) request('source_type', optional($payment->payment_method)->type),
            'errorMessage' => request('redirect_status') === 'failed'
                ? 'Something went wrong when trying to confirm the payment. Please try again.'
                : '',
            'customer' => $payment->customer(),
            'redirect' => url(request('redirect', '/')),
            'input' => $input,
        ]);
    }

    public function requiredAction($id,Event $event,$paymentMethod,$subscriptionCheckout=false)
    {
        $paymentMethod = decrypt($paymentMethod);
        session()->put('payment_method',$paymentMethod);
        $paymentMethod = PaymentMethod::find($paymentMethod);

        $payment = new Payment(Cashier::stripe()->paymentIntents->retrieve(
            $id, ['expand' => ['payment_method']])
        );

        $price = $payment->amount();

        $paymentIntent = Arr::only($payment->asStripePaymentIntent()->toArray(), [
            'id', 'status', 'payment_method_types', 'client_secret', 'payment_method',
        ]);

        $paymentIntent['payment_method'] = Arr::only($paymentIntent['payment_method'] ?? [], 'id');
    
        $payment = new Payment(Cashier::stripe()->paymentIntents->retrieve(
            $id, ['expand' => ['payment_method']])
        );

        $paymentIntent = Arr::only($payment->asStripePaymentIntent()->toArray(), [
            'id', 'status', 'payment_method_types', 'client_secret', 'payment_method',
        ]);

        $paymentIntent['payment_method'] = Arr::only($paymentIntent['payment_method'] ?? [], 'id');

        if(!$subscriptionCheckout){
            $duration = $event->summary1->where('section','date')->first() ? $event->summary1->where('section','date')->first()->title : 'date';
        }

        $data['info']['success'] = true;
        $data['info']['title'] = __('thank_you_page.title');
        $data['info']['message'] = __('thank_you_page.message');
        $data['event']['title'] = $event->title;
        $data['event']['slug'] = $event->slugable->slug;
        $data['event']['facebook'] = url('/') . '/' .$event->slugable->slug .'?utm_source=Facebook&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&quote='.urlencode("Proudly participating in ". $event->title . " by KnowCrunch.");
        $data['event']['twitter'] = urlencode("Proudly participating in ". $event->title . " by KnowCrunch. 💙");
        $data['event']['linkedin'] = urlencode(url('/') . '/' .$event->slugable->slug .'?utm_source=LinkedIn&utm_medium=Post_Student&utm_campaign=KNOWCRUNCH_BRANDING&title='."Proudly participating in ". $event->title . " by KnowCrunch. 💙");

        return view('cashier.action_required', [
            'stripeKey' => env('PAYMENT_PRODUCTION') ? $paymentMethod->processor_options['key'] : $paymentMethod->test_processor_options['key'],
            'amount' => $payment->amount(),
            'payment' => $payment,
            'paymentIntent' => array_filter($paymentIntent),
            'paymentMethod' => (string) request('source_type', optional($payment->payment_method)->type),
            'errorMessage' => request('redirect_status') === 'failed'
                ? 'Something went wrong when trying to confirm the payment. Please try again.'
                : '',
            'customer' => $payment->customer(),
            'redirect' => url(request('redirect', '/')),
            'price' => $price,
            'duration' => $duration,
            'eventName' => $event->title,
            'data' => $data
        ]);

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

        return Redirect::to('/');

    }

}
