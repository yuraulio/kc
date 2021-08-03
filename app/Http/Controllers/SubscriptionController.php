<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaction;

class SubscriptionController extends Controller
{
    public function index()
    {
        // $data['subscriptions'] = Subscriptions::with('user.statistic', 'event.users',)->orderBy('created_at', 'DESC')->doesnthave('subscription')->get();
        //dd($data['transactions'][0]);
        //$subs = new Subscription;
        $data['subscriptions'] = Transaction::with('user', 'event', 'subscription.event')->has('subscription')->get();

        return view('admin.subscription.subscription_list', $data);
    }
}
