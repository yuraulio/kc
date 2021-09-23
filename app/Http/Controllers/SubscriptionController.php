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

        $subscriptions = [];

        $data['subscriptions'] = Transaction::with('user', 'subscription.event')->get()->toArray();
        $plans = Plan::all()->groupby('stripe_plan');

        $status = $sub['subscription'][0]['stripe_status'];

        if($status == 'canceled'){
            $status = 'cancel';
        }

        foreach($data['subscriptions'] as $key => $sub){

            if(count($sub['subscription']) != 0){
               
                $name = $sub['user'][0]['firstname'] . ' ' . $sub['user'][0]['lastname'];
                $amount = '€'.number_format(intval($sub['total_amount']), 2, '.', '');
                $subscriptions[]=['user' => $name, 'plan_name' => $sub['subscription'][0]['name'], 
                    'event_title' => $sub['subscription'][0]['event'][0]['title'], 'status' => $sub['subscription'][0]['stripe_status'],'ends_at'=>$sub['ends_at'],
                    'amount' => $amount,'created_at'=>$sub['created_at'],'id'=>$sub['id']];

            }


        }   

        $data['subscriptions'] = $subscriptions;

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
