<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;
use App\Model\Transaction;

use App\Model\Plan;

class SubscriptionController extends Controller
{



public function index()
    {
        $data['subscriptions'] = Transaction::with('user', 'subscription.event')->get()->toArray();
        $plans = Plan::all()->groupby('stripe_plan');
        foreach($data['subscriptions'] as $key => $sub){

            if(count($sub['subscription']) != 0){
                $planId = $sub['subscription'][0]['stripe_price'];
                $data['new_sub'][$key] = $sub;

                $data['new_sub'][$key]['subscription'][0]['plan_name'] = $plans[$planId]->first()['name'];
            }


        }

        return view('admin.subscription.subscription_list', $data);
    }

    public function subs_for_dashboard(){

        $data['subscriptions'] = Transaction::with('user', 'subscription.event')->get()->toArray();
        $plans = Plan::all()->groupby('stripe_plan');
        foreach($data['subscriptions'] as $key => $sub){

            if(count($sub['subscription']) != 0){
                $planId = $sub['subscription'][0]['stripe_price'];
                $data['new_sub'][$key] = $sub;

                $data['new_sub'][$key]['subscription'][0]['plan_name'] = $plans[$planId]->first()['name'];
            }
        }

        return $data['new_sub'];

    }

}
