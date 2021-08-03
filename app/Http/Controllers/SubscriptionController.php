<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaction;

class SubscriptionController extends Controller
{
    public function index()
    {
        $data['subscriptions'] = Transaction::with('user', 'event', 'subscription.event')->has('subscription')->get();

        //dd($data['subscriptions']);

        return view('admin.subscription.subscription_list', $data);
    }
}
