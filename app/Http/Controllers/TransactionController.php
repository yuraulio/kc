<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaction;
use App\Model\Event;

class TransactionController extends Controller
{
    public function participants()
    {
        $data['transactions'] = Transaction::with('user.statistic', 'event.users',)->orderBy('created_at', 'DESC')->has('event')->get();
        //dd($data['transactions'][0]);

        return view('admin.transaction.participants', $data);
    }

    public function updateExpirationDate(Request $request)
    {
        $transaction_id = $request->id;
        $date = $request->date;

        $transaction = Transaction::with('user', 'event')->find($transaction_id);

        $user = $transaction->user[0]['id'];
        $event = $transaction->event[0]['id'];

        $event = Event::find($event);

        $old_date = explode(" ",$event->users()->wherePivot('user_id',$user)->first()->pivot->expiration);


        $new_date = $date.' '.$old_date[1];
        $new_date = date('Y-m-d H:i:s', strtotime($new_date));
        $res_new_date = date('d/m/Y', strtotime($new_date));

        $event->users()->wherePivot('user_id',$user)->updateExistingPivot($user,[
            'expiration' => $new_date
        ], false);


        $data['id'] = $transaction_id;
        $data['date'] = $res_new_date;

        return response()->json([
            'message' => 'Expiration date has updated!',
            'data' => $data
        ]);
    }
}
