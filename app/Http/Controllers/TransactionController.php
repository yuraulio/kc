<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaction;

class TransactionController extends Controller
{
    public function participants()
    {
        $data['transactions'] = Transaction::with('user.statistic', 'event')->orderBy('created_at', 'DESC')->has('event')->get();
        //dd($data['transactions']);

        return view('admin.transaction.participants', $data);
    }

    public function updateExpirationDate(Request $request)
    {
        $transaction_id = $request->id;
        $date = $request->date;

        //dd($date);

        $transaction = Transaction::find($transaction_id);
        $old_date = explode(" ",$transaction->ends_at);

        //dd($old_date);

        $new_date = $date.' '.$old_date[1];
        $new_date = date('Y-m-d H:i:s', strtotime($new_date));
        //dd($new_date);

        Transaction::where('id',$transaction_id)->update(['ends_at' => $new_date]);

        $data['id'] = $transaction_id;
        $data['date'] = $new_date;

        return response()->json([
            'message' => 'Expiration date has updated!',
            'data' => $data
        ]);
    }
}
