<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Event;
use App\Model\Plan;
use Auth;
use \Stripe\Stripe;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        //$this->middleware('event.subscription')->only(['index','store']);
    }


    public function index($event,$plan)
    {     
        $plan = Plan::where('name',$plan)->first();
        $event = Event::where('title',$event)->first();
        session()->put('payment_method',$event->paymentMethod->first()->id);
        $stripe = Stripe::setApiKey($event->paymentMethod->first()->processor_options['secret_key']);
        
        $user = Auth::user();
        $user->asStripeCustomer();
        $data['plan'] = $plan;
        $data['event'] = $event;
        $data['cur_user'] = $user;
        $data['default_card'] = $user->defaultPaymentMethod() ? $user->defaultPaymentMethod()->card : false;
        
        
        $data['stripe_key'] = env('PAYMENT_PRODUCTION') ? $event->paymentMethod->first()->processor_options['key'] : 
                                                                $event->paymentMethod->first()->test_processor_options['key'];

        return view('theme.cart.subscription-form', $data);
       
    }
}
