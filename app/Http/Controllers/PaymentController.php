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

class PaymentController extends Controller
{
    /**
     * Create a new PaymentController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(VerifyRedirectUrl::class);
    }

    /**
     * Display the form to gather additional payment verification for the given payment.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id,$paymentMethod,$input)
    {
        
        //$paymentMethod = 3;
        session()->put('payment_method',$paymentMethod);
        $paymentMethod = PaymentMethod::find($paymentMethod);

        //dd($request->all());

        $payment = new Payment(Cashier::stripe()->paymentIntents->retrieve(
            $id, ['expand' => ['payment_method']])
        );

        $paymentIntent = Arr::only($payment->asStripePaymentIntent()->toArray(), [
            'id', 'status', 'payment_method_types', 'client_secret', 'payment_method',
        ]);

        $paymentIntent['payment_method'] = Arr::only($paymentIntent['payment_method'] ?? [], 'id');
        $input = decrypt($input);
       
        return view('cashier.payment', [
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
            'input' => $input,
        ]);
    }
}
