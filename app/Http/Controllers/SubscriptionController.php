<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaction;
use App\Model\Plan;

class SubscriptionController extends Controller
{
    public function index()
    {
        $data['subscriptions'] = Transaction::with('user', 'subscription.event')->has('subscription')->get();
        //dd($data['subscriptions'][0]);
        $plans = Plan::all()->groupby('stripe_plan');
        //dd($plans);
        foreach($data['subscriptions'] as $key => $sub){

            $planId = $sub['subscription']->first()['stripe_price'];
            //$plan = Plan::where('stripe_plan', $planId)->first();
            dd($plans[$planId]);
            $data['subscriptions'][$key]['subscription']->first()['plan_name'] = $plans[$planId]['name'];
        }

        //dd($data['subscriptions'][0]);

        return view('admin.subscription.subscription_list', $data);
    }
}
