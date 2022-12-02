<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;
use App\Model\Transaction;
use App\Model\Event;
use Excel;
use App\Exports\SubscriptionExport;

use App\Model\Plan;

class SubscriptionController extends Controller
{

    public function index()
    {
        $subscriptions = [];

        $events = Event::has('transactions')->with('category', 'delivery')->get()->groupBy('id');
        $data['subscriptions'] = Transaction::with('user', 'user.events_for_user_list', 'subscription.event', 'subscription.event.category')->get()->sortBy('created_at');
        // $plans = Plan::all()->groupby('stripe_plan');
        //$plans = Plan::with('categories')->get()->groupBy('id');
        $plans = Plan::wherePublished(1)->with('categories')->get();
        $categories = [];

        foreach($plans as $plan){
            $categories = array_merge($plan->categories->pluck('id')->toArray(),$categories);
        }

        $users = [];

        foreach($data['subscriptions'] as $key => $sub){
            $events = [];
            if(count($sub['subscription']) != 0){

                if(!isset($sub['subscription'][0]['event'][0]['title'])){
                    continue;
                }

                if(isset($users[$sub['user'][0]['id']])){
                    $users[$sub['user'][0]['id']][] = $sub['subscription'][0]['event'][0]['id'];
                }else{
                    $users[$sub['user'][0]['id']] = [];
                    $users[$sub['user'][0]['id']][] = $sub['subscription'][0]['event'][0]['id'];
                }

                $status = $sub['subscription'][0]['stripe_status'];

                if($sub['trial'] && $status == 'trialing'){
                    $status = 'trialing';
                }else if($status == 'active' && $sub['subscription'][0]['status'] && !$sub['trial']){
                    $status = 'active';
                }else if(($status == 'cancelled' || $status == 'cancel' || $status == 'canceled') && !$sub['trial']){
                    $status = 'paid_and_cancelled';
                }else if(($status == 'cancelled' || $status == 'cancel' || $status == 'canceled') && $sub['trial']){
                    $status = 'cancelled';
                }
                //dd($sub['user']);
                $registrationEvent = null;
                foreach($sub['user'][0]['events_for_user_list'] as $userEvent){
                    $category = isset($userEvent['category'][0]['id']) ? $userEvent['category'][0]['id'] : -1;
                    if(in_array($category,$categories)){
                        $registrationEvent = $userEvent;
                    }
                }
             	
                $name = $sub['user'][0]['firstname'] . ' ' . $sub['user'][0]['lastname'];
                $amount = '€'.number_format(intval($sub['total_amount']), 2, '.', '');
              
              	$delivery = '-';
              	if($registrationEvent){
                  $delivery = $registrationEvent->is_inclass_course() ? 'In-Class' : 'E-Learning';
                }
                

                $subscriptions[$sub['subscription'][0]['stripe_id']]=['user_id' => $sub['user'][0]['id'], 'user' => $name, 'plan_id' => $sub['subscription'][0]['event'][0]['plans'][0]['id'], 'plan_name' => $sub['subscription'][0]['name'],
                    'event_title' => $sub['subscription'][0]['event'][0]['title'], 'status' => $status,'ends_at'=>$sub['ends_at'],
                    'amount' => $amount,'created_at'=>date('Y-m-d',strtotime($sub['created_at'])),'id'=>$sub['id'], 
                    'event_id' => $sub['subscription'][0]['event'][0]['id'], 'delivery' => $delivery];

            }


        }

        //dd($subscriptions);
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

    public function exportExcel(Request $request){

        //$fromDate = date('Y-m-d',strtotime($request->fromDate));
        //$toDate = $request->toDate ? date('Y-m-d',strtotime($request->toDate)) : date('Y-m-d');

        //I am HERE
        Excel::store(new SubscriptionExport($request), 'SubscriptionsExport.xlsx', 'export');
        return Excel::download(new SubscriptionExport($request), 'SubscriptionsExport.xlsx');
    }

}
