<?php

namespace App\Http\Controllers;

use App\Exports\SubscriptionExport;
use App\Model\Event;
use App\Model\Plan;
use App\Model\Transaction;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;

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
        //dd(Transaction::whereHas('subscription')->sum('amount'));
        foreach ($plans as $plan) {
            $categories = array_merge($plan->categories->pluck('id')->toArray(), $categories);
        }

        $users = [];

        $data['total_users'] = 0;
        $total_users = [];

        foreach ($data['subscriptions'] as $key => $sub) {
            $events = [];
            if (count($sub['subscription']) != 0) {
                if (!isset($sub['subscription'][0]['event'][0]['title'])) {
                    $subEvId = -1;
                    $titleE = '-';
                    $plId = -1;
                } else {
                    $subEvId = $sub['subscription'][0]['event'][0]['id'];
                    $titleE = $sub['subscription'][0]['event'][0]['title'];
                    $plId = $sub['subscription'][0]['event'][0]['plans'][0]['id'];
                }

                if (isset($users[$sub['user'][0]['id']])) {
                    $users[$sub['user'][0]['id']][] = $subEvId;
                } else {
                    $users[$sub['user'][0]['id']] = [];
                    $users[$sub['user'][0]['id']][] = $subEvId;
                }

                $status = $sub['subscription'][0]['stripe_status'];

                if ($sub['trial'] && $status == 'trialing') {
                    $status = 'trialing';
                } elseif ($status == 'active' && $sub['subscription'][0]['status'] == 1 && !$sub['trial']) {
                    $nowDate = Carbon::now();

                    if ($sub['ends_at']) {
                        $sub_ends_at = Carbon::createFromFormat('Y-m-d H:i:s', $sub['ends_at']);

                        if ($sub_ends_at->gte($nowDate)) {
                            $status = 'active';
                        }
                    }
                } elseif ($status == 'active' && $sub['subscription'][0]['status'] == 0 && !$sub['trial']) {
                    $status = 'paid_not_active_user_canceled';
                } elseif (($status == 'cancelled' || $status == 'cancel' || $status == 'canceled') && !$sub['trial']) {
                    $status = 'paid_and_cancelled';
                } elseif (($status == 'cancelled' || $status == 'cancel' || $status == 'canceled') && $sub['trial']) {
                    $status = 'cancelled';
                } else {
                    $status = '';
                }
                //dd($sub['user']);
                $registrationEvent = null;
                foreach ($sub['user'][0]['events_for_user_list'] as $userEvent) {
                    $category = isset($userEvent['category'][0]['id']) ? $userEvent['category'][0]['id'] : -1;
                    if (in_array($category, $categories)) {
                        $registrationEvent = $userEvent;
                    }
                }

                $name = $sub['user'][0]['firstname'] . ' ' . $sub['user'][0]['lastname'];
                $amount = '€' . number_format(intval($sub['total_amount']), 2, '.', '');

                $delivery = '-';
                if ($registrationEvent) {
                    $delivery = $registrationEvent->is_inclass_course() ? 'In-Class' : 'E-Learning';
                }

                $total_users[$name] = $name;

                $subscriptions[$sub['id']] = ['user_id' => $sub['user'][0]['id'], 'user' => $name, 'plan_id' => $plId, 'plan_name' => $sub['subscription'][0]['name'],
                    'event_title' => $titleE, 'status' => $status, 'ends_at'=>$sub['ends_at'],
                    'amount' => $amount, 'created_at'=>date('Y-m-d', strtotime($sub['created_at'])), 'id'=>$sub['id'],
                    'event_id' => $subEvId, 'delivery' => $delivery];
            }
        }

        //dd($subscriptions);
        $data['total_users'] = count($total_users);
        $data['subscriptions'] = $subscriptions;

        return view('admin.subscription.subscription_list', $data);
    }

    public function subs_for_dashboard()
    {
        $data['subscriptions'] = Transaction::with('user', 'subscription.event')->get()->toArray();
        $plans = Plan::all()->groupby('stripe_plan');
        foreach ($data['subscriptions'] as $key => $sub) {
            if (count($sub['subscription']) != 0) {
                $planId = $sub['subscription'][0]['stripe_price'];
                $data['new_sub'][$key] = $sub;

                $data['new_sub'][$key]['subscription'][0]['plan_name'] = $plans[$planId]->first()['name'];
            }
        }

        return $data['new_sub'];
    }

    public function exportExcel(Request $request)
    {
        //$fromDate = date('Y-m-d',strtotime($request->fromDate));
        //$toDate = $request->toDate ? date('Y-m-d',strtotime($request->toDate)) : date('Y-m-d');

        //I am HERE
        Excel::store(new SubscriptionExport($request), 'SubscriptionsExport.xlsx', 'export');

        return Excel::download(new SubscriptionExport($request), 'SubscriptionsExport.xlsx');
    }
}
